<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\CidadaoEncontradoException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof CidadaoEncontradoException) {
            return response()->json([
                'error' => 'Cidadão com CPF já cadastrado'
            ], 403);
        }
        if ($exception instanceof CidadaosNaoEncontradoException) {
            return response()->json([
                'error' => 'Sem cidadãos registrados no banco de dados!'
            ], 400);
        }
        if ($exception instanceof RotaIndisponivelException) {
            return response()->json([
                'error' => 'Rota disponível apenas para o método GET'
            ], 400);
        }
        if ($exception instanceof CidadaoNaoEncontradoException) {
            return response()->json([
                'error' => 'Nenhum cidadão com o CPF informado encontrado no banco de dados'
            ], 400);
        }
        if ($exception instanceof CPFNaoInformadoException) {
            return response()->json([
                'error' => 'O CPF do cidadão não foi informado'
            ], 400);
        }
        if ($exception instanceof ValidacaoFalhaException) {
            return response()->json([
                'error' => 'Erro na validação de algum(uns) dos itens repassados'
            ], 400);
        }
        if ($exception instanceof CEPNaoEncontradoException) {
            return response()->json([
                'error' => 'CEP não encontrado'
            ], 400);
        }
        return parent::render($request, $exception);
    }
}
