<?php
namespace Src\Controller;

use Src\Model\LoteDAO;
use Src\VO\LoteVO;
use Src\_public\Util;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LoteController
{

    private $dao;

    public function __construct()
    {
        $this->dao = new LoteDAO;
    }

    public function InserirLoteController($vo)
    {
      /*   $vo->setfuncao(CADASTRAR_LOTE);
        $vo->setIdLogado($vo->getId());
 */
        return $this->dao->CadastrarLoteDAO($vo);
    }

    public function FiltrarLoteController($empresa, $filtro, $status)
    {
        return $this->dao->FiltrarLoteDAO($empresa, $filtro, $status);

    }
    public function InativarLoteController($status, $lote_id, $empresa): int
    {
        return $this->dao->InativarLoteDAO($status, $lote_id, $empresa);

    }
    public function ReabrirLoteController($status, $lote_id, $empresa)
    {

        

        return $this->dao->ReabrirLoteDAO($status, $lote_id, $empresa);

    }

    public function ImportarEquipamentoController($excel, $numero_lote, $equipamento_id, $empresa_id, $qtd_equip, $data_lote)
    {
        // Carrega o arquivo Excel
        $inputFileName = $excel['tmp_name'];
        $spreadsheet = IOFactory::load($inputFileName);
    
        $worksheet = $spreadsheet->getActiveSheet();
        $data = array();
    
        $dadosParaDAO = array();
    
        $firstRow = true; // Variável para identificar a primeira linha (cabeçalho)
        $rowCount = 0; // Contador de linhas com dados relevantes

        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = array();
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
    
            if ($firstRow) {
                $firstRow = false;
                continue; // Ignora a primeira linha (cabeçalho)
            }
    
            // Verifique se a linha tem dados relevantes (não é cabeçalho, etc.)
            if (!empty($rowData[1]) && !empty($rowData[3])) {
                $rowCount++;
                $serie = $rowData[1]; // Coluna da Série
                $versao = $rowData[3]; // Coluna da Versão
    
                // Preenche o array de dados para a DAO
                $dadosParaDAO[] = array(
                    'serie' => $serie,
                    'versao' => $versao,
                    'equipamento_id' => $equipamento_id
                );
            }
        }
        // Verifique se a quantidade de equipamentos na planilha é igual a qtd_equip
         if ($rowCount != $qtd_equip) {
            return array(
                'status' => -11, // Quantidade de equipamentos não bate com a informada
                'rowCount' => $rowCount
            );
        }
        // Chame a função da DAO para gravar os números de série e versão
        return $this->dao->ImportarEquipamentoDAO($dadosParaDAO, $numero_lote, $empresa_id, $data_lote);
    
        // Lógica de tratamento do resultado da DAO
       
    }
    
    

    
    
    
    
    
    
    






















    public function RetornaLoteController()
    {
        return $this->dao->RetornaLoteDAO();
    }

    public function ConsultarInsumosController($equipamento_id): array
    {
        return $this->dao->ConsultarInsumoDAO($equipamento_id);
    }

    public function ConsultarInsumoServicoLoteController($lote_equip_id): array
    {
        return $this->dao->ConsultarInsumoServicoLoteDAO($lote_equip_id);
    }
    public function ConsultarServicosLoteController($lote_equip_id): array
    {
        return $this->dao->ConsultarServicosLoteDAO($lote_equip_id);
    }

    public function ConsultarProdutosLoteController($lote_equip_id): array
    {
        return $this->dao->ConsultarProdutosLoteDAO($lote_equip_id);
    }
    

    public function FiltrarEquipamentoLoteController($lote_id): array
    {
        return $this->dao->FiltrarEquipamentoLoteDAO($lote_id);
    }

    public function EditarEquipamentoLoteController($vo)
    {
      /*   $vo->setfuncao(CADASTRAR_LOTE);
        $vo->setIdLogado($vo->getId());
 */
        return $this->dao->EditarEquipamentoLoteDAO($vo);
    }


    

    
    public function ConsultarServicoLoteController($equipamento_id): array
    {
        return $this->dao->ConsultarServicoLoteDAO($equipamento_id);
    }
    
    public function GravarDadosLoteController(array $produtos, $lote_equip_id)
    {
        return $this->dao->GravarDadosOsGeralDAO($produtos, $lote_equip_id);
    }

    public function GravarDadosServLoteController(array $servicos, $lote_equip_id)
    {
        return $this->dao->GravarDadosServOsGeralDAO($servicos, $lote_equip_id);
    }

    public function EncerramentoLoteController($vo)
    {
        return $this->dao->EncerramentoLoteDAO($vo);
    }

    
}




