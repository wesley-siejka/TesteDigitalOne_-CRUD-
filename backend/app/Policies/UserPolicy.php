<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Se for admin libera automaticamente todas as ações.
     */
    public function before(User $authUser)
    {
        if ($authUser->nivel === 'admin') {
            return true;
        }
    }

    /**
     * Permite visualizar apenas o próprio usuário.
     */
    public function view(User $authUser, User $targetUser)
    {
        return $authUser->id === $targetUser->id;
    }

    /**
     * Permite editar apenas o próprio usuário.
     */
    public function update(User $authUser, User $targetUser)
    {
        return $authUser->id === $targetUser->id;
    }

    /**
     * Apenas administrador pode criar usuários.
     */
    public function create(User $authUser)
    {
        return $authUser->nivel === 'admin';
    }

    /**
     * Apenas administrador pode deletar usuários.
     */
    public function delete(User $authUser, User $targetUser)
    {
        return false; // admin já é liberado no método before()
    }

    /**
     * Apenas admin pode alterar permissões
     */
    public function changePermission(User $authUser, User $targetUser)
    {
        return $authUser->nivel === 'admin' && $authUser->id !== $targetUser->id;
    }

    /**
     * Listagem de usuários.
     * Somente admin (admin passa no before).
     */
    public function viewAny(User $authUser)
    {
        return false;
    }

    /**
     * Reset de senha (admin apenas).
     */
    public function resetPassword(User $authUser, User $targetUser)
    {
        return false; // admin passa no before()
    }
}
