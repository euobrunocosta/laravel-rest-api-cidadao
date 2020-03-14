<?php

namespace App\Exceptions;

use Exception;

class CidadaoEncontradoException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::debug('Cidadão com CPF informado já registrado no banco de dados');
    }
}
