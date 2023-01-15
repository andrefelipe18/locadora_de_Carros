<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Marca;
use Illuminate\Http\Request;
use App\Repositories\MarcaRepository;

class MarcaController extends Controller
{
    private $marca;
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')) {
            //pegando os atributos dinamicos da marca
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados( $atributos_modelos);

        } else {
            //se nao tiver atributos dinamicos da marca, passa todos os atributos da marca
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }
        //--------------
        if($request->has('filtro')) {
            $marcaRepository->filtro($request->filtro);
        }
        //--------------
        if($request->has('atributos')) {
            $atributos = $request->atributos;

            $marcaRepository->selectAtributos($atributos);
        }
        //---------------------------------------------------------------

        return response()->json($marcaRepository->getResultadoPaginado(4), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

       $marca = $this->marca->create([
           'nome' => $request->nome,
           'imagem' => $imagem_urn
       ]);
       return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Interger
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null){
            return response()->json(["msg" => "Recurso não existe"], 404);
        }
        else {
            return response()->json($marca, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($marca->rules() as $input => $regra) {

                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());

        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        if($request->file('imagem') !== null) { //se o usuário enviou uma nova imagem
            Storage::disk('public')->delete($marca->imagem); //exclui a imagem antiga
        }

        $imagem = $request->file('imagem'); //recebe a nova imagem
        $imagem_urn = $imagem->store('imagens', 'public'); //armazena a nova imagem

        //preencher o objeto $marca com os dados do request
        $marca->fill($request->all()); //o método fill preenche o objeto com os dados que faltam vindo do request
        $marca->imagem = $imagem_urn; //atualiza a imagem


        $marca->save(); //salva o objeto no banco de dados

        return response()->json($marca, 200); //retorna o registro atualizado
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $marca = $this->marca->find($id,);
        if($marca === null){
            return response()->json(['nsg' => 'Impossivel realizar a exclusão. Recurso não existe'], 404);
        }

        //verifica se o usuário enviou uma nova imagem
        if($request->file('imagem') !== null) { //se o usuário enviou uma nova imagem
            Storage::disk('public')->delete($marca->imagem); //exclui a imagem antiga
        }

        $marca->delete();
        return response()->json(['msg' => 'Exclusão feita com sucesso!'], 200);

    }
}
