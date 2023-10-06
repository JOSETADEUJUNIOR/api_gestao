<?php

namespace Src\Controller;

use Src\Model\EquipamentoDAO;
use Src\VO\EquipamentoVO;
use Src\_public\Util;
use Src\VO\AlocarVO;
use Src\VO\LogErro;

class EquipamentoController
{
    private $dao;

    public function __construct()
    {
        $this->dao = new EquipamentoDAO;
    }

    public function AlterarStatusEquipamentoCTRL(EquipamentoVO $vo): int
    {
        $vo->setStatusEquipamento($vo->getStatusEquipamento() == STATUS_ATIVO ? STATUS_INATIVO : STATUS_ATIVO);
        $vo->setfuncao(STATUS_EQUIPAMENTO);
        $vo->setIdLogado(Util::CodigoLogado());
        return $this->dao->AlterarStatusEquipamentoDAO($vo);
    }

    public function AlterarEquipamentoController(EquipamentoVO $vo)
    {
        if (empty($vo->getIdentificacao()) || empty($vo->getTipoEquipID()) || empty($vo->getModeloEquipID()))
            return 0;

        $vo->setfuncao(CADASTRO_EQUIPAMENTO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->AlterarEquipamentoDAO($vo);
    }

    public function CadastrarServicoEquipamentoController(EquipamentoVO $vo)
    {
        $vo->setfuncao(CADASTRO_EQUIPAMENTO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->CadastrarServicoEquipamentoDAO($vo);
    }

    public function CadastrarInsumoEquipamentoController(EquipamentoVO $vo)
    {
        $vo->setfuncao(CADASTRO_EQUIPAMENTO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->CadastrarInsumoEquipamentoDAO($vo);
    }

    public function CadastrarEquipamentoController(EquipamentoVO $vo)
    {
        if (empty($vo->getIdentificacao()) || empty($vo->getTipoEquipID()) || empty($vo->getModeloEquipID()))
            return 0;

        $vo->setfuncao(CADASTRO_EQUIPAMENTO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->CadastrarEquipamentoDAO($vo);
    }

    public function AlocarEquipamentoController(AlocarVO $vo): int
    {
        if (empty($vo->getEquipamentoID()) || empty($vo->getSetorID()))
            return 0;

        $vo->setDataAlocacao(Util::DataAtualBd());
        $vo->setSituacao(SITUACAO_ALOCADO);
        $vo->setfuncao(CADASTRO_ALOCAR);
        $vo->setIdLogado(Util::CodigoLogado());


        return $this->dao->AlocarEquipamentoDAO($vo);
    }

    public function ConsultarEquipamentoController($BuscarTipo, $filtro_palavra): array
    {
        if (empty(trim($filtro_palavra))) {
            return [];
        }
        return $this->dao->ConsultarEquipamentoDAO($BuscarTipo, $filtro_palavra);
    }

    public function ConsultarEquipamentoAllController($empresa_id, $tipo): array
    {
        if(isset($tipo) && $tipo == '3'){
                $id_empresa = $empresa_id;
        }else{
            $id_empresa = Util::EmpresaLogado();
        }
        
        return $this->dao->ConsultarEquipamentoAllDAO($id_empresa);
    }

    public function SelecionarEquipamentosNaoAlocadosController()
    {
        $lista = $this->dao->SelecionarEquipamentosNaoAlocadosDAO(SITUACAO_REMOVIDO);
        for ($i = 0; $i < count($lista); $i++) {
            $lista[$i]['nome_modelo'] =  $lista[$i]['nome_modelo'] . ' / ' . $lista[$i]['nome_tipo'] . ' / ' . $lista[$i]['identificacao'];
        }
        return $lista;
    }
    public function DetalharEquipamentoController($id)
    {
        if (empty(trim($id))) {
            return 0;
        }
        return $this->dao->DetalharEquipamentoDAO($id);
    }

    public function RetornarEquipamentoController()
    {
        return $this->dao->RetornarEquipamentoDAO();
    }

    public function FiltrarEquipamentoController($nome_filtro)
    {

        return $this->dao->FiltrarEquipamentoDAO($nome_filtro);
    }

    public function ExcluirEquipamentoController(EquipamentoVO $vo): int
    {
        if (empty($vo->getID()))
            return 0;

        $vo->setfuncao(EXCLUI_EQUIPAMENTO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->ExcluirEquipamentoDAO($vo);
    }

    public function SelecionarEquipamentosAlocadosSetorController($idSetor): array
    {
        return $this->dao->SelecionarEquipamentosAlocadosSetorDAO(SITUACAO_ALOCADO, $idSetor);
    }
    public function SelecionarEquipamentosAlocadosController($situacao): array
    {
        return $this->dao->SelecionarEquipamentosAlocadosDAO($situacao);
    }


    public function RemoverAlocamentoController(AlocarVO $vo): int
    {
        if (empty($vo->getIdAlocar()))
            return 0;

        $vo->setDataRemocao(Util::DataAtualBd());
        $vo->setSituacao(SITUACAO_REMOVIDO);
        $vo->setfuncao(EXCLUI_EQUIPAMENTO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->RemoverAlocamentoDAO($vo);
    }

    public function SelecionarServicoEquipamentoController()
    {
        return $this->dao->SelecionarServicoEquipamentoDAO();
    }

    public function SelecionarProdutoEquipamentoController()
    {
        return $this->dao->SelecionarProdutoEquipamentoDAO();
    }

    public function ListaServicoDoEquipamentoController($id_equipamento)
    {
        return $this->dao->ListaServicoDoEquipamentoDAO($id_equipamento);
    }

    public function ListaProdutoDoEquipamentoController($id_equipamento)
    {
        return $this->dao->ListaProdutoDoEquipamentoDAO($id_equipamento);
    }

    public function RemoverProdutoEquipamentoController(EquipamentoVO $vo)
    {
        if (empty($vo->getId()))
            return 0;

        $vo->setfuncao(EXCLUI_PRODUTO_EQUIPAMENTO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->RemoverProdutoEquipamentoDAO($vo);
    }

    public function RemoverServicoEquipamentoController(EquipamentoVO $vo)
    {
        if (empty($vo->getId()))
            return 0;
        
        $vo->setfuncao(EXCLUI_SERVICO_EQUIPAMENTO);
        $vo->setIdLogado(Util::CodigoLogado());

        return $this->dao->RemoverServicoEquipamentoDAO($vo);
    }
}
