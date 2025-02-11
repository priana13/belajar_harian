<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use App\Models\AngkatanUser;
use App\Models\Belajar;
use App\Models\User;
use DateTime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{

    public function create(Angkatan $angkatan){    

        $kode_daftar = $angkatan->kode_daftar;      

        return view('daftar' , compact('kode_daftar' , 'angkatan'));

    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'. User::class],
            'no_hp' => ['required', 'max:255','unique:'. User::class],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', Rules\Password::defaults()],
            'temp_lahir' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required']
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'temp_lahir' => $request->temp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'kota' => $request->kota,
            'no_hp' => $request->no_hp,
            'pekerjaan' => $request->pekerjaan,
            'status' => $request->status,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password' => Hash::make($request->password),
        ]);

        $nip = $user->id . date('Y');
        $user->nip = $nip;
        $user->kode_user = uniqid();
        $user->save();


        if($request->kode_angkatan){

            $this->daftarAngkatan($user , $request->kode_angkatan);
            
        }


        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home');
    }

    public function daftarAngkatan($user , $kode_angkatan){

        /**
         * 1. tambahkan ke angkatan
         * 2. buat jadwal belajar
         */  
                $angkatan = Angkatan::where('kode_daftar' , $kode_angkatan)->first();

                $kelas = $angkatan->kelas->first();

                // tambahkan user ke angkatan user
                AngkatanUser::create([
                    'angkatan_id' => $angkatan->id,
                    'user_id' => $user->id,
                    'status' => "Aktif",
                    "kelas_id" => $kelas->id
                ]);

                            
                // dapatkan jumlah hari
                $tanggal_mulai = $angkatan->tanggal_mulai;
                $tanggal_akhir = $angkatan->tanggal_akhir;
            
                $tgl1 = new DateTime($tanggal_mulai);
                $tgl2 = new DateTime($tanggal_akhir);
                $selisih = $tgl2->diff($tgl1);
                $jumlah_hari = $selisih->d;       

                $tanggal = $tanggal_mulai;

                // dd($tanggal);

                $materi_pertemuan = $angkatan->materi->materi_detail()->orderBy('pertemuan')->get();     

                $hari_ke = 1;

                foreach ($materi_pertemuan as $materi_detail) {

                    //batasi sampai 20 pertemuan saja
                    if($hari_ke == 21){
                        break;
                    }


                    Belajar::create([
                        "tanggal" => $tanggal,
                        "materi_detail_id" => $materi_detail->id,
                        "user_id" => $user->id,
                        "angkatan_id" => $angkatan->id         
                    ]);


                    // cek apakah termasuk hari ujian atau bukan, jika tanggal ujian lewati 2 hari
                    if(in_array($hari_ke , [5,10,15])){

                        $penambah_hari = 3; // ditambah 2 hari

                    }else{

                    $penambah_hari = 1;

                    }


                    $tanggal = date('Y-m-d', strtotime('+'. $penambah_hari .' day' , strtotime($tanggal)));
            
                    //    $tanggal = date('Y-m-d', strtotime( $tanggal .'+ 1 day' ));
                


                    $hari_ke ++;
                    
                }

       

    }
}
