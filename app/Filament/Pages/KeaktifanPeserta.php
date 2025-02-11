<?php

namespace App\Filament\Pages;

use App\Exports\ExportPesertaTidakAktif;
use App\Models\Angkatan;
use App\Models\AngkatanUser;
use App\Models\Ujian;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\Concerns\HasRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;

class KeaktifanPeserta extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.keaktifan-peserta';

    protected static ?int $navigationSort = 5;

    public $start;

    public $end;

    public $paginate = 10;

    public $qty_ujian = 0;

    public $angkatan_selected = 'all';

    public $users;

    public function mount(){

        if(auth()->user()->jenis_user->nama_jenis != 'Admin'){

            abort(403);
        }

        $this->start = date('Y-m-d');
        $this->end = date('Y-m-d');
    }

    // public function render(): View
    // {
    //     return view('filament.pages.laporan-kegiatan', $this->getViewData())
    //         ->layout(static::$layout, $this->getLayoutData());
    // }


    protected function getViewData(): array
    { 
        
        $tanggal_akhir = date('Y-m-d', strtotime('+1 day' , strtotime($this->end)));

        // user yang sudah mengikuti ujian
        $user_sudah_ujian = Ujian::where('angkatan_id', $this->angkatan_selected)->whereDate('created_at', ">=" ,  $this->start)->whereDate('created_at', '<' , $tanggal_akhir)->pluck('user_id')->toArray();


        // user yang belum mengikuti ujian  
        $angkatan = Angkatan::find($this->angkatan_selected);    
        
        if($this->angkatan_selected != 'all'){

            $angkatan = Angkatan::find($this->angkatan_selected);

            $users = $angkatan->peserta()->whereNotIn('users.id', $user_sudah_ujian)->get(); 

            // dd($users);
            
            
            if($this->qty_ujian){

                $data_users = [];

                foreach ($users as $key => $user) {


                    if($user->ujian->count() <= $this->qty_ujian){

                        

                        $data_users[] = $user;

                    }


                }


                $users = collect($data_users);

            }          
            

        }else{
          

            $users = User::get();           
         

            $data_users = [];

            foreach ($users as $key => $user) {              


                if($user->ujian->count() <= $this->qty_ujian){

                    

                    $data_users[] = $user;

                }


            }


            $users = collect($data_users);

            
        }  

        $this->users = $users;
             

        return [           
            "list_angkatan" => Angkatan::get()

        ];
    }


    public function export(){

        $params = ["users" => $this->users];

        if(count($this->users) > 0){

            return Excel::download(new ExportPesertaTidakAktif($params) , 'data_peserta_belum_ujian -'.$this->start.'.xlsx');

        }

    }

}
