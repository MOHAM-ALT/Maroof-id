<?php

namespace App\Policies;

use App\Models\BrandKit;
use App\Models\User;

class BrandKitPolicy
{
    public function update(User $user, BrandKit $brandKit): bool
    {
        return $user->id === $brandKit->user_id;
    }

    public function delete(User $user, BrandKit $brandKit): bool
    {
        return $user->id === $brandKit->user_id;
    }
}
