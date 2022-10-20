<?php
namespace App\Controllers\Api;

use App\Models\User;

class UsersController
{
    /**
     * Obtiene los usuarios.
     */
    public function getUsers()
    {
        $u = new User;
        $users = $u
            ->select('-id', 'consultor_id', 'consultor_nombre')
            ->where('consultor_estado', "'A'")
            ->where('area_servicio_nombre', "'SISTEMAS'", '=', 'AND')
            ->orderBy('consultor_nombre')
            ->get()
            ->fetch_all(MYSQLI_ASSOC);
    
        echo json_encode([
            'users' => $users,
        ]);
    }
}