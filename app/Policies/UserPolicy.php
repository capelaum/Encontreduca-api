<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use  \Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $action
     * @return Response|bool
     */
    public function isAdmin(User $user, string $action): Response|bool
    {
        if (!$user->tokenCan('admin')) {
            return $this->denyWithStatus(
                401,
                "Você não tem permissão para {$action}"
            );
        }

        return true;
    }

    /**
     * @param User $user
     * @param User $model
     * @param string $action
     * @return Response|bool
     */
    public function isOwner(User $user, User $model, string $action): Response|bool
    {
        if ($user->id !== $model->id) {
            return $this->denyWithStatus(
                401,
                "Você não tem permissão para {$action}"
            );
        }

        return true;
    }

    /**
     * @param User $user
     * @param int $ownerId
     * @param string $action
     * @return Response|bool
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
