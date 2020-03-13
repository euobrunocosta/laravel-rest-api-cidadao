<?php

namespace App\Http\Controllers;

use App\Cidadao;
use Illuminate\Http\Request;
use App\Http\Resources\Cidadao as CidadaoResource;
use Validator;

class CidadaoController extends Controller
{
    /**
     * Retorna uma lista com todos os didadãos registrados
     * no banco de dados ordenados pelo nome em ordem alfabética.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CidadaoResource::collection(Cidadao::orderBy('nome')->get());
    }

    /**
     * Retorna uma mensagem de erro informando que rota não
     * está disponível para esse método
     *
     * @return \Illuminate\Http\Response
     */
    public function indexErro()
    {
        return response()->json([
            'error' => 'Rota disponível apenas para o método GET'
        ], 400);
    }

    /**
     * Guarda um cidadão recém criado no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Cidadao::where('cpf', (int) $request->input('cpf'))->first()) {
            return response()->json([
                'error' => 'Cidadão com CPF já cadastrado'
            ], 403);
        }

        $cidadao = new Cidadao;

        $validator = $this->validaInformacoes($request->all());

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Erro na validação: ' . $validator->errors()
            ], 400);
        }

        $cidadao = $this->criaCidadao($request, $cidadao);

        if ($cidadao->logradouro == "erro") {
            return response()->json([
                'error' => 'CEP não encontrado'
            ], 400);
        }

        if($cidadao->save()) {
            return new CidadaoResource($cidadao);
        }
    }

    /**
     * Recebe as informações passadas pelo cadastro,
     * define regras de validação para cada uma delas
     * e retorna o resultado
     *
     * @param  object  $informacoes
     * @return Validator
     */
    private function validaInformacoes($informacoes)
    {
        return Validator::make($informacoes, [
            'nome' => 'required|string',
            'sobrenome' => 'required|string',
            'cpf' => 'required|digits:11',
            'telefone' => 'required|digits_between:8,15',
            'email' => 'required|email',
            'celular' => 'required|digits_between:8,15',
            'cep' => 'required|digits:8'
        ]);
    }

    /**
     * Faz uma requisição à API da ViaCEP para encontrar
     * o endereço referente ao cep informado.
     *
     * @param  string  $cep
     * @return mixed
     */
    private function getEndereco($cep)
    {
        return json_decode(file_get_contents("https://viacep.com.br/ws/{$cep}/json/"));
    }

    /**
     * Faz uma requisição à API da ViaCEP para encontrar
     * o endereço referente ao cep informado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Cidadao
     */
    private function criaCidadao($request, Cidadao $cidadao)
    {
        $cidadao->nome = $request->input('nome');
        $cidadao->sobrenome = $request->input('sobrenome');
        $cidadao->cpf = $request->input('cpf');
        $cidadao->telefone = $request->input('telefone');
        $cidadao->email = $request->input('email');
        $cidadao->celular = $request->input('celular');
        $cidadao->cep = $request->input('cep');

        $endereco = $this->getEndereco($request->input('cep'));

        if (isset($endereco->erro)) {
            $cidadao->logradouro = "erro";
            return $cidadao;
        } 

        $cidadao->logradouro = $endereco->logradouro;
        $cidadao->bairro = $endereco->bairro;
        $cidadao->cidade = $endereco->localidade;
        $cidadao->uf = $endereco->uf;

        return $cidadao;
    }

    /**
     * Altera as informações no banco de dados
     * do cidadão cujo cpf é igual ao informado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $cpf
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $cpf)
    {
        $cpf = (int) $cpf;

        $cidadao = Cidadao::where('cpf', $cpf)->first();

        if (!$cidadao) {
            return response()->json([
                'error' => 'Cidadão com CPF informado não consta no banco de dados'
            ], 400);
        }

        $validator = $this->validaInformacoes($request->all());

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Erro na validação: ' . $validator->errors()
            ], 400);
        }

        $cidadao = $this->criaCidadao($request, $cidadao);

        if ($cidadao->logradouro == "erro") {
            return response()->json([
                'error' => 'CEP não encontrado'
            ], 400);
        }

        if($cidadao->save()) {
            return new CidadaoResource($cidadao);
        }
    }

    /**
     * Retorna dados do cidadão cujo cpf é igual ao informado.
     *
     * @param  string  $cpf
     * @return \Illuminate\Http\Response
     */
    public function show(string $cpf)
    {
        $cpf = (int) $cpf;

        $cidadao = Cidadao::where('cpf', $cpf)->first();

        if (!$cidadao) {
            return response()->json([
                'error' => 'Cidadão com CPF informado não consta no banco de dados'
            ], 400);
        }

        return new CidadaoResource($cidadao);
    }

    /**
     * Remove uma cidadão do banco de dados quando informado o cpf do mesmo.
     *
     * @param  string  $cpf
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $cpf)
    {
        $cpf = (int) $cpf;

        $cidadao = Cidadao::where('cpf', $cpf)->first();

        if (!$cidadao) {
            return response()->json([
                'error' => 'Cidadão com CPF informado inexistente'
            ], 400);
        }

        if ($cidadao->delete()) {
            return new CidadaoResource($cidadao);
        }
    }

    /**
     * Retorna uma response de erro dizendo que o usuário não
     * informou o cpf do cidadao para ser removido
     *
     * @return \Illuminate\Http\Response
     */
    public function cpfErro()
    {
        return response()->json([
            'error' => 'CPF do cidadão não informado'
        ], 400);
    }
}