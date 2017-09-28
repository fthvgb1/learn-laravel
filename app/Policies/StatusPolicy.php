<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;


    public function destroy(User $user, Status $status)
    {
        return $user->getAttribute('id') === $status->getAttribute('user_id');
    }
}
