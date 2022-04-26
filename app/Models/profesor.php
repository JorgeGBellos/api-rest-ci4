<?php

namespace App\Models;

class profesor
{
    public $id;
    public $nombres;
    public $apellidos;
    public $numeroEmpleado;
    public $horasClase;
    public function __construct($id,$nombres,$apellidos,$numeroEmpleado,$horasClase) {
        $this->id = $id;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->numeroEmpleado = $numeroEmpleado;
        $this->horasClase = $horasClase;
    }
}