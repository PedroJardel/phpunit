<?php

namespace Tests\Models;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    #[DataProvider('geraLances')]
    public function test_leilao_deve_receber_lances(
        int $qtdLances, 
        Leilao $leilao, 
        array $valores
        ): void {

        static::assertCount($qtdLances, $leilao->getLances());
        foreach ($valores as $i => $valorEsperado) {
            static::assertEquals($valorEsperado, $leilao->getLances()[$i]->getValor());
        }
    }

    public function test_nao_deve_aceitar_lances_consecutivos_do_mesmo_usuario() {

        static::expectException(\DomainException::class);
        static::expectExceptionMessage('Usuário não pode propor dois lances consecutivos');

        $leilao = new Leilao('Fiat 147 0KM');
        $joao = new Usuario('Joao');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($joao, 2000));
    }

    public function test_nao_deve_aceitar_cinco_lances_do_mesmo_usuario() {

        static::expectException(\DomainException::class);
        static::expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');

        $leilao = new Leilao('Fiat 147 0KM');
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 2000));
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 4000));
        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($maria, 6000));
        $leilao->recebeLance(new Lance($joao, 7000));
        $leilao->recebeLance(new Lance($maria, 8000));
        $leilao->recebeLance(new Lance($joao, 9000));
        $leilao->recebeLance(new Lance($maria, 10000));

        $leilao->recebeLance(new Lance($joao, 11000));
    }

    public static function geraLances(): array {
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');


        $leilaoCom1Lance = new Leilao('Fiat 147 0KM');
        $leilaoCom1Lance->recebeLance(new Lance($joao, 1000));

        $leilaoCom2Lances = new Leilao('Fusca 0KM');
        $leilaoCom2Lances->recebeLance(new Lance($joao, 1000));
        $leilaoCom2Lances->recebeLance(new Lance($maria, 2000));
        
        return [
            '1-lance' => [1, $leilaoCom1Lance, [1000]],
            '2-lances' => [2, $leilaoCom2Lances, [1000, 2000]],
        ];
    }
}