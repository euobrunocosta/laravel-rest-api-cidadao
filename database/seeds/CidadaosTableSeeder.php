<?php

use App\Cidadao;
use Illuminate\Database\Seeder;

class CidadaosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cidadao::truncate();

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            Cidadao::create([
                'nome' => $faker->firstName(),
                'sobrenome' => $faker->lastName(),
                'cpf' => $faker->numberBetween($min = 10000000000, $max = 99999999999),
                'telefone' => $faker->numberBetween($min = 1000000000, $max = 9999999999),
                'email' => $faker->email(),
                'celular' => $faker->numberBetween($min = 1000000000, $max = 9999999999),
                'cep' => $faker->numberBetween($min = 10000000, $max = 99999999),
                'logradouro' => $faker->streetName(),
                'bairro' => $faker->state(),
                'cidade' => $faker->city(),
                'uf' => $faker->stateAbbr(),
            ]);
        }
    }
}
