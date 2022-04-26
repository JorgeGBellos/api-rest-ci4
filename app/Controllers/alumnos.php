<?php

namespace App\Controllers;

use App\Models\alumno;
use CodeIgniter\HTTP\Response;

class Alumnos extends BaseController
{
    protected $response;

    public function getAlumno($id = null)
    {
        $this->validateSession();
        $alumno = array();
        if (is_numeric($id)) {
            foreach ($this->session->get('alumnosArray') as $key => $val) {
                if ($this->session->get('alumnosArray')[$key]->id == intval($id)) {
                    $alumno[] = $this->session->get('alumnosArray')[$key];
                }
            }
            if (empty($alumno)) {
                $this->response->setStatusCode(404, 'no hay');
                return $this->response->setJSON($alumno);
            } else {
                $this->response->setStatusCode(200, 'se encontró');
                return $this->response->setJSON($alumno);
            }
        }
    }

    public function getAll()
    {
        $this->validateSession();
        $this->response->setStatusCode(200, 'Todos los alumnos');
        return $this->response->setJSON($this->session->get('alumnosArray'));
    }

    public function createAlumno()
    {
        $this->validateSession();
        $id        = $this->generateId();
        $nombres   = $this->request->getPost('nombres');
        $apellidos = $this->request->getPost('apellidos');
        $matricula = $this->request->getPost('matricula');
        $promedio  = $this->request->getPost('promedio');
        
        if (preg_grep("/(a)[0-9]{8}$/", array($matricula))
            && in_array(floatVal($promedio), range(0, 10))
            && is_numeric($promedio)) {
            $alumno = new alumno(
                $id,
                $nombres,
                $apellidos,
                $matricula,
                $promedio
            );
            $newArray   = $this->session->get('alumnosArray');
            $newArray[] = $alumno;
            $this->session->set('alumnosArray', $newArray);
            $this->response->setStatusCode(201, 'Alumno creado');
            return $this->response->setJSON($alumno);
        } else {
            return $this->response->setStatusCode(200, 'Datos erroneos');
        }
    }

    public function updateAlumno($id = null)
    {
        $this->validateSession();
        $alumno = array();
        if (is_numeric($id)) {
            foreach ($this->session->get('alumnosArray') as $key => $val) {
                if ($this->session->get('alumnosArray')[$key]->id == intval($id)) {
                    if (!empty($this->request->getVar('nombres'))) {
                        $this->session->get('alumnosArray')[$key]->nombres = $this->request->getVar('nombres');
                    }
                    if (!empty($this->request->getVar('apellidos'))) {
                        $this->session->get('alumnosArray')[$key]->apellidos = $this->request->getVar('apellidos');
                    }
                    if (!empty($this->request->getVar('matricula'))) {
                        if (preg_grep("/(a)[0-9]{8}$/", array($this->request->getVar('matricula')))) {
                            $this->session->get('alumnosArray')[$key]->matricula = $this->request->getVar('matricula');
                        }
                    }
                    if (!empty($this->request->getVar('promedio'))) {
                        $this->session->get('alumnosArray')[$key]->promedio = $this->request->getVar('promedio');
                    }
                    $alumno[] = $this->session->get('alumnosArray')[$key];
                }
            }
            if (empty($alumno)) {
                $this->response->setStatusCode(404, 'no hay');
                return $this->response->setJSON($alumno);
            } else {
                $this->response->setStatusCode(200, 'se actualizó');
                return $this->response->setJSON($alumno);
            }
        }
    }

    public function deleteAlumno($id = null)
    {
        $this->validateSession();
        $finalArray = array();
        if (is_numeric($id)) {
            foreach ($this->session->get('alumnosArray') as $key => $val) {
                if ($this->session->get('alumnosArray')[$key]->id != intval($id)) {
                    $finalArray[] = $this->session->get('alumnosArray')[$key];
                }
            }
            $this->session->set('alumnosArray',$finalArray);
            $this->response->setStatusCode(200, 'se actualizó');
            return $this->response->setJSON($this->session->get('alumnosArray'));
        }
    }

    private function generateId()
    {
        $num     = rand(0, 1000);
        $idArray = array();
        foreach ($this->session->get('alumnosArray') as $key => $val) {
            $idArray[] = $this->session->get('alumnosArray')[$key]->id;
        }
        while (in_array($num, $idArray)) {
            $num = rand(0, 1000);
        }
        return $num;
    }

    private function validateSession()
    {
        if (!isset($this->session->alumnosArray)) {
            // session has not started so start it
            $this->session->start();
            $this->session->set('alumnosArray', array());
            $this->session->set('profesoresArray', array());
        }
    }
}
