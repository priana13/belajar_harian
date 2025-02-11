<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;





class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $temp_lahir;
    public $tgl_lahir;
    public $kota;
    public $no_hp;
    public $status;
    public $password;
    public $password_confirmation; 
    public $foto_profil;


    public function mount($userId = null)
    {
        $this->name= auth()->user()->name;
        $this->email=auth()->user()->email;
        $this->temp_lahir=auth()->user()->temp_lahir;
        $this->tgl_lahir=auth()->user()->tgl_lahir;
        $this->kota=auth()->user()->kota;
        $this->no_hp=auth()->user()->no_hp;
        $this->status=auth()->user()->status;
        $this->foto_profil=auth()->user()->foto_profil;

    }

    public function render()
    {
       
        return view('livewire.user.profile')->extends('layouts.app')->section('content');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
            'no_hp' => 'required|max:255|unique:users,no_hp,' . auth()->user()->id,
            'password' => 'nullable|min:8|confirmed',
            'password_confirmation' => 'nullable', 
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function submit(){

    $validated = $this->validate();
    if($this->password){
        $password =Hash::make($this->password);
    }else{
        $password =auth()->user()->password;
    }
    if($this->foto_profil){
        $profilePicturePath = $this->foto_profil->store('foto_profil', 'public');
    }else{
        $profilePicturePath = auth()->user()->foto_profil;
    }
    $this->foto_profil = $profilePicturePath;
    $user = User::where('id',auth()->user()->id)->update([
        'name' => $this->name,
        'email' => $this->email,
        'temp_lahir' => $this->temp_lahir,
        'tgl_lahir' => $this->tgl_lahir,
        'kota' => $this->kota,
        'no_hp' => $this->no_hp,
        // 'pekerjaan' => $request->pekerjaan,
        'status' => $this->status,
        // 'gender' => $request->gender,
        'foto_profil' => $profilePicturePath,
        'password' => $password,  
    ]);
    // if($this->foto_profil){
    //     $profilePicturePath = $this->foto_profil->store('foto_profil');
    //     $user->foto_profil = $profilePicturePath;
    //     $user->save();
    // }
   
    }
}
