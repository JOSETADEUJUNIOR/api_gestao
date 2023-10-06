<?php

include_once '_include_autoload.php';

use Src\_public\Util;
use Dompdf\Dompdf;
use Dompdf\Options;
use Src\Controller\ServicoController;
use Src\Controller\TipoEquipamentoController;

if (isset($_GET['filtro'])) {
  $options = new Options();
  $options->setChroot('../../Resource/dataview/arquivos');

  $html = "";
  $ctrl = new ServicoController();
  $filtro_palavra = $_GET['filtro'];
  $servico = $ctrl->ConsultarServicoController($filtro_palavra);
  $empresa = $ctrl->DadosEmpresaCTRL();

  // Inicia o buffer de saída
  ob_start();
  $img = ($empresa['EmpLogoPath'] == "" || $empresa['EmpLogoPath'] == null ? "" : '<img src=../../Resource/dataview/' . $empresa['EmpLogoPath'] . ' height="100px" width="150px" alt="Photo 2" class="img-fluid">'); ?>

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
    <th><?= $img ?></th>
    <th colspan="2">
      <p><?= $empresa['EmpNome'] ?></p>
      <p><?= $empresa['EmpCNPJ'] ?></p>
      <p><?= $empresa['EmpEnd'] ?></p>
    </th>
  </tr>
  <tr>
    <td style="text-align: center;" colspan="3">
      <center><strong>Relação de Serviços</strong></center>
    </td>
  </tr>
  <tr>
    <td><strong>Nome</strong></td>
    <td><strong>Descrição</strong></td>
    <td><strong>Valor</strong></td>
  </tr>
  <?php
  $servicoZ = 0;
  for ($i = 0; $i < count($servico); $i++) {
    $servicoZ++;
  ?>
    <tr>
      <td><?= $servico[$i]['ServNome'] ?></td>
      <td><?= $servico[$i]['ServDescricao'] ?></td>
      <td><?= $servico[$i]['ServValor'] ?></td>
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
  Util::chamarPagina('servico.php');
}
