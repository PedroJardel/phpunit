<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    /** @var bool */
    private $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {
        if(!empty($this->lances) && $this->isLastUser($lance)) {
            throw new \DomainException('Usuário não pode propor dois lances consecutivos');
        }

        if($this->quantidadeLancesPorUsuario($lance) >= 5) {
            throw new \DomainException('Usuário não pode propor mais de 5 lances por leilão');
        }

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    public function finaliza(): void {
        $this->finalizado = true;
    }

    public function getFinalizado(): bool
    {
        return $this->finalizado;
    }

    private function quantidadeLancesPorUsuario(Lance $lance): int {
        $totalLancesUsuario = array_reduce($this->lances, function(int $total, Lance $lanceAtual) use ($lance) {
            if($lanceAtual->getUsuario() == $lance->getUsuario()) {
                return $total + 1;
            }
            return $total;
        }, 0);

        return $totalLancesUsuario;
    }

    private function isLastUser(Lance $lance): bool
    {
        $ultimoLance = $this->lances[count($this->lances) - 1];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }
}
