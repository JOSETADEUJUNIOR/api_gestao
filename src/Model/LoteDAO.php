<?php

namespace Src\Model;

use Src\Model\Conexao;
use Src\VO\LoteVO;
use Src\VO\Lote_insumoVO;
use Src\VO\Lote_servicoVO;
use Src\Model\SQL\LoteSQL;
use Src\_public\Util;
use PhpOffice\PhpSpreadsheet\IOFactory;
class LoteDAO extends Conexao
{

    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function CadastrarLoteDAO($vo): int
    {
        try {
            $this->conexao->beginTransaction();

            $sql = $this->conexao->prepare(LoteSQL::InserirLote());
            $i = 1;
            $sql->bindValue($i++, $vo->getNumero_lote());
            $sql->bindValue($i++, $vo->getEmpresa_id());
            $sql->bindValue($i++, $vo->getData_lote());
            /* $sql->bindValue($i++, $vo->getEquipamento_id());
            $sql->bindValue($i++, $vo->getVersao());
            $sql->bindValue($i++, $vo->getNumero_serie());
             */

            $sql->execute();
            $idLote = $this->conexao->lastInsertId();
            $quantidadeEquip = $vo->getQtdEquip();

            $sql = $this->conexao->prepare(LoteSQL::InserirLoteEquip());
            for ($j = 0; $j < $quantidadeEquip; $j++) {
                $i = 1;
                $sql->bindValue($i++, $idLote);
                $sql->bindValue($i++, $vo->getEquipamento_id());
                $sql->execute();
            }

            $this->conexao->commit();

            return 1;
        } catch (\Exception $ex) {
            $this->conexao->rollBack();
            /* $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo); */
            return -1;
        }
    }

    public function InativarLoteDAO($status, $lote_id, $empresa)
    {
        $sql = $this->conexao->prepare(LoteSQL::InativarLoteSQL());
        $i = 1;
        $sql->bindValue($i++, $status);
        $sql->bindValue($i++, $lote_id);
        $sql->bindValue($i++, $empresa);

        /* $sql->bindValue($i++, $vo->getEquipamento_id());
        $sql->bindValue($i++, $vo->getVersao());
        $sql->bindValue($i++, $vo->getNumero_serie());
         */

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            return -1;
        }
    }

    public function ReabrirLoteDAO($status, $lote_id, $empresa)
    {
       
        $sql = $this->conexao->prepare(LoteSQL::VerificaLoteChamado());
        $i = 1;
        $sql->bindValue($i++, $lote_id);
        $sql->bindValue($i++, $empresa);
        $sql->execute();

        $temChamado = $sql->fetchAll(\PDO::FETCH_ASSOC);
        
        if ($temChamado[0]['lote_id']>0) {
            return -4;
        }else{
            $sql = $this->conexao->prepare(LoteSQL::ReabrirLoteSQL());
            $i = 1;
            $sql->bindValue($i++, 'A');
            $sql->bindValue($i++, $lote_id);
            $sql->bindValue($i++, $empresa);
           
            
        }
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            return -1;
        }

    }


    public function ImportarEquipamentoDAO($dados, $numero_lote, $empresa_id, $data_lote)
    {

        $this->conexao->beginTransaction();
        
        $sql = $this->conexao->prepare(LoteSQL::InserirLote());
        $i = 1;
        $sql->bindValue($i++, $numero_lote);
        $sql->bindValue($i++, $empresa_id);
        $sql->bindValue($i++, $data_lote);
        /* $sql->bindValue($i++, $vo->getEquipamento_id());
        $sql->bindValue($i++, $vo->getVersao());
        $sql->bindValue($i++, $vo->getNumero_serie());
        */
        
        $sql->execute();
        $idLote = $this->conexao->lastInsertId();
        
        
        $sql = $this->conexao->prepare(LoteSQL::InserirLoteEquip());
    
        try {
            
            foreach ($dados as $item) {
                $serie = $item['serie'];
                $versao = $item['versao'];
                $equipamento_id = $item['equipamento_id'];

                $i = 1;
                $sql->bindValue($i++, $idLote);
                $sql->bindValue($i++, $equipamento_id);
                $sql->bindValue($i++, $serie);
                $sql->bindValue($i++, $versao);
    
                $sql->execute();
            }
    
            $this->conexao->commit();
            return 1;
        } catch (\Exception $ex) {
            $this->conexao->rollBack();
            return -3;
        }
    }
    


    
    

    public function FiltrarLoteDAO($empresa, $filtro, $status)
    {

        $sql = $this->conexao->prepare(LoteSQL::FiltrarLoteSQL($empresa, $filtro, $status));
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function RetornaLoteDAO()
    {
        $sql = $this->conexao->prepare(LoteSQL::RetornaLoteSQL());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function ConsultarInsumoDAO($equipamento_id)
    {
        $sql = $this->conexao->prepare(LoteSQL::ConsultarInsumoSQL());
        $sql->bindValue(1, $equipamento_id);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function ConsultarServicoLoteDAO($equipamento_id)
    {
        $sql = $this->conexao->prepare(LoteSQL::ConsultarServicoLoteSQL());
        $sql->bindValue(1, $equipamento_id);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ConsultarInsumoServicoLoteDAO($lote_equip_id)
    {
        $sql = $this->conexao->prepare(LoteSQL::ConsultarInsumoServicoLoteSQL());
        $sql->bindValue(1, $lote_equip_id);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    # para consultar os serviços dos equipamentos inseridos no lote
    public function ConsultarServicosLoteDAO($lote_equip_id)
    {
        $sql = $this->conexao->prepare(LoteSQL::ConsultarServicosLoteSQL());
        $sql->bindValue(1, $lote_equip_id);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ConsultarProdutosLoteDAO($lote_equip_id)
    {
        $sql = $this->conexao->prepare(LoteSQL::ConsultarProdutosLoteSQL());
        $sql->bindValue(1, $lote_equip_id);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function FiltrarEquipamentoLoteDAO($lote_id)
    {
        $sql = $this->conexao->prepare(LoteSQL::FiltrarEquipamentoLote());
        $sql->bindValue(1, $lote_id);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function EditarEquipamentoLoteDAO(LoteVO $vo)
    {


        $sql = $this->conexao->prepare(LoteSQL::EditarEquipamentoLoteSQL());
        $i = 1;
        $sql->bindValue($i++, $vo->getNumero_serie());
        $sql->bindValue($i++, $vo->getVersao());
        $sql->bindValue($i++, $vo->getId_lote_equip());
        /* $sql->bindValue($i++, $vo->getEquipamento_id());
    $sql->bindValue($i++, $vo->getVersao());
    $sql->bindValue($i++, $vo->getNumero_serie());
     */

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }




    public function GravarDadosOsGeralDAO($produtos, $lote_equip_id)
    {
        foreach ($produtos as $p) {
            $sqlSaldo = $this->conexao->prepare(LoteSQL::ConsultarSaldoProdutoSQL());
            $sqlSaldo->bindValue(1, $p['produto_id']);
            $sqlSaldo->execute();
            $saldo = $sqlSaldo->fetch(\PDO::FETCH_ASSOC);

            if ($saldo['ProdEstoque'] >= $p['qtd']) {
                // Saldo é suficiente, então você pode prosseguir com a gravação e atualização do saldo
                $sql = $this->conexao->prepare(LoteSQL::InserirLoteInsumo());

                $i = 1;
                $sql->bindValue($i++, $p['valor']);
                $sql->bindValue($i++, $p['qtd']);
                $sql->bindValue($i++, $lote_equip_id);
                $sql->bindValue($i++, $p['produto_id']);
                $sql->execute();

                $atualizacaoSaldo = $this->conexao->prepare(LoteSQL::AtualizarSaldoProdutoSQL());
                $atualizacaoSaldo->bindValue(1, $p['qtd']);
                $atualizacaoSaldo->bindValue(2, $p['produto_id']);
                $atualizacaoSaldo->execute();
            } else {
                // Saldo insuficiente, você pode retornar algum código de erro ou mensagem informando isso
                return -2; // Por exemplo
            }
        }

        try {
            return 1;
        } catch (\Exception $ex) {
            return -1;
        }
    }

    public function GravarDadosServOsGeralDAO($servicos, $lote_equip_id)
    {
        foreach ($servicos as $p) {

            $sql = $this->conexao->prepare(LoteSQL::InserirLoteServico());

            $i = 1;
            $sql->bindValue($i++, $p['valor']);
            $sql->bindValue($i++, 1);
            $sql->bindValue($i++, $lote_equip_id);
            $sql->bindValue($i++, $p['servico_id']);
            $sql->execute();
        }
        try {
            return 1;
        } catch (\Exception $ex) {
            return -1;
        }
    }
    public function CalculaTotalLoteDAO($vo)
    {
        $sql = $this->conexao->prepare(LoteSQL::CalculaTotalLoteSQL());
        $i = 1;
        $sql->bindValue($i++, $vo->getid());

        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }
    public function EncerramentoLoteDAO($vo)
    {
        $sql = $this->conexao->prepare(LoteSQL::CalculaTotalLoteSQL());
        $sql->bindValue(1, $vo->getId());
        $sql->execute();

        $valorTotal = 0;

        while ($row = $sql->fetch(\PDO::FETCH_ASSOC)) {
            $valorTotal += $row['total_geral'];
        }



        if ($valorTotal > 0) {
            $sql = $this->conexao->prepare(LoteSQL::EncerramentoLoteSQL());
            $i = 1;
            $sql->bindValue($i++, $valorTotal);
            $sql->bindValue($i++, $vo->getStatus());
            $sql->bindValue($i++, $vo->getId());

            try {
                $sql->execute();
                return 1;
            } catch (\Exception $ex) {
                $vo->setmsg_erro($ex->getMessage());
                parent::GravarLogErro($vo);
                return -1;
            }
        } else {
            return 0;
        }
    }
}
