<?php

namespace App\Exceptions;

use Exception;

class CidadaoNaoEncontradoException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::debug('Nenhum cidadão com o CPF informado encontrado no banco de dados');
    }
}
