<?php
namespace App\Contracts;

interface Refreshable
{
    /**
     * Este metodo debe encargarse de refrescar las propiedades del objecto
     * conn forme a los valores que están en la base de datos;
     */
    public function refresh(): void;
}