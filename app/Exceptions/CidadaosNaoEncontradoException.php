<?php

namespace App\Exceptions;

use Exception;

class CidadaosNaoEncontradoException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::debug('Sem cidadãos registrados no banco de dados!');
    }
}
