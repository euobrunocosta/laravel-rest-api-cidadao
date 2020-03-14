<?php

namespace App\Exceptions;

use Exception;

class RotaIndisponivelException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::debug('Rota disponível apenas para o método GET');
    }
}
