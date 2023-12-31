<?php

namespace Src\Model;

use Src\_public\Util;
use Src\Model\Conexao;
use Src\VO\ServicoVO;
use Src\Model\SQL\ServicoSQL;


class ServicoDAO extends Conexao
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function CadastrarServico(ServicoVO $vo): int
    {

        if (!empty($vo->getServID())) {
            $sql = $this->conexao->prepare(ServicoSQL::AlterarServicoSQL());
            $sql->bindValue(1, $vo->getServNome());
            $sql->bindValue(2, $vo->getServValor());
            $sql->bindValue(3, $vo->getServDescricao());
            $sql->bindValue(4, $vo->getServEmpID());
            $sql->bindValue(5, $vo->getServUserID());
            $sql->bindValue(6, $vo->getServID());
        } else {
            $sql = $this->conexao->prepare(ServicoSQL::InserirServicoSQL());
            $sql->bindValue(1, $vo->getServNome());
            $sql->bindValue(2, $vo->getServValor());
            $sql->bindValue(3, $vo->getServDescricao());
            $sql->bindValue(4, $vo->getServEmpID());
            $sql->bindValue(5, $vo->getServUserID());
        }

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function FiltrarServicoDAO($filtro_palavra)
    {

        $sql = $this->conexao->prepare(ServicoSQL::FiltrarServicoSQL($filtro_palavra));
        $sql->bindValue(1, Util::EmpresaLogado());
        if (!empty($filtro_palavra)) {

            $sql->bindValue(2, "%" . $filtro_palavra . "%");
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ConsultarServicoDAO($filtro_palavra): array
    {

        $sql = $this->conexao->prepare(ServicoSQL::ConsultarServicoSQL($filtro_palavra));
        if (!empty($filtro_palavra)) {

            $sql->bindValue(1, "%" . $filtro_palavra . "%");
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ConsultarServicoAllDAO(): array
    {

        $sql = $this->conexao->prepare(ServicoSQL::RetornarServicoSQL());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function DetalharServicoDAO($id)
    {

        $sql = $this->conexao->prepare(ServicoSQL::DetalharServicoSQL());
        $sql->bindValue(1, $id);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function RetornarServicoDAO()
    {
        $sql = $this->conexao->prepare(ServicoSQL::RetornarServicoSQL());
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function AlterarServicoDAO(ServicoVO $vo): int
    {
        $sql = $this->conexao->prepare(ServicoSQL::AlterarServicoSQL());
        $sql->bindValue(1, $vo->getServNome());
        $sql->bindValue(2, $vo->getServID());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -2;
        }
    }

    public function ExcluirServicoDAO(ServicoVO $vo): int
    {
        $sql = $this->conexao->prepare(ServicoSQL::ExcluirServico());
        $sql->bindValue(1, $vo->getServID());

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
        $sql = $this->conexao->prepare(ServicoSQL::DADOS_EMPRESA_SQL());
        $i = 1;
        $sql->bindValue($i++, Util::EmpresaLogado());
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }
}
