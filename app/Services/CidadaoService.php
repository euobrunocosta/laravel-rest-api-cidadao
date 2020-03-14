<?php

namespace App\Services;
use App\Cidadao;
use Validator;
use App\Exceptions\RotaIndisponivelException;
use App\Exceptions\CPFNaoInformadoException;
use App\Exceptions\ValidacaoFalhaException;
use App\Exceptions\CEPNaoEncontradoException;

class CidadaoService
{
    /**
     * Método chamado para 'jogar' uma exception
     * mencionando que a rota não está disponível
     * para o método utilizado
     * 
     * @return void
     */
    public function rotaIndisponivel():void
    {
        throw new RotaIndisponivelException();
    }

    /**
     * Método chamado para 'jogar' uma exception
     * mencionando que o CPF do cidadão não
     * foi repassado
     * 
     * @return void
     */
    public function cpfNaoInformado():void
    {
        throw new CPFNaoInformadoException();
    }

    /**
     * Certifica que o cpf repassado é do tipo int
     *
     * @param  mixed  $cpf
     * @return int
     */
    public function intCPF($cpf):int
    {
        return (int) $cpf;
    }

    /**
     * Recebe as informações passadas pelo cadastro,
     * define regras de validação para cada uma delas
     * e retorna o resultado
     *
     * @param  array  $informacoes
     * @return void
     */
    public function validaInformacoes(array $informacoes):void
    {
        $validator = Validator::make($informacoes, [
            'nome' => 'required|string',
            'sobrenome' => 'required|string',
            'cpf' => 'required|digits_between:1,11',
            'telefone' => 'required|digits_between:8,15',
            'email' => 'required|email',
            'celular' => 'required|digits_between:8,15',
            'cep' => 'required|digits:8'
        ]);

        if ($validator->fails()) {
            throw new ValidacaoFalhaException();
        }
    }

    /**
     * Faz uma requisição à API da ViaCEP para encontrar
     * o endereço referente ao cep informado.
     *
     * @param  string  $cep
     * @return object
     */
    private function getEndereco(string $cep):object
    {
        $endereco = json_decode(file_get_contents("https://viacep.com.br/ws/{$cep}/json/"));
        if (isset($endereco->erro)) {
            throw new CEPNaoEncontradoException();
        }
        return $endereco;
    }

    /**
     * Faz uma requisição à API da ViaCEP para encontrar
     * o endereço referente ao cep informado.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Cidadao $cidadao
     * @return Cidadao
     */
    public function criaCidadao(\Illuminate\Http\Request $request, Cidadao $cidadao):Cidadao
    {
        $cidadao->nome = $request->input('nome');
        $cidadao->sobrenome = $request->input('sobrenome');
        $cidadao->cpf = $request->input('cpf');
        $cidadao->telefone = $request->input('telefone');
        $cidadao->email = $request->input('email');
        $cidadao->celular = $request->input('celular');
        $cidadao->cep = $request->input('cep');

        $endereco = $this->getEndereco($request->input('cep'));

        $cidadao->logradouro = $endereco->logradouro;
        $cidadao->bairro = $endereco->bairro;
        $cidadao->cidade = $endereco->localidade;
        $cidadao->uf = $endereco->uf;

        return $cidadao;
    }
}