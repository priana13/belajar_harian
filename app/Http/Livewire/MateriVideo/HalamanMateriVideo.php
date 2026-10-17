<?php

namespace App\Http\Livewire\MateriVideo;

use App\Models\Belajar;
use App\Models\JadwalRoadmap;
use App\Models\Materi;
use App\Models\MateriDetailUser;
use Livewire\Component;

class HalamanMateriVideo extends Component
{
    public $kode;

    public $materi;

    public $materiDetail;

    public $selectedVideo;

    public function mount($kode)
    {
        $this->authorizePremium();

        $this->kode = $kode;

        $this->materi = Materi::where('kode_materi', $kode)->first();

        if ($this->materi) {

            $this->materiDetail = $this->materi->materi_detail;

        } else {

            abort(404, 'Materi not found');
        }

        $availableIds = $this->availableVideoIds();
        $this->selectedVideo = $this->materiDetail->first(fn ($video) => in_array($video->id, $availableIds));

    }

    public function render()
    {
        $this->authorizePremium();
        $availableIds = $this->availableVideoIds();
        if ($this->selectedVideo && ! in_array($this->selectedVideo->id, $availableIds)) {
            $this->selectedVideo = null;
        }

        $completedIds = MateriDetailUser::where('user_id', auth()->id())
            ->whereIn('materi_detail_id', $this->materiDetail->modelKeys())
            ->whereIn('status', ['sudah baca', 'sudah paham', 'sudah ujian'])
            ->pluck('materi_detail_id')->all();

        return view('livewire.materi-video.halaman-materi-video', [
            'videoEmbedUrl' => $this->getVideoEmbedUrlProperty(),
            'availableIds' => $availableIds,
            'completedIds' => $completedIds,
        ])->extends('layouts.video-app')->section('content');
    }

    public function selectVideo($id)
    {
        $this->authorizePremium();
        $video = $this->materiDetail->find($id);

        if ($video && in_array($video->id, $this->availableVideoIds())) {
            $this->selectedVideo = $video;
        }
    }

    public function getVideoEmbedUrlProperty(): ?string
    {
        $url = trim($this->selectedVideo?->video_url ?? '');

        if ($url === '') {
            return null;
        }

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return 'https://www.youtube.com/embed/'.$url;
        }

        if (preg_match('~^(?:(?:www|m)\.)?(?:youtube\.com|youtu\.be|youtube-nocookie\.com)/~i', $url)) {
            $url = 'https://'.$url;
        }

        $parts = parse_url($url);
        if (! $parts || ! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        $path = trim($parts['path'] ?? '', '/');
        $youtubeHosts = ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'];

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $videoId = explode('/', $path)[0];
        } elseif (in_array($host, $youtubeHosts, true)) {
            parse_str($parts['query'] ?? '', $query);
            $videoId = $path === 'watch' ? ($query['v'] ?? '') : '';
            if (preg_match('~^(?:embed|shorts|live|v)/([^/]+)~', $path, $matches)) {
                $videoId = $matches[1];
            }
        } else {
            return $url;
        }

        return is_string($videoId) && preg_match('/^[a-zA-Z0-9_-]{11}$/', $videoId)
            ? 'https://www.youtube.com/embed/'.$videoId
            : null;
    }

    private function authorizePremium(): void
    {
        abort_unless(auth()->user()?->canAccessPremium(), 403, 'Langganan Premium aktif diperlukan.');
    }

    private function availableVideoIds(): array
    {
        $user = auth()->user();
        if (! $user) {
            return [];
        }

        $groupScheduleIds = JadwalRoadmap::whereIn('group_id', $user->groups()->pluck('groups.id'))
            ->pluck('id');

        return Belajar::whereIn('materi_detail_id', $this->materiDetail->modelKeys())
            ->aktif()
            ->whereDate('tanggal', '<=', today())
            ->where(function ($query) use ($user, $groupScheduleIds) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($shared) use ($user, $groupScheduleIds) {
                        $shared->whereNull('user_id')->where(function ($schedule) use ($user, $groupScheduleIds) {
                            $schedule->whereIn('jadwal_roadmap_id', $groupScheduleIds);
                            if ($user->gelombang_id) {
                                $schedule->orWhere('gelombang_id', $user->gelombang_id);
                            }
                        });
                    });
            })
            ->distinct()->pluck('materi_detail_id')->all();
    }

    public function sudahPaham(): void
    {
        $this->authorizePremium();
        $video = $this->materiDetail->find($this->selectedVideo?->id);
        abort_unless($video && in_array($video->id, $this->availableVideoIds()), 403);

        $progress = MateriDetailUser::firstOrCreate([
            'user_id' => auth()->id(),
            'materi_detail_id' => $video->id,
        ], [
            'status' => 'sudah baca',
        ]);

        if (! in_array($progress->status, ['sudah baca', 'sudah paham', 'sudah ujian'], true)) {
            $progress->update(['status' => 'sudah baca']);
        }

        session()->flash('video_progress', 'Materi berhasil ditandai sudah dipahami.');
    }
}
