<?php

namespace App\Exceptions;

use Exception;

class ValidacaoFalhaException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::debug('Erro na validação de algum(uns) dos itens repassados');
    }
}
