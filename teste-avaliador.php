<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Service\Avaliador;
use Alura\Leilao\Model\Usuario;

require 'vendor/autoload.php';

// Arrange - Given
$leilao = new Leilao('Fiat 147 0KM');

$maria = new Usuario( 'Maria');
$joao = new Usuario('Joao');

$leilao->recebeLance(new Lance($joao, 2000));
$leilao->recebeLance(new Lance($maria, 2500));

$leiloeiro = new Avaliador();

// Act - When
$leiloeiro->avalia($leilao);
$maiorValor = $leiloeiro->getMaiorValor();

// Assert - Then
$valorEsperado = 2500;
if($maiorValor == $valorEsperado) {
    echo "TESTE OK!" . PHP_EOL;
} else {
    echo "TESTE FALHOU!" . PHP_EOL;
}

echo $maiorValor;