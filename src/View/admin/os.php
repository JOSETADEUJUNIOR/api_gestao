<?php

require_once dirname(__DIR__, 2) . '/Resource/dataview/os_dataview.php';

use Src\_public\Util;
?>
<!DOCTYPE html>
<html>

<head>
	<?php include_once PATH_URL . '/Template/_includes/_head.php' ?>
	<meta name="description" content="Static &amp; Dynamic Tables" />
</head>

<body class="skin-1">
	<?php include_once PATH_URL . '/Template/_includes/_topo.php' ?>
	<!-- topo-->


	<!--inicio do conteudo principal-->
	<div class="main-container ace-save-state" id="main-container">
		<script type="text/javascript">
			try {
				ace.settings.loadState('main-container')
			} catch (e) {}
		</script>

		<?php include_once PATH_URL . '/Template/_includes/_menu.php' ?>

		<div class="main-content">
			<div class="main-content-inner">
				<div class="breadcrumbs ace-save-state" id="breadcrumbs">
					<ul class="breadcrumb">
						<li>
							<i class="ace-icon fa fa-home home-icon"></i>
							<a href="#">Home</a>
						</li>

						<li class="active">Ordem de serviço</li>
					</ul><!-- /.breadcrumb -->
				</div>
				<!-- conteudo da pagina -->
				<div class="page-content">
					<div class="row">
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->

							<div class="row">
								<div class="col-xs-12">
									<!-- <h4 class="pink">
										<a href="#ordem" role="button" class="btn btn-success" data-toggle="modal"><i class="ace-icon fa fa-plus white"></i>Novo</a>
									</h4> -->
									<div class="table-header">
										Buscar ordem de serviço

										<div style="display:inline-flex" id="dynamic-table_filter">
											<input type="search" onkeyup="FiltrarOs(this.value)" class="form-control input-sm" placeholder="buscar por NF" aria-controls="dynamic-table">
										</div>
										<label style="margin-left:10px">
											<input name="form-field-radio" id="buscarEmAberto" onclick="FiltrarChamado('4')" type="radio" class="ace" checked>
											<span class="lbl">Todos</span>
										</label>
										<label style="margin-left:10px">
											<input name="form-field-radio" id="buscarEmAberto" onclick="FiltrarChamado('1')" type="radio" class="ace">
											<span class="lbl">Em aberto</span>
										</label>
										<label style="margin-left:10px">
											<input name="form-field-radio" id="buscarEmAndamento" onclick="FiltrarChamado('2')" type="radio" class="ace">
											<span class="lbl">Em andamento</span>
										</label>
										
										<label style="margin-left:10px">
											<input name="form-field-radio" id="buscarConcluida" onclick="FiltrarChamado('3')" type="radio" class="ace">
											<span class="lbl"> Concluídas</span>
										</label>
									</div>
									<div id="tabela_result_os">
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>NF</th>
													<th>Dt Inicio</th>
													<th>Dt Entrega</th>
													<th>Tecnico</th>
													<th>Cliente</th>
													<th>Status</th>
													<th>Ação</th>
												</tr>
											</thead>
											<tbody>
												<?php for ($i = 0; $i < count($ch); $i++) { ?>
													<tr>
														<td>
															<?= $ch[$i]['numero_nf'] ?>
														</td>
														<td>
															<?= $ch[$i]['data_abertura'] ?>
														</td>
														<td>
															<?= $ch[$i]['data_encerramento'] ?>
														</td>
														<td>
															<?= $ch[$i]['tecnico_encerramento'] ?>
														</td>
														<td>
															<?= $ch[$i]['nome_cliente'] ?>
														</td>
														<td class="hidden-480">
															<?php if ($ch[$i]['data_atendimento']=="") { ?>
																<span class="label label-sm label-purple">Aguardando atendimento</span>
																
															<?php }elseif ($ch[$i]['data_atendimento']!="" && $ch[$i]['data_encerramento']==""){?>
																<span class="label label-sm label-warning">Em atendimento</span>
															<?php }else{ ?>
																	<span class="label label-sm label-warning">Encerrado</span>
															<?php } ?>
														</td>
														<td>
															<div class="hidden-sm hidden-xs action-buttons">
																<a class="green" href="#itens-os" role="button" data-toggle="modal" onclick="VerItens('<?=$ch[$i]['LoteID'] ?>')">
																	<i title="Itens da os" class="ace-icon fa fa-list bigger-130"></i>
																</a>
																<a href="#" id="busca_nf" type="button" onclick="Imprimir('<?=$ch[$i]['LoteID'] ?>')" class="red"><i class="ace-icon fa fa-file-pdf-o bigger-130"></i></a>
																<a href="#" id="busca_nf" type="button" onclick="ImprimirExcel('<?=$ch[$i]['LoteID'] ?>')" class="green"><i class="ace-icon fa fa-file-excel-o bigger-130"></i></a>
															</div>


															<?php #table_data += '<a id="print-button" href="#" onclick="PrintLote(\'' + this.id+ '\')" class="green"><i title="Imprir os dados do lote" class="ace-icon fa fa-file-excel-o bigger-130"></i></a>';?>
                        



															<div class="hidden-md hidden-lg">
																<div class="inline pos-rel">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">

																		<a href="#setor" role="button" class="btn btn-info btn-xs" data-toggle="modal">Adicionar Setor</a>
																		<li>
																			<a href="#modalExcluir" role="button" data-toggle="modal" class="tooltip-error" title="Delete" onclick="ExcluirModal('<?=$ch[$i]['id'] ?>')">
																				<span class="red">
																					<i class="ace-icon fa fa-trash-o bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</td>
													</tr>
												<?php } ?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.final do conteudo da pagina -->
				<div id="divload">

				</div>
			</div>
		</div><!-- /.main-content -->
		<form id="form_os" action="os.php" method="post">
			<?php include_once 'modal/_os.php' ?>
			<?php include_once 'modal/_excluir.php' ?>
			<?php include_once 'modal/_itens_os.php' ?>
			<?php include_once 'modal/_excluirServ.php' ?>
			<?php include_once 'modal/_excluirItem.php' ?>

		</form>


		<?php include_once PATH_URL . '/Template/_includes/_footer.php' ?>
	</div><!-- /.final do conteudo Princial -->

	<?php include_once PATH_URL . '/Template/_includes/_scripts.php' ?>
	<script src="../../Resource/js/mensagem.js"></script>
	<script src="../../Resource/ajax/os-ajx.js"></script>
</body>


</html>