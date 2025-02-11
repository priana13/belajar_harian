<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPesertaTidakAktif implements FromCollection , WithHeadings
{
    public array $params;

    public function __construct($params){

        $this->params = $params;

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {  
        
        $users = $this->params['users'];

        foreach ($users as $key => $user) {              
            
            if( is_array( $user ) ){    
                         
                //ubah jadi object jk masih array
                $user = (object)$user;
            }        

            //rapihkan data nama
            // $nama = str_replace(',' ,'',$user->name);
            $explode_name = \explode(' ',$user->name);
            $nama_depan = $explode_name[0];

            $data_users[] = collect([

                'id' => $user->id,
                'nama' => $user->name,
                'no_hp' => $user->no_hp,
                'BC' => $nama_depan . ',' . $user->no_hp

            ]);

        }

        return collect($data_users);
        
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Hp',
            'BC'
        ];
    }
}
