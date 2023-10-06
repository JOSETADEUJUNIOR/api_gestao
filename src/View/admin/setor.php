<?php

require_once dirname(__DIR__, 2) . '/Resource/dataview/setor_dataview.php';
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

						<li class="active">Configurações</li>
					</ul><!-- /.breadcrumb -->
				</div>
				<!-- conteudo da pagina -->
				<div class="page-content">
					<div class="row">
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->
							<div class="row">
								<div class="col-xs-12">
									<h4 class="pink">
										<a href="#setor" role="button" class="btn btn-success" data-toggle="modal"><i class="ace-icon fa fa-plus white"></i>Novo</a>
										<!-- <?php // if (count($setor) > 0) { ?>
											<button type="button" onclick="Imprimir()"class="btn btn-purple"><i class="ace-icon fa fa-plus white"></i>Relatorio</button>
										<?php // } ?> -->
									</h4>
									<div class="table-header">
										Setores Cadastrados

										<div style="display:inline-flex" id="dynamic-table_filter">
											<input type="search" onkeyup="FiltrarSetor(this.value)" class="form-control input-sm" placeholder="buscar por setor" aria-controls="dynamic-table">
										</div>
									</div>
									<div id="table_result_Setor">
										<table id="dynamic-table" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th class="sorting_desc">Nome Setor</th>
													<!-- <th class="hidden-480">Status</th> -->
													<th class="sorting_desc">Ações</th>
												</tr>
											</thead>
											<tbody>
												<?php for ($i = 0; $i < count($setor); $i++) { ?>
													<tr>
														<td>
															<?= $setor[$i]['nome_setor'] ?>
														</td>
														<!-- <td class="hidden-480">
															<span class="label label-sm label-warning">Ativo</span>
														</td> -->
														<td>
															<!-- <div class="hidden-sm hidden-xs action-buttons"> -->
																<a class="green" href="#setor" role="button" data-toggle="modal" onclick="AlterarSetorModal('<?= $setor[$i]['id'] ?>', '<?= $setor[$i]['nome_setor'] ?>')">
																	<i title="Alterar Setor" class="ace-icon fa fa-pencil bigger-130"></i>
																</a>
																
																<a class="red" href="#modalExcluir" data-toggle="modal" onclick="ExcluirModal('<?= $setor[$i]['id'] ?>', '<?= $setor[$i]['nome_setor'] ?>')">
																	<i title="Excluir Setor" class="ace-icon fa fa-trash-o bigger-130"></i>
																</a>
															<!-- </div>
															<div class="hidden-md hidden-lg">
																<div class="inline pos-rel">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li> -->
																			<!-- <a href="#setor" onclick="AlterarSetorModal('<?=  $setor[$i]['id'] ?>', '<?= $setor[$i]['nome_setor'] ?>')" data-toggle="modal" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li> -->
																		<!-- <a href="#setor" role="button" class="btn btn-info btn-xs" data-toggle="modal">Adicionar Setor</a>
																		<li> -->
																			<!-- <a href="#modalExcluir" role="button" data-toggle="modal" class="tooltip-error" title="Delete" onclick="ExcluirModal('<?= $setor[$i]['id'] ?>', '<?= $setor[$i]['nome_setor'] ?>')">
																				<span class="red">
																					<i class="ace-icon fa fa-trash-o bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div> -->
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
				
			</div>
		</div><!-- /.main-content -->
		<form id="form_setor" action="setor.php" method="post">
			<?php include_once 'modal/_setor.php' ?>
			<?php include_once 'modal/_excluir.php' ?>

		</form>
		<?php include_once PATH_URL . '/Template/_includes/_footer.php' ?>
	</div><!-- /.final do conteudo Princial -->


	<?php include_once PATH_URL . '/Template/_includes/_scripts.php' ?>
	<script src="../../Template/assets/js/bootbox.js"></script>
	<script src="../../Resource/js/mensagem.js"></script>
	<script src="../../Resource/ajax/setor-ajx.js"></script>
	
</body>


</html>