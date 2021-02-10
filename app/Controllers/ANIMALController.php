<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ANIMALController extends ResourceController{
    
    protected $modelName = 'App\Models\AnimalModelo';
    protected $format = 'json';

    public function consultarTodos(){
        return $this->respond($this->model->findAll());
    }

    public  function agregarAnimal(){

        //recibir datos
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $edad = $this->request->getPost('edad');
        $descripcion = $this->request->getPost('descripcion');
        $comida = $this->request->getPost('comida');

        //armar arreglo asociativo donde las claves sean los nombres de las columnas
        $datosEnviar = array(
            'id'=>$id,
            'nombre'=>$nombre,
            'edad'=>$edad,
            'descripcion'=>$descripcion,
            'comida'=>$comida
        );

        //validaciones
        if($this->validate('animales')){
            $this->model->insert($datosEnviar);
            $mensaje = array('estado'=>true,'mensaje'=>'Animal agregado con exito');
            return $this->respond($mensaje);
        }
        else{
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors(),400);
        }
    }

    public  function editar($id){

        //recibir datos
        $animalEditar = $this->request->getRawInput();

        //armar arreglo asociativo donde las claves sean los nombres de las columnas
        $datosEnviar = array(
            'nombre'=>$animalEditar['nombre'],
            'edad'=>$animalEditar['edad'],
            'descripcion'=>$animalEditar['descripcion'],
            'comida'=>$animalEditar['comida']
        );

        //validaciones
        if($this->validate('animal')){
            $this->model->update($id,$datosEnviar);
            $mensaje = array('estado'=>true,'mensaje'=>'Animal actualizado con exito');
            return $this->respond($mensaje);
        }
        else{
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors(),400);
        }
    }

    public function eliminar($id){

        $consulta = $this->model->where('id',$id)->delete();
        if($consulta->connID->affected_rows==1){
            $mensaje = array('estado'=>true,'mensaje'=>'animal eliminado con exito');
            return $this->respond($mensaje);
        }
        else{
            $mensaje = array('estado'=>false,'mensaje'=>'animal no encontrado');
            return $this->respond($mensaje,400);
        }
    }
}
