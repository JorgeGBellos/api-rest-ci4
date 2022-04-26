<?php

namespace App\Controllers;

use App\Models\profesor;
use CodeIgniter\HTTP\Response;

class Profesores extends BaseController
{
    protected $response;

    public function getProfesor($id = null)
    {
        $this->validateSession();
        $profesor = array();
        if (is_numeric($id)) {
            foreach ($this->session->get('profesoresArray') as $key => $val) {
                if ($this->session->get('profesoresArray')[$key]->id == intval($id)) {
                    $profesor[] = $this->session->get('profesoresArray')[$key];
                }
            }
            if (empty($profesor)) {
                $this->response->setStatusCode(404, 'no hay');
                return $this->response->setJSON($profesor);
            } else {
                $this->response->setStatusCode(200, 'se encontró');
                return $this->response->setJSON($profesor);
            }
        }
    }

    public function getAll()
    {
        $this->validateSession();
        $this->response->setStatusCode(200, 'Todos los profesores');
        return $this->response->setJSON($this->session->get('profesoresArray'));
    }

    public function createProfesor()
    {
        $this->validateSession();
        $id        = $this->generateId();
        $nombres   = $this->request->getPost('nombres');
        $apellidos = $this->request->getPost('apellidos');
        $numeroEmpleado = $this->request->getPost('numeroEmpleado');
        $horasClase  = $this->request->getPost('horasClase');
        if (is_numeric($numeroEmpleado) && is_numeric($horasClase)) {
            $profesor = new profesor(
                $id,
                $nombres,
                $apellidos,
                $numeroEmpleado,
                $horasClase
            );
            $newArray   = $this->session->get('profesoresArray');
            $newArray[] = $profesor;
            $this->session->set('profesoresArray', $newArray);
            $this->response->setStatusCode(201, 'Profesor creado');
            return $this->response->setJSON($profesor);
        } else {
            return $this->response->setStatusCode(200, 'Datos erroneos');
        }

    }

    public function updateProfesor($id = null)
    {
        $this->validateSession();
        $profesor = array();
        if (is_numeric($id)) {
            foreach ($this->session->get('profesoresArray') as $key => $val) {
                if ($this->session->get('profesoresArray')[$key]->id == intval($id)) {
                    if (!empty($this->request->getVar('nombres'))) {
                        $this->session->get('profesoresArray')[$key]->nombres = $this->request->getVar('nombres');
                    }
                    if (!empty($this->request->getVar('apellidos'))) {
                        $this->session->get('profesoresArray')[$key]->apellidos = $this->request->getVar('apellidos');
                    }
                    if (!empty($this->request->getVar('numeroEmpleado'))) {
                        $this->session->get('profesoresArray')[$key]->numeroEmpleado = $this->request->getVar('numeroEmpleado');
                    }
                    if (!empty($this->request->getVar('horasClase'))) {
                        $this->session->get('profesoresArray')[$key]->horasClase = $this->request->getVar('horasClase');
                    }
                    $profesor[] = $this->session->get('profesoresArray')[$key];
                }
            }
            if (empty($profesor)) {
                $this->response->setStatusCode(404, 'no hay');
                return $this->response->setJSON($profesor);
            } else {
                $this->response->setStatusCode(200, 'se actualizó');
                return $this->response->setJSON($profesor);
            }
        }
    }

    public function deleteProfesor($id = null)
    {
        $this->validateSession();
        $finalArray = array();
        if (is_numeric($id)) {
            foreach ($this->session->get('profesoresArray') as $key => $val) {
                if ($this->session->get('profesoresArray')[$key]->id != intval($id)) {
                    $finalArray[] = $this->session->get('profesoresArray')[$key];
                }
            }
            $this->session->set('profesoresArray',$finalArray);
            $this->response->setStatusCode(200, 'se actualizó');
            return $this->response->setJSON($this->session->get('profesoresArray'));
        }
    }

    private function generateId()
    {
        $num     = rand(0, 1000);
        $idArray = array();
        foreach ($this->session->get('profesoresArray') as $key => $val) {
            $idArray[] = $this->session->get('profesoresArray')[$key]->id;
        }
        while (in_array($num, $idArray)) {
            $num = rand(0, 1000);
        }
        return $num;
    }
    
    private function validateSession()
    {
        if (!isset($this->session->profesoresArray)) {
            // session has not started so start it
            $this->session->start();
            $this->session->set('profesoresArray', array());
        }
    }
}
