<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\JobKirimEmail;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function pesan(Request $request){


        return view('email.pages.kirim-email');

    }

    public function kirim(Request $request){

        // Validasi input
        // $validated = $request->validate([
        //     'recipient_type' => 'required|in:all,specific',
        //     'recipients' => 'required_if:recipient_type,specific|array',
        //     'recipients.*' => 'exists:users,id',
        //     'subject' => 'required|string|max:255',
        //     'message' => 'required|string',
        //     'attachment' => 'nullable|file|max:10240', // 10MB
        // ]);

        // Handle attachment jika ada
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('email-attachments', 'public');
        }

        // Tentukan recipients berdasarkan pilihan
        if ($request->recipient_type === 'all') {
            $recipients = User::pluck('id')->toArray();
        } else {
            $recipients = $request->recipients;
        }

        // Counter untuk tracking
        $emailCount = 0;

        // Loop kirim email ke setiap user
        foreach ($recipients as $user_id) {
      
            $user = User::find($user_id); 
                 
            if ($user && $user->email) {
                // Dispatch job untuk kirim email
                \App\Jobs\JobKirimEmail::dispatch(
                    $user,
                    $request->subject,
                    $request->message,
                    $attachmentPath
                );
                
                $emailCount++;
            }
        }

    // Redirect dengan success message
    return redirect()->back()->with('success', "Email berhasil dijadwalkan untuk dikirim ke {$emailCount} penerima.");

    }
}
