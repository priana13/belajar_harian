<?php

namespace App\Policies;

use App\Models\KategoriKegiatan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KategoriKegiatanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KategoriKegiatan  $kategoriKegiatan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, KategoriKegiatan $kategoriKegiatan)
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KategoriKegiatan  $kategoriKegiatan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, KategoriKegiatan $kategoriKegiatan)
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KategoriKegiatan  $kategoriKegiatan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, KategoriKegiatan $kategoriKegiatan)
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KategoriKegiatan  $kategoriKegiatan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, KategoriKegiatan $kategoriKegiatan)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KategoriKegiatan  $kategoriKegiatan
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, KategoriKegiatan $kategoriKegiatan)
    {
        //
    }
}
