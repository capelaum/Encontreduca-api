<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DefaultPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $action
     * @return Response
     */
    public function isAdmin(User $user, string $action): Response
    {
        return $this->denyWithStatus(
            401,
            "Você não tem permissão para {$action}"
        );
    }

    /**
     * @param User $user
     * @param int $ownerId
     * @param string $action
     * @return bool|Response
     */
    public function isRequestUser(User $user, int $ownerId, string $action): Response|bool
    {
        if ($user->id !== $ownerId) {
            return $this->denyWithStatus(
                401,
                "Você não tem permissão para {$action}"
            );
        }

        return true;
    }
}
