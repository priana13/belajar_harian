<?php

namespace Tests\Feature;

use App\Http\Livewire\MateriVideo\HalamanMateriVideo;
use App\Models\MateriDetail;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class MateriVideoProgressTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'video_test', 'database.connections.video_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
        });
        Schema::create('group_users', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('group_id');
        });
        Schema::create('jadwal_roadmap', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->nullable();
        });
        Schema::create('jadwal_belajar', function (Blueprint $table) {
            $table->id();
            $table->integer('materi_detail_id');
            $table->integer('user_id')->nullable();
            $table->integer('gelombang_id')->nullable();
            $table->integer('jadwal_roadmap_id')->nullable();
            $table->date('tanggal');
            $table->string('status')->default('Sekarang');
        });
        Schema::create('materi_detail_user', function (Blueprint $table) {
            $table->id();
            $table->integer('materi_detail_id');
            $table->integer('user_id');
            $table->string('status')->nullable();
            $table->timestamps();
        });
        $this->actingAs(new User(['id' => 10, 'gelombang_id' => 20]));
    }

    protected function tearDown(): void
    {
        DB::purge('video_test');
        parent::tearDown();
    }

    private function videoComponent(): HalamanMateriVideo
    {
        $component = new HalamanMateriVideo;
        $component->materiDetail = (new MateriDetail)->newCollection(array_map(
            fn ($id) => new MateriDetail(['id' => $id, 'video_url' => 'https://youtu.be/M7lc1UVf-VE']),
            range(1, 6)
        ));
        $component->selectedVideo = $component->materiDetail->first();

        return $component;
    }

    private function schedule(int $id, array $attributes = []): void
    {
        DB::table('jadwal_belajar')->insert(array_merge([
            'materi_detail_id' => $id, 'user_id' => null, 'gelombang_id' => 20,
            'tanggal' => today()->toDateString(), 'status' => 'Sekarang',
        ], $attributes));
    }

    public function test_only_released_schedules_for_this_user_unlock_modules(): void
    {
        $this->schedule(1);
        $this->schedule(2, ['tanggal' => today()->addDay()->toDateString()]);
        $this->schedule(3, ['gelombang_id' => 99]);
        $this->schedule(4, ['user_id' => 99]);
        $this->schedule(5, ['user_id' => 10, 'gelombang_id' => null]);
        DB::table('groups')->insert(['id' => 30]);
        DB::table('group_users')->insert(['user_id' => 10, 'group_id' => 30]);
        DB::table('jadwal_roadmap')->insert(['id' => 40, 'group_id' => 30]);
        $this->schedule(6, ['gelombang_id' => null, 'jadwal_roadmap_id' => 40]);
        $component = $this->videoComponent();
        $this->assertEqualsCanonicalizing([1, 5, 6], $component->render()->getData()['availableIds']);
        $component->selectVideo(2);
        $this->assertSame(1, $component->selectedVideo->id);
        $component->selectVideo(5);
        $this->assertSame(5, $component->selectedVideo->id);
    }

    public function test_progress_is_persisted_once_and_scoped_to_the_user(): void
    {
        $this->schedule(1);
        $component = $this->videoComponent();
        $component->sudahPaham();
        $component->sudahPaham();
        $this->assertDatabaseCount('materi_detail_user', 1);
        $this->assertDatabaseHas('materi_detail_user', ['user_id' => 10, 'materi_detail_id' => 1, 'status' => 'sudah baca']);
        $this->assertSame([1], $this->videoComponent()->render()->getData()['completedIds']);
        $this->actingAs(new User(['id' => 11, 'gelombang_id' => 20]));
        $this->assertSame([], $this->videoComponent()->render()->getData()['completedIds']);
    }

    public function test_locked_module_cannot_be_marked_understood(): void
    {
        $component = $this->videoComponent();
        try {
            $component->sudahPaham();
            $this->fail('Locked module should be rejected.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
        $this->assertDatabaseCount('materi_detail_user', 0);
        $this->assertNull($component->render()->getData()['videoEmbedUrl']);
    }
}
