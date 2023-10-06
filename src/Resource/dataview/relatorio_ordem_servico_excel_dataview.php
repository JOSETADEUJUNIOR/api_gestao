<?php

include_once '_include_autoload.php';

use Src\_public\Util;

use Src\Controller\ChamadoController;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


  
  if (isset($_GET['filtro'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
  
    $ctrl = new ChamadoController();
    $LoteID = $_GET['lote_id'];
    $dados = $ctrl->RetornarEquipamentosLoteController($LoteID, Util::EmpresaLogado());
    $totalGeral = 0; // 
    $sheet->setCellValue('A1', 'Equipamento');
    $sheet->setCellValue('B1', 'Numero de Série');
    $sheet->setCellValue('C1', 'Lote');
    $sheet->setCellValue('D1', 'Versão');
    $sheet->setCellValue('E1', 'Insumo/Material');
    $sheet->setCellValue('F1', 'Total Insumo');
    $sheet->setCellValue('G1', 'Serviço');
    $sheet->setCellValue('H1', 'Total Serviços');
    $sheet->setCellValue('I1', 'Valor Total');
  
    $row = 2;
    foreach ($dados as $registro) {
      $sheet->setCellValue('A' . $row, $registro['descricao']);
      $sheet->setCellValue('B' . $row, $registro['numero_serie_equipamento']);
      $sheet->setCellValue('C' . $row, $registro['numero_lote']);
      $sheet->setCellValue('D' . $row, $registro['versao']);
      $sheet->setCellValue('E' . $row, $registro['insumos_nome']);
      $cellValue = number_format($registro['total_insumos'], 2, ',', '.');
      $sheet->setCellValue('F' . $row, 'R$ ' . $cellValue);
      $sheet->setCellValue('G' . $row, $registro['servicos_nome']);
      $cellValue = number_format($registro['total_servicos'], 2, ',', '.');
      $sheet->setCellValue('H' . $row, 'R$ ' . $cellValue);
    
      $cellValue = number_format($registro['total_geral'], 2, ',', '.');
      $sheet->setCellValue('I' . $row, 'R$ ' . $cellValue);
   
        // Somando ao total geral
      $totalGeral += $registro['total_geral'];
      $row++;
    }
  
    // Auto dimensionar o tamanho das colunas
    foreach (range('A', 'I') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getStyle('A1:I1')->applyFromArray([
    'font' => [
        'name' => 'Calibri',
        'size' => 11,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => '0000FF'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
]);

// Adicionar bordas às células preenchidas
$lastRow = $row - 1;
$sheet->getStyle('A2:I' . $lastRow)->applyFromArray([
    'font' => [
        'name' => 'Calibri',
        'size' => 11,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
]);

// Inserir a linha com o total geral
$row++;

$sheet->setCellValue('H' . $row, 'Total Geral:');
$sheet->setCellValue('I' . $row, 'R$ ' . number_format($totalGeral, 2, ',', '.'));
// Aplicar estilo de negrito à célula do total geral
$sheet->getStyle('H' . $row . ':I' . $row)->applyFromArray([
    'font' => [
        'bold' => true,
    ],
]);



    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="relatorio_servicos.xlsx"');
    $writer->save('php://output');
  }  else {
 
  
    Util::chamarPagina('equipamento.php');
  }
  