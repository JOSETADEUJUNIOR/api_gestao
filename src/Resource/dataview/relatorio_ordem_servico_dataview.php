<?php

include_once '_include_autoload.php';

use Src\_public\Util;
use Dompdf\Dompdf;
use Dompdf\Options;
use Src\Controller\ChamadoController;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['filtro']) && $_GET['pdf']) {
  $options = new Options();
  $options->setChroot('../../Resource/dataview/arquivos');

  $html = "";
  $ctrl = new ChamadoController();
  $BuscarTipo = $_GET['filtro'];
  $filtro_palavra = $_GET['desc_filtro'];
  $LoteID = $_GET['lote_id'];
  $dados = $ctrl->RetornarEquipamentosLoteController($LoteID, Util::EmpresaLogado());

  ob_start();
  $img = '<img src=../../Resource/dataview/' . $dados[0]['EmpLogoPath'] . ' height="100px" width="150px" alt="Photo 2" class="img-fluid">'; ?>


  <style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    td,
    th {
      border: 1px solid black;
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }
  </style>


<table style="width:100%">
            <tr>
                <th> <?= $img ?>
                </th>
                <th colspan="8">
                    <p>Nome empresa:<span><?= $dados[0]['EmpNome']?></span></p>
                    <p>Empresa CNPJ:<span><?= $dados[0]['EmpCNPJ']?></span></p>
                    <p>Empresa Endereço:<span><?= $dados[0]['EmpEnd']?></span></p>
                </th>
            </tr>
           
               <tr>
                <td style="text-align: left;" colspan="9">
                    <strong>Relação de Lote: </strong><?= $dados[0]['lote_id']?>
                </td>
            </tr>
            <tr>
                <td><strong>Equipamento</strong></td>
                <td><strong>Numero de Série</strong></td>
                <td><strong>Lote</strong></td>
                <td><strong>Versão</strong></td>
                <td><strong>Insumo/Material</strong></td>
                <td><strong>Total Insumo</strong></td>
                <td><strong>Serviço</strong></td>
                <td><strong>Total Serviços</strong></td>
                <td><strong>Valor Total</strong></td>
            </tr>
            <?php $servicoZ = 0; $valorTotal=0; $valorInsumo=0;$valorServicos=0; ?>
           <?php
            foreach ($dados as $registro) {
              $servicoZ++;
              $valorTotal = ($registro['total_geral'] + $valorTotal);
              $valorInsumo = ($registro['total_insumos'] + $valorInsumo);
              $valorServicos = ($registro['total_servicos'] + $valorServicos);
              
              
              
              ?>
                <tr>
                    <td><?= $registro['descricao'] ?></td>
                    <td><?= $registro['numero_serie_equipamento'] ?></td>
                    <td><?= $registro['numero_lote'] ?></td>
                    <td><?= $registro['versao'] ?></td>
                    <td><?= $registro['insumos_nome'] ?></td>
                    <td><?= $registro['total_insumos'] ?></td>
                    <td><?= $registro['servicos_nome'] ?></td>
                    <td><?= $registro['total_servicos'] ?></td>
                    <td><?= $registro['total_geral'] ?></td>
                </tr>
            <?php } ?>

        </table>
       <!-- Rodapé do relatório -->
       <p style="font-size:13px" align="right">Total insumos: R$ <?= number_format($valorInsumo, 2, ',', '.') ?></p>
       <p style="font-size:13px" align="right">Total serviços: R$ <?= number_format($valorServicos, 2, ',', '.') ?></p>
       <p style="font-size:13px" align="right">Total geral: R$ <?= number_format($valorTotal, 2, ',', '.') ?></p>
       <p style="font-size:13px" align="right">Total de Registros: <?= $servicoZ ?></p>
  </body>

  </html>

<?php
  // Captura a saída do HTML
  echo $html;
  $html = ob_get_clean();

  $dompdf = new Dompdf();

  // definir as opções
  $dompdf->setOptions($options);

  $dompdf->loadHtml($html);

  // (Optional) Setup the paper size and orientation
  $dompdf->setPaper('A4', 'landscape');

  // Define o tipo de conteúdo como PDF
  header("Content-Type: application/pdf");

  // Define o cabeçalho para exibir o PDF no navegador
  header("Content-Disposition: inline; filename=nome_do_arquivo.pdf");

  // Render the HTML as PDF
  $dompdf->render();

  // Output the generated PDF to Browser
  echo $dompdf->output();
} else {
 
  
    Util::chamarPagina('equipamento.php');
  }
  
  











