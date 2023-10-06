<?php

namespace Src\Model;

use Src\_public\Util;
use Src\Model\Conexao;
use Src\VO\TipoEquipamentoVO;
use Src\Model\SQL\TipoEquipamento;
use Src\Model\SQL\TipoEquipamentoSQL;

class TipoEquipamentoDAO extends Conexao
{

    private $conexao;
    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function CadastrarTipo(TipoEquipamentoVO $vo): int
    {

        $sql = $this->conexao->prepare(TipoEquipamento::InserirTipo());
        $sql->bindValue(1, $vo->getNome());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function RetornarTipoEquipamentoDAO()
    {

        $sql = $this->conexao->prepare(TipoEquipamento::RetornarTiposEquipamentos());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function FiltrarTipoEquipamentoDAO($filtro_palavra)
    {

        $sql = $this->conexao->prepare(TipoEquipamento::FiltrarTipoEquipamentoSQL($filtro_palavra));
        if (!empty($filtro_palavra)) {

            $sql->bindValue(1, "%" . $filtro_palavra . "%");
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ConsultarTipoEquipamentoDAO($filtro_palavra): array
    {

        $sql = $this->conexao->prepare(TipoEquipamentoSQL::ConsultarTipoEquipamentoSQL($filtro_palavra));
        if (!empty($filtro_palavra)) {

            $sql->bindValue(1, "%" . $filtro_palavra . "%");
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ConsultarTipoEquipamentoAllDAO(): array
    {

        $sql = $this->conexao->prepare(TipoEquipamentoSQL::ConsultarTipoEquipamento());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function AlterarTipoEquipamentoDAO(TipoEquipamentoVO $vo): int
    {
        $sql = $this->conexao->prepare(TipoEquipamento::AlterarTipoEquipamentoSQL());
        $sql->bindValue(1, $vo->getNome());
        $sql->bindValue(2, $vo->getID());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -2;
        }
    }

    public function ExcluirTipoEquipamentoDAO(TipoEquipamentoVO $vo): int
    {
        $sql = $this->conexao->prepare(TipoEquipamento::ExcluirTipoEquipamentoSQL());
        $sql->bindValue(1, $vo->getID());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -2;
        }
    }
    public function DadosEmpresaDAO()
    {
        $sql = $this->conexao->prepare(TipoEquipamentoSQL::DADOS_EMPRESA_SQL());
        $i = 1;
        $sql->bindValue($i++, Util::EmpresaLogado());
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }
}
