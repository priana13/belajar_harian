<?php

namespace Tests\Unit;

use App\Http\Livewire\MateriVideo\HalamanMateriVideo;
use App\Models\MateriDetail;
use PHPUnit\Framework\TestCase;

class MateriVideoTest extends TestCase
{
    public function test_youtube_links_are_converted_to_embed_urls(): void
    {
        $component = new HalamanMateriVideo;
        foreach ([
            'https://www.youtube.com/watch?v=M7lc1UVf-VE&feature=shared',
            'https://youtu.be/M7lc1UVf-VE?si=example',
            'https://www.youtube.com/embed/M7lc1UVf-VE',
            'https://youtube.com/shorts/M7lc1UVf-VE',
            'https://youtube.com/live/M7lc1UVf-VE',
            'https://www.youtube-nocookie.com/embed/M7lc1UVf-VE',
            'youtube.com/watch?v=M7lc1UVf-VE',
            'M7lc1UVf-VE',
        ] as $url) {
            $component->selectedVideo = new MateriDetail(['video_url' => $url]);
            $this->assertSame('https://www.youtube.com/embed/M7lc1UVf-VE', $component->getVideoEmbedUrlProperty(), $url);
        }
    }

    public function test_missing_or_invalid_video_does_not_create_a_player(): void
    {
        $component = new HalamanMateriVideo;
        $this->assertNull($component->getVideoEmbedUrlProperty());
        foreach (['', '   ', 'https://youtube.com/watch?v=invalid', 'javascript:alert(1)', 'https://youtube.com/watch?v[]=M7lc1UVf-VE'] as $url) {
            $component->selectedVideo = new MateriDetail(['video_url' => $url]);
            $this->assertNull($component->getVideoEmbedUrlProperty());
        }
    }

    public function test_other_embed_providers_are_preserved(): void
    {
        $component = new HalamanMateriVideo;
        $component->selectedVideo = new MateriDetail(['video_url' => 'https://player.vimeo.com/video/12345']);
        $this->assertSame('https://player.vimeo.com/video/12345', $component->getVideoEmbedUrlProperty());
    }
}
