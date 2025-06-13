<?php

namespace Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class AvaliadorTest extends TestCase
{
    private $leiloeiro;
    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    #[DataProvider('entregaLeiloes')]
    public function test_maior_lance_do_leilao(Leilao $leilao): void
    {
        // Act - When
        $this->leiloeiro->avalia($leilao);
        $maiorValor = $this->leiloeiro->getMaiorValor();

        // Assert - Then
        self::assertEquals(2500, $maiorValor);
    }

    #[DataProvider('entregaLeiloes')]
    public function test_menor_lance_do_leilao(Leilao $leilao): void
    {
        // Act - When
        $this->leiloeiro->avalia($leilao);
        $menorValor = $this->leiloeiro->getMenorValor();

        // Assert - Then
        self::assertEquals(1800, $menorValor);
    }

    #[DataProvider('entregaLeiloes')]
    public function test_avaliador_deve_buscar_os_tres_maiores_valores(Leilao $leilao): void
    {
        // Act - When
        $this->leiloeiro->avalia($leilao);
        $maioresValores = $this->leiloeiro->getMaioresLances();

        // Assert - Then
        self::assertCount(3, $maioresValores);
        self::assertEquals(2500, $maioresValores[0]->getValor());
        self::assertEquals(2000, $maioresValores[1]->getValor());
        self::assertEquals(1800, $maioresValores[2]->getValor());
    }

    public function test_leilao_sem_lances_deve_retornar_excecao(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar um leilão sem lances');

        $leilao = new Leilao('Fiat 147 0KM');
        $this->leiloeiro->avalia($leilao);
    }

    #[DataProvider('entregaLeiloes')]
    public function test_leilao_finalizado_nao_pode_ser_avaliado(Leilao $leilao): void 
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');
        $leilao->finaliza();
        $this->leiloeiro->avalia($leilao);
    }
    public static function leilaoEmOrdemCrescente(): Leilao
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

    public static function leilaoEmOrdemDecrescente(): Leilao
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

    public static function leilaoEmOrdemAleatoria(): Leilao
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

    public static function entregaLeiloes(): array
    {
        return [
            "ordem-crescente" => [self::leilaoEmOrdemCrescente()],
            "ordem-decrescente" => [self::leilaoEmOrdemDecrescente()],
            "ordem-aleatoria" => [self::leilaoEmOrdemAleatoria()]
        ];
    }
}
