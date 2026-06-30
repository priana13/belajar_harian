<?php

namespace App\Policies;

use App\Models\Struktur;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StrukturPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Struktur $struktur): bool
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Struktur $struktur): bool
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Struktur $struktur): bool
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Struktur $struktur): bool
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Struktur $struktur): bool
    {
        return $user->jenis_user->nama_jenis == 'Admin';
    }
}
