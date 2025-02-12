<?php

namespace App\Observers;

use App\Models\Belajar;

class JadwalBelajarObserver
{
    /**
     * Handle the Belajar "created" event.
     *
     * @param  \App\Models\Belajar  $belajar
     * @return void
     */
    public function created(Belajar $belajar)
    {
       
        $belajar->code = uniqid();
        $belajar->save();
    }

    /**
     * Handle the Belajar "updated" event.
     *
     * @param  \App\Models\Belajar  $belajar
     * @return void
     */
    public function updated(Belajar $belajar)
    {
        //
    }

    /**
     * Handle the Belajar "deleted" event.
     *
     * @param  \App\Models\Belajar  $belajar
     * @return void
     */
    public function deleted(Belajar $belajar)
    {
        //
    }

    /**
     * Handle the Belajar "restored" event.
     *
     * @param  \App\Models\Belajar  $belajar
     * @return void
     */
    public function restored(Belajar $belajar)
    {
        //
    }

    /**
     * Handle the Belajar "force deleted" event.
     *
     * @param  \App\Models\Belajar  $belajar
     * @return void
     */
    public function forceDeleted(Belajar $belajar)
    {
        //
    }
}
