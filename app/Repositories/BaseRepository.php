<?php

namespace App\Repositories;

use App\Cidadao;
use App\Exceptions\CidadaoEncontradoException;
use App\Exceptions\CidadaoNaoEncontradoException;
use App\Exceptions\CidadaosNaoEncontradoException;
use App\Repositories\EloquentRepositoryInterface;
use App\Services\CidadaoService;

class BaseRepository implements EloquentRepositoryInterface
{

    private $cidadaoService;

    public function __construct(CidadaoService $cidadaoService)
    {
        $this->cidadaoService = $cidadaoService;
    }

    /**
     * Retorna uma lista com todos os didadãos registrados
     * no banco de dados ordenados pelo nome em ordem alfabética
     *
     * @return object
     */
    public function cidadaosOrdenados(): object
    {
        $cidadaos = Cidadao::orderBy('nome')->get();
        if (count($cidadaos) == 0) {
            throw new CidadaosNaoEncontradoException();
        }
        return $cidadaos;
    }

    /**
     * Checa se existe no banco de dados um cidadão
     * cujo CPF seja igual ao informado
     *
     * @param  mixed  $cpf
     * @return bool
     */
    public function cidadaoExiste($cpf): bool
    {
        $cpf = $this->cidadaoService->intCPF($cpf);
        $cidadao = $this->cidadao($cpf, $store = true);
        if ($cidadao) {
            throw new CidadaoEncontradoException();
        }
        return false;
    }

    /**
     * Recupera no banco de dados um cidadão cujo CPF
     * seja igual ao informado
     *
     * @param  mixed  $cpf
     * @param  bool  $store
     * @return Cidadao
     */
    public function cidadao($cpf, bool $store = false)
    {
        $cpf = $this->cidadaoService->intCPF($cpf);
        $cidadao = Cidadao::where('cpf', $cpf)->first();
        if (!$cidadao && !$store) {
            throw new CidadaoNaoEncontradoException();
        }
        return $cidadao;
    }

    /**
     * Cria uma nova instância do Model Cidadao
     *
     * @return Cidadao
     */
    public function novoCidadao(): Cidadao
    {
        return new Cidadao;
    }

    /**
     * Remove o cidadão passado como parâmetro
     * do banco de dados e retorna true se a
     * remoção for bem sucedida
     *
     * @param  Cidadao  $cidadao
     * @return bool
     */
    public function remove(Cidadao $cidadao): bool
    {
        return $cidadao->delete();
    }

    /**
     * Adiciona o cidadão passado como parâmetro
     * ao banco de dados e retorna true se a
     * adição for bem sucedida
     *
     * @param  Cidadao  $cidadao
     * @return bool
     */
    public function salva(Cidadao $cidadao): bool
    {
        return $cidadao->save();
    }

}
