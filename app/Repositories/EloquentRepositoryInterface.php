<?php

namespace App\Repositories;

use App\Cidadao;

interface EloquentRepositoryInterface
{
    public function cidadaosOrdenados(): object;

    public function cidadaoExiste($cpf): bool;

    public function cidadao($cpf, bool $store = false);

    public function novoCidadao(): Cidadao;

    public function remove(Cidadao $cidadao): bool;

    public function salva(Cidadao $cidadao): bool;
}
