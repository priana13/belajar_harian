<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class DaftarKelasKhususController extends Controller
{
    public function daftar(Group $group)
    {
        // $list_materi = 
     
        return view('daftar-kelas-khusus', compact('group'));
    }

    public function postDaftar(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $group = Group::findOrFail($request->group_id);

     
        $user = auth()->user();

        // Cek apakah user sudah terdaftar di grup ini
        if ($group->users()->where('user_id', $user->id)->exists()) {

            
            return redirect()->route('home')->with('success', 'Berhasil mendaftar ke kelas khusus!');
        }

        // Daftarkan user ke grup
        $group->users()->attach($user->id);

        // redirect ke halaman homepage
        return redirect()->route('home')->with('success', 'Berhasil mendaftar ke kelas khusus!');

        // return redirect()->route('daftar-kelas-khusus', ['group' => $group->kode_group])->with('success', 'Berhasil mendaftar ke kelas khusus!');
    }
}
