<?php

namespace Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{

    /**
     *  @dataProvider entregaLeiloes
     */
    public function test_maior_lance_do_leilao(Leilao $leilao)
    {
        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);
        $maiorValor = $leiloeiro->getMaiorValor();

        // Assert - Then
       self::assertEquals(2500, $maiorValor);
    }

    /**
     *  @dataProvider entregaLeiloes
     */
    public function test_menor_lance_do_leilao(Leilao $leilao)
    {
        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);
        $menorValor = $leiloeiro->getMenorValor();

        // Assert - Then
       self::assertEquals(1800, $menorValor);
    }
    
    /**
     *  @dataProvider entregaLeiloes
     */
    public function test_avaliador_deve_buscar_os_tres_maiores_valores(Leilao $leilao)
    {
        $leiloeiro = new Avaliador();

        // Act - When
        $leiloeiro->avalia($leilao);
        $maioresValores = $leiloeiro->getMaioresLances();

        // Assert - Then
        self::assertCount(3, $maioresValores);
        self::assertEquals(2500, $maioresValores[0]->getValor());
        self::assertEquals(2000, $maioresValores[1]->getValor());
        self::assertEquals(1800, $maioresValores[2]->getValor());
    }

    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1800));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return $leilao;
    }

    public function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana, 1800));

        return $leilao;
    }

    public function leilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 1800));

        return $leilao;
    }

    public function entregaLeiloes() 
    {
        return [
            [$this->leilaoEmOrdemCrescente()],
            [$this->leilaoEmOrdemDecrescente()],
            [$this->leilaoEmOrdemAleatoria()]
        ];
    }
}
