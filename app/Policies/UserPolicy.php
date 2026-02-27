<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Admin pode fazer tudo automaticamente
     */
    public function before(User $authUser)
    {
        if ($authUser->nivel === 'admin') {
            return true;
        }
    }

    /**
     * Usuário simples pode visualizar apenas o próprio perfil
     */
    public function view(User $authUser, User $targetUser)
    {
        return $authUser->id === $targetUser->id;
    }

    /**
     * Usuário simples pode editar apenas o próprio perfil
     */
    public function update(User $authUser, User $targetUser)
    {
        return $authUser->id === $targetUser->id;
    }

    /**
     * Apenas admin pode criar usuários
     */
    public function create(User $authUser)
    {
        return $authUser->nivel === 'admin';
    }

    /**
     * Apenas admin pode deletar
     */
    public function delete(User $authUser, User $targetUser)
    {
        return false; // admin já passa no before()
    }

    /**
     * Ninguém pode alterar a própria permissão
     */
    public function changePermission(User $authUser, User $targetUser)
    {
        return $authUser->nivel === 'admin' && $authUser->id !== $targetUser->id;
    }
}
