<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Modelo;
use Illuminate\Http\Request;
use App\Repositories\ModeloRepository;

class ModeloController extends Controller
{

    private $modelo;
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    public function index(Request $request)
    {
        $modeloRepository = new ModeloRepository($this->modelo);

        if($request->has('atributos_marca')) {
            //pegando os atributos dinamicos da marca
            $atributos_marca = 'marca:id,'.$request->atributos_marca;
            $modeloRepository->selectAtributosRegistrosRelacionados( $atributos_marca);

        } else {
            //se nao tiver atributos dinamicos da marca, passa todos os atributos da marca
            $modeloRepository->selectAtributosRegistrosRelacionados('marca');
        }
        //--------------
        if($request->has('filtro')) {
            $modeloRepository->filtro($request->filtro);
        }
        //--------------
        if($request->has('atributos')) {
            $atributos = $request->atributos;

            $modeloRepository->selectAtributos($atributos);
        }
        //---------------------------------------------------------------

        return response()->json($modeloRepository->getResultado(), 200);
    }

    //metodo store
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');

       $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
       ]);
       return response()->json($modelo, 201);
    }


    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === null){
            return response()->json(["msg" => "Recurso não existe"], 404);
        }
        else {
            return response()->json($modelo, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($modelo->rules() as $input => $regra) {

                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $modelo->feedback());

        } else {
            $request->validate($modelo->rules(), $modelo->feedback());
        }

        if($request->file('imagem') !== null) { //se o usuário enviou uma nova imagem
            Storage::disk('public')->delete($modelo->imagem); //exclui a imagem antiga
        }

        $imagem = $request->file('imagem'); //recebe a nova imagem
        $imagem_urn = $imagem->store('imagens/modelos', 'public'); //armazena a nova imagem

        $modelo->fill($request->all()); //o método fill preenche o objeto com os dados que faltam vindo do request
        $modelo->imagem = $imagem_urn; //atualiza a imagem


        $modelo->save(); //salva o objeto no banco de dados

        return response()->json($modelo, 200); //retorna o registro atualizado
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $modelo = $this->modelo->find($id,);
        if($modelo === null){
            return response()->json(['nsg' => 'Impossivel realizar a exclusão. Recurso não existe'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return response()->json(['msg' => 'Exclusão feita com sucesso!'], 200);
    }
}
