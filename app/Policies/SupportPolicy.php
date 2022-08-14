<?php

namespace App\Policies;

use App\Models\Support;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupportPolicy
{
    use HandlesAuthorization;

    public function isAdmin(User $user, string $action)
    {
        return $this->denyWithStatus(
            401,
            "Você não tem permissão para {$action}"
        );
    }

    public function isRequestUser(User $user, int $resourceUserId, string $action)
    {
        if ($user->id !== $resourceUserId) {
            return $this->denyWithStatus(
                401,
                "Você não tem permissão para {$action}"
            );
        }

        return true;
    }
}
