<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Ujian;
use App\Models\Kelompok;
use App\Models\JenisUser;
use App\Models\JenisKelompok;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    public function canAccessFilament(): bool
    {
        return true;
        return str_ends_with($this->email, '@bisionline.com') && $this->hasVerifiedEmail();
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // protected $rules = [
    //     'email' => 'sometimes|required|email|unique:users',
    // ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jenis_user()
    {

        return $this->belongsTo(JenisUser::class);
    }

    public function ujian()
    {

        return $this->hasMany(Ujian::class);
    }

    public function jadwal_belajar()
    {

        return $this->hasMany(Belajar::class, 'user_id');
    }

    public function scopeType($query, $type)
    {

        return $query->where('jenis_user_id', $type);
    }

    public function kelompok()
    {
        // dd('masuk');
        return $this->belongsTo(Kelompok::class);
    }

    public function gelombang(){
        return $this->belongsTo(Gelombang::class, 'gelombang_id');
    }

    // public function jenis_kelompok(){

    //     return $this->belongsTo(JenisKelompok::class);
    // }

    public function sendPasswordResetNotification($token)
    {
        $url = "http://" . $_SERVER['HTTP_HOST'] . '/reset_password/' . $token . '-' . $this->id;

        $this->notify(new ResetPasswordNotification($url));
    }

    public function angkatan_user(){

        return $this->hasMany(AngkatanUser::class, 'user_id');
    }

    public function angkatan(){

        // return $this->belongsToMany()

        return $this->belongsToMany(Angkatan::class, AngkatanUser::class, 'user_id' , 'angkatan_id');
    }

    public function scopePeserta($query){

        return $query->where('jenis_user_id', 2);
    }

    public function roadmaps(): BelongsToMany
    {
        return $this->belongsToMany(Roadmap::class, 'user_roadmap', 'user_id', 'roadmap_id');
    }

    // public function 
}
