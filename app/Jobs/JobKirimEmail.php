<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\CustomEmail;
use App\Mail\EmailPengumuman;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class JobKirimEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $subject;
    public $message;
    public $attachmentPath;

    /**
     * Jumlah kali job akan dicoba
     */
    public $tries = 3;

    /**
     * Timeout untuk job (dalam detik)
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $subject, string $message, ?string $attachmentPath = null)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->message = $message;
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user->email)
                ->send(new EmailPengumuman(
                    $this->user,
                    $this->subject,
                    $this->message,
                    $this->attachmentPath
                ));

            Log::info("Email berhasil dikirim ke: {$this->user->email}");
            
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email ke {$this->user->email}: " . $e->getMessage());
            
            // Throw exception agar job bisa di-retry
            throw $e;
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Job gagal setelah {$this->tries} percobaan untuk {$this->user->email}: " . $exception->getMessage());
        
        // Anda bisa menambahkan notifikasi ke admin di sini
    }
}