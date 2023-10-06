<?php

require_once dirname(__DIR__, 2) . '/Resource/dataview/equipamento_dataview.php';
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

						<li class="active">Equipamento</li>
					</ul><!-- /.breadcrumb -->
				</div>
				<!-- conteudo da pagina -->
				<div class="page-content">
					<div class="row">
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->

							<div class="row">
								<div class="col-sm-12">
									<h4 class="pink">
										<a href="#equipamento" role="button" class="btn btn-success" data-toggle="modal"><i class="ace-icon fa fa-plus white"></i>Novo</a>
										<?php if (count($equipamento) > 0) { ?>
											<button id="btnImprimirEquip" type="button" onclick="Imprimir()" class="btn btn-purple"><i class="ace-icon fa fa-plus white"></i>Relatorio</button>
										<?php } ?>
									</h4>
								</div>
							</div>
							<div class="row">
								<form id="form_consultaEquip">
									<div class="form-group col-md-6">
										<label>Filtrar Por:</label>
										<select class="form-control obg" id="tipoFiltro" name="tipoFiltro">
											<option value="">Selecione</option>
											<option value="<?= FILTRO_TIPO ?>">TIPO</option>
											<option value="<?= FILTRO_MODELO ?>">MODELO</option>
											<option value="<?= FILTRO_IDENTIFICACAO ?>">IDENTIFICACAO</option>
											<option value="<?= FILTRO_DESCRICAO ?>">DESCRICAO</option>

										</select>
									</div>
									<div class="form-group col-md-6">
										<label>Escolha o filtro</label>
										<input id="filtro_palavra" name="filtro_palavra" class="form-control obg">
									</div>
									<div class="col-md-12 text-center">
										<button name="btn_consultar" id="btn_consultar" class="btn btn-primary btn-xs" onclick=" return FiltrarEquipamentos('form_consultaEquip')">Pesquisar</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="row" id="divResult">
						<div class="col-sm-12">
							<div class="table-header" style="margin-top: 10px;">
								Equipamentos Cadastrados

							</div>

							<div class="table-responsive" id="tabela_result_equipamento">
								<table id="dynamic-table" style="max-width: 100%;" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th class="sorting_desc">Tipo</th>
											<th class="sorting_desc">Modelo</th>
											<th class="sorting_desc">Identificação</th>
											<th class="sorting_desc">Insumo</th>
											<th class="sorting_desc">Serviço</th>
											<th class="sorting_desc">Descrição</th>
											<th class="sorting_desc">Ativo/Inativo</th>
											<th class="sorting_desc">Ações</th>
										</tr>
									</thead>
									<tbody>
										<?php
										for ($i = 0; $i < count($equipamento); $i++) { ?>
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
												<td>
													<div class="col-xs-3">
														<label>
															<input name="switch-field-1" value="0" id="status_equipamento" onclick="MudarStatusEquipamento('<?= $equipamento[$i]['idEquip'] ?>', '<?= $equipamento[$i]['status_equipamento'] ?>')" title="Ativar/Inativar Equipamento" class="ace ace-switch ace-switch-6" <?= $equipamento[$i]['status_equipamento'] == STATUS_ATIVO ? "checked='checked'" : ''  ?> type="checkbox" />
															<span class="lbl"></span>
														</label>
													</div>
												</td>
												<td>
													<div class="action-buttons">
														<a class="green" href="#equipamento" role="button" data-toggle="modal" onclick="AlterarEquipamentoModal('<?= $equipamento[$i]['idEquip'] ?>', '<?= $equipamento[$i]['idTipo'] ?>', '<?= $equipamento[$i]['idModelo'] ?>', '<?= $equipamento[$i]['identificacao'] ?>', '<?= $equipamento[$i]['descricao'] ?>')">
															<i title="Alterar Setor" class="ace-icon fa fa-pencil bigger-130"></i>
														</a>
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
			<!-- /.final do conteudo da pagina -->
			<div id="spinner" style="display: none;">
  <i class="fas fa-spinner fa-spin"></i> Aguarde...
</div>

		</div><!-- /.main-content -->
		<form id="form_equipamento" action="equipamento.php" method="post">
			<?php include_once 'modal/_equipamento.php' ?>
			<?php include_once 'modal/_excluir.php' ?>
		</form>


		<?php include_once PATH_URL . '/Template/_includes/_footer.php' ?>
	</div><!-- /.final do conteudo Princial -->

	<?php include_once PATH_URL . '/Template/_includes/_scripts.php' ?>
	<script src="../../Template/assets/js/bootbox.js"></script>
	<script src="../../Resource/js/mensagem.js"></script>
	<script src="../../Resource/ajax/equipamento-ajx.js"></script>
	<script>
		$('#alterar_servico').select2({
			placeholder: '+Serviço....',
			dropdownParent: $('#equipamento')
		});
		$('#alterar_insumo').select2({
			placeholder: '+Insumo....',
			dropdownParent: $('#equipamento')
		});
		$('#alterar_insumo').next('.select2-container').css('width', '96%');
		$('#alterar_servico').next('.select2-container').css('width', '96%');
		var label = $('#alterarLabelInsumo');
		label.css('margin-left', '10px');
		var label = $('#alterarLabelServico');
		// Ajustar o deslocamento da label
		label.css('margin-left', '10px');
	</script>
</body>


</html>