# Cadastro de Cidadãos

Uma REST API desenvolvida com Laravel em PHP e MYSQL para o banco de dados.

Migrations e Seeders estão incluídas no código.

Edite o arquivo .env incluído com suas credenciais para o banco de dados.

# Como utilizar?

## Listar cidadãos

```
GET request -> /cidadaos/
```

Retornará uma lista com todos os cidadãos registrados no banco de dados em ordem alfabética crescente.

### Observações

1. Se o usuário tentar acessar o endpoint utilizando os métodos `POST`, `PUT`, `PATH` ou `DELETE` uma mensagem de erro aparecerá informado que o único método aceito é o `GET`.

## Consultar cidadão

```
GET request -> /cidadao/<cpf-do-cidadao>
```

Retornará todas as informações do cidadão cujo número do CPF seja igual ao informado.

### Observações

1. O CPF do cidadão deverá ser informado na URL do endpoint através de uma sequência de 11 dígitos sem pontos e sem hífen;
2. Se o CPF não for repassado, uma mensagem de erro aparecerá informando que o mesmo é obrigatório.

## Inserir cidadão

```
POST request -> /cidadao/
{
	"nome": "José",
	"sobrenome": "Silva",
	"cpf": "00000000000",
	"telefone": "11111111",
	"email": "jose@exemplo.com",
	"celular": "22222222",
	"cep": "33333333"
}
```

Os dados passados serão inseridos no banco de dados caso já não exista um cidadão cadastrado com o mesmo número de CPF.

### Observações

1. O endereço do cidadão será recuperado automaticamente através da API da ViaCEP mediante o repasse de um CEP válido;
2. Todos os dados passarão por uma validação antes de serem inseridos no banco de dados;
3. Não será permitida a inserção de mais de um cidadão com o mesmo número de CPF;
4. A API retornará todos os dados do cidadão em questão que foram inseridos no banco de dados, incluindo seu endereço completo.

## Edição de dados do cidadão

```
PUT ou PATCH request -> /cidadao/<cpf-do-cidadao>
{
	"nome": "João",
	"sobrenome": "Silva",
	"cpf": "00000000000",
	"telefone": "11111111",
	"email": "joao@exemplo.com",
	"celular": "22222222",
	"cep": "33333333"
}
```

Possibilita a alteração de quaisquer dados dos listados acima do cidadão cujo CPF seja igual ao informado.

### Observações

1. O CPF do cidadão deverá ser informado na URL do endpoint através de uma sequência de 11 dígitos sem pontos e sem hífen;
2. Se o CPF não for repassado, uma mensagem de erro aparecerá informando que o mesmo é obrigatório;
3. Ao solicitar a alteração de um certo dado, todos os outros dados deverão ser informados, mesmo que o usuário não os deseje alterá-los;
4. Uma nova consulta à API da ViaCEP será realizada independente da alteração ou não do CEP do cidadão.

## Exclusão de cidadão

```
DELETE request -> /cidadao/<cpf-do-cidadao>
```

Remove o registro de um cidadão do banco de dados.

### Observações

1. O CPF do cidadão deverá ser informado na URL do endpoint através de uma sequência de 11 dígitos sem pontos e sem hífen;
2. Se o CPF não for repassado, uma mensagem de erro aparecerá informando que o mesmo é obrigatório.