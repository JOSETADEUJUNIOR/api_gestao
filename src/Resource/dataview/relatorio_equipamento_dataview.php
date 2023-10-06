<?php

include_once '_include_autoload.php';

use Src\_public\Util;
use Dompdf\Dompdf;
use Dompdf\Options;
use Src\Controller\EquipamentoController;
use Src\Controller\ProdutoController;

if (isset($_GET['filtro'])) {
  $options = new Options();
  $options->setChroot('../../Resource/dataview/arquivos');

  $html = "";
  $ctrl = new EquipamentoController();
  $ctrl_produto = new ProdutoController();
  $BuscarTipo = $_GET['filtro'];
  $filtro_palavra = $_GET['desc_filtro'];
  if ($BuscarTipo!="" && $filtro_palavra!=""){
    $equipamento = $ctrl->ConsultarEquipamentoController($BuscarTipo, $filtro_palavra);
  }else{
    $tipo="";
    $empresa_id="";
    $equipamento = $ctrl->ConsultarEquipamentoAllController($empresa_id, $tipo);
  }
  $dados_empresa = $ctrl_produto->DadosEmpresaCTRL();


  // Inicia o buffer de saída
  ob_start();
  $img = ($dados_empresa['EmpLogoPath'] == "" || $dados_empresa['EmpLogoPath'] == null ? "" :'<img src=../../Resource/dataview/' . $dados_empresa['EmpLogoPath'] . ' height="100px" width="150px" alt="Photo 2" class="img-fluid">'); ?>


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
      <th><?= $img ?>
      </th>
      <th colspan="5">
      <p><?= $dados_empresa['EmpNome'] ?></p>
        <p><?= $dados_empresa['EmpCNPJ'] ?></p>
        <p><?= $dados_empresa['EmpEnd'] ?></p>
      </th>
    </tr>
    <tr>
      <td style="text-align: center;" colspan="6"><strong>Relação de Equipamentos</strong></td>
    </tr>
    <tr>
      <td><strong>Tipo</strong></td>
      <td><strong>Modelo</strong></td>
      <td><strong>Identificação</strong></td>
      <th><strong>Insumo</strong></th>
      <th><strong>Serviço</strong></th>
      <th><strong>Descrição</strong></th>
    </tr>
    <?php $servicoZ = 0;
    for ($i = 0; $i < count($equipamento); $i++) {
      $servicoZ++; ?>
      <tr>
        <td>
          <?= $equipamento[$i]['nomeTipo'] ?>
        </td>
        <td>
          <?= $equipamento[$i]['nomeModelo'] ?>
        </td>
        <td>
          <?= $equipamento[$i]['identificacao'] ?>
        </td>
        <td>
          <?= $equipamento[$i]['nome_produto'] ?>
        </td>
        <td>
          <?= $equipamento[$i]['nome_servico'] ?>
        </td>
        <td>
          <?= $equipamento[$i]['descricao'] ?>
        </td>
      </tr>
    <?php } ?>
  </table>
  <p style="font-size:12px" align="right">Total de Registros: <?= $servicoZ ?></p>

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
