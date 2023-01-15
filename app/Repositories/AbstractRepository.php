<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository {
    private $model;

    public function __construct(Model $model){
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos){
        $this->model = $this->model->with($atributos);

    }

    public function filtro($filtros){
         //pegando os filtros
         $filtros = explode(';', $filtros);

         foreach($filtros as $key => $condicao) {
         //pegando as condicoes dentro dos filtros
         $c = explode(':', $condicao);
         //passando as condicoes para o modelo
         $this->model =  $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function selectAtributos($atributos){
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado(){
        return $this->model->get();
    }
    public function getResultadoPaginado($totalPagina){
        return $this->model->paginate($totalPagina);
    }
}
