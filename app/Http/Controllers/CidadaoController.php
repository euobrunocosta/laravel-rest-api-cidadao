<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Cidadao as CidadaoResource;
use App\Repositories\BaseRepository;
use App\Services\CidadaoService;

class CidadaoController extends Controller
{

    private $baseRepository;
    private $cidadaoService;

    public function __construct(BaseRepository $baseRepository, CidadaoService $cidadaoService)
    {
       $this->baseRepository = $baseRepository;
       $this->cidadaoService = $cidadaoService;
    }

    /**
     * Retorna uma lista com todos os didadãos registrados
     * no banco de dados ordenados pelo nome em ordem alfabética
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index()
    {
        $cidadaos = $this->baseRepository->cidadaosOrdenados();
        return CidadaoResource::collection($cidadaos);
    }

    /**
     * 'Joga' uma exception mencionando que essa rota não
     * está disponível para esse método
     *
     * @return void
     */
    public function indexErro()
    {
        $this->cidadaoService->rotaIndisponivel();
    }

    /**
     * Guarda um cidadão recém criado no banco de dados
     * e retorna todos os dados do mesmo incluíndo o
     * endereço conseguido pela API ViaCEP
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(Request $request)
    {

        $this->baseRepository->cidadaoExiste($request->input('cpf'));

        $validator = $this->cidadaoService->validaInformacoes($request->all());

        $cidadao = $this->baseRepository->novoCidadao();

        $cidadao = $this->cidadaoService->criaCidadao($request, $cidadao);

        if($this->baseRepository->salva($cidadao)) {
            return new CidadaoResource($cidadao);
        }
    }

    /**
     * Altera os dados do cidadão cujo cpf 
     * é igual ao informado
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $cpf
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function update(Request $request, string $cpf)
    {
        $cidadao = $this->baseRepository->cidadao($cpf);

        $validator = $this->cidadaoService->validaInformacoes($request->all());

        $cidadao = $this->cidadaoService->criaCidadao($request, $cidadao);

        if($this->baseRepository->salva($cidadao)) {
            return new CidadaoResource($cidadao);
        }
    }

    /**
     * Retorna os dados do cidadão cujo cpf 
     * é igual ao informado
     *
     * @param  string  $cpf
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(string $cpf)
    {
        $cidadao = $this->baseRepository->cidadao($cpf);

        return new CidadaoResource($cidadao);
    }

    /**
     * Remove uma cidadão do banco de dados
     *
     * @param  string  $cpf
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function destroy(string $cpf)
    {
        $cidadao = $this->baseRepository->cidadao($cpf);

        if ($this->baseRepository->remove($cidadao)) {
            return new CidadaoResource($cidadao);
        }
    }

    /**
     * 'Joga' uma exception mencionando que o 
     * CPF do cidadão não foi informado
     *
     * @return void
     */
    public function cpfErro()
    {
        $this->cidadaoService->cpfNaoInformado();
    }
}