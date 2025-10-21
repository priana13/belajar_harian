<?php

namespace App\Http\Livewire\Kuis;
use App\Http\Controllers\API\UjianController;
use App\Models\JadwalUjian;
use App\Models\JenisUjian;
use App\Models\Kelas;

use App\Models\Ujian;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Soal;
use App\Models\SoalUjian;
use Illuminate\Support\Facades\Auth;

class HalamanKuis extends Component
{

    public $materi_id;
    public $jadwal_id;
    public $ujian_id;
    public $jawaban;

    public $angkatan;

    public $kelas;

    public $ujian;

    protected $listeners = ['evaluasi' => 'evaluasi','expired' => 'expired'];

    public function mount($materi_id,$jadwal_id)
    {

        $this->materi_id = $materi_id;
        $this->jadwal_id = $jadwal_id;

        $cek_ujian = Ujian::where('user_id', auth()->user()->id)->where('jadwal_ujian_id', $jadwal_id)->first(); 

        if($cek_ujian && request()->trial){
            $cek_ujian->delete();
            $cek_ujian = null;
        }
        
        // dd($cek_ujian);
        
        $this->kelas = Kelas::find(auth()->user()->angkatan_user->last()->kelas_id);     

        if(!$cek_ujian){

            $ujian = $this->ikut_ujian(); 

            $this->ujian = Ujian::find($ujian->id);

            $this->ujian = $ujian;

            $this->ujian_id = $ujian->id;

        }else{ 

            $this->ujian = $cek_ujian;

            $this->ujian = $cek_ujian;  
            $this->ujian_id = $cek_ujian->id;                      


            if( $cek_ujian->status == "Selesai" ){                 
             
                redirect()->route('hasil_evaluasi',[$materi_id , $cek_ujian->id]);

            }else{

                // jika ujian masih aktif, isi jawaban user sebelumnya

                $jawaban_soal = $this->ujian->soal_ujian;

                if(count($jawaban_soal) > 0){
    
                    foreach ($jawaban_soal as $row) {                   
    
                        $data_jawaban[$row->soal->nomor] = $row->jawaban;                    
    
                    }
                }                  
                        
                for ($i=1; $i < 20; $i++) { 
    
                    $this->jawaban[$i] = (isset( $data_jawaban[$i] ) ) ? $data_jawaban[$i] : null ;
                }



            }

        } 



    }
    public function render()
    {       

        $data['list_soal'] = $this->getListSoal();
      

        $data['jawaban_user'] = $this->jawaban_user($this->ujian_id);        


        // $data['ujian'] = $ujian->lihat_nilai_ujian($this->materi_id);  

        // dd( $data['ujian'] );

        if( $this->ujian ){

            $created = Carbon::parse($this->ujian->created_at);           

            $data['mulai'] =  $created->format('H:i');
   
            // tambah 25 menit
            $data['sampai'] = $created->addMinutes(25)->format('H:i');
   
        }            
        

        return view('livewire.kuis.halaman-kuis',$data)->extends('layouts.app')->section('content');
    }

    public function simpan_jawaban($soal_id,$jawaban){
        // dd($soal_id);
        // $ujian = new UjianController(); 
        // $ujian->insert_jawaban($soal_id,$this->ujian_id, $jawaban);


        if ($jawaban == Soal::find($soal_id)->kunci) {
            $istrue = 1;
        } else $istrue = 0;

        SoalUjian::updateOrCreate(
        ['user_id' =>  Auth::id(), 'soal_id' => $soal_id, 'ujian_id' =>$this->ujian->id],
        [
            'user_id' => Auth::id(),
            'soal_id' => $soal_id,
            'ujian_id' => $this->ujian->id,
            'jawaban' => $jawaban,
            'istrue' => $istrue
        ]);


    }


    public function jawaban_user($ujian_id){
        $jawaban_user = SoalUjian::where('ujian_id', $ujian_id)
        ->where('user_id', Auth::id())
        ->get();
        
        return $jawaban_user;
    }


    public function evaluasi(){

        $this->validate([
            'jawaban.1' => 'required',
            'jawaban.2' => 'required',
            'jawaban.3' => 'required'
        ],[
            'jawaban.1.required' => "Anda belum menentukan jawaban",
            'jawaban.2.required' => "Anda belum menentukan jawaban",
            'jawaban.3.required' => "Anda belum menentukan jawaban"
        ]);       

        $ujian = new UjianController(); 

        $ujian->update_nilai_ujian($this->ujian->id);

        // matikan dulu
        return redirect()->route('hasil_evaluasi', ['materi_id' => $this->materi_id,'ujian_id' => $this->ujian->id]);

    }

    public function expired(){
        // dd($soal_id);
        $ujian = new UjianController(); 

        $ujian->update_nilai_ujian($this->ujian_id);

        // matikan dulu

    }


    public function ikut_ujian()
    {
        // dd($materi_id);

        $jadwal_ujian = JadwalUjian::find($this->jadwal_id);   
        
        $jenis_ujian_ids = [
            "Harian" => 1,
            "Pekanan" => 2,
            "Akhir" => 3
        ];        
    
        $ujian = Ujian::create([
            'nama_ujian' => 'Ujian Materi',
            'jenis_ujian_id' => $jenis_ujian_ids[$jadwal_ujian->type],
            'angkatan_id' => $jadwal_ujian->angkatan_id,
            'user_id' => Auth::id(),
            'materi_id' => $this->materi_id,
            'kelas_id' => $this->kelas->id,
            'kode_ujian' => uniqid(),
            'jadwal_ujian_id' => $jadwal_ujian->id,
            'urutan' => $jadwal_ujian->urutan, // pekan ke n atau hari ke n
            'status' => 'Aktif'
        ]);

        $ujian->kode_ujian = $ujian->id . $ujian->kode_ujian;
        $ujian->save();

        // dd($ujian_id);

        return $ujian;
    }




    public function getListSoal()
    {
       
        $jadwal = JadwalUjian::find($this->jadwal_id);        

        $jenis_ujian = JenisUjian::where('nama', $jadwal->type)->first(); 

        // dd($jenis_ujian);
        
        if($jenis_ujian->id == 3){

            $soal = Soal::where('materi_id', $this->materi_id)
            ->where('jenis_ujian_id', $jenis_ujian->id)          
            ->orderBy('nomor','asc')  
            ->get(); 

        }else{

            $soal = Soal::where('materi_id', $this->materi_id)
            ->where('jenis_ujian_id', $jenis_ujian->id)
            ->where('urutan',$jadwal->urutan)
            ->orderBy('nomor','asc') 
            ->get(); 

        }

        return $soal;
    }


}
