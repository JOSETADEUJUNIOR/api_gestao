<?php

require_once dirname(__DIR__, 2) . '/Resource/dataview/os_dataview.php';

use Src\_public\Util;
?>
<!DOCTYPE html>
<html>

<head>
	<?php include_once PATH_URL . '/Template/_includes/_head.php' ?>

	<meta name="description" content="Static &amp; Dynamic Tables" />
	<!-- include libraries(jQuery, bootstrap) -->

	<!-- include summernote css/js -->
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
	<style>
	.date-filter {
    align-items: center; /* Centralize verticalmente */
}
.btn.btn-app.btn-xs {
    width: 59%;
    font-size: 17px;
    border-radius: 8px;
    padding-bottom: 3px;
    padding-top: 5px;
    line-height: 1.45;
}
</style>
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

						<li class="active">Envio de e-mails</li>
					</ul><!-- /.breadcrumb -->
				</div>
				<!-- conteudo da pagina -->
				<div class="page-content">
					<div class="row">
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->

							<div class="row">
								<div class="col-xs-12">
									<!-- PAGE CONTENT BEGINS -->
									<div class="row">
										<div class="col-xs-12">
											<h4 class="pink">
												<a href="#sendMail" role="button" class="btn btn-purple" data-toggle="modal"><i class="ace-icon fa fa-envelope bigger-130"></i></i>Novo Email</a>

											</h4>
											<div class="table-header" style="background-color: #9585BF;">
												E-mails enviados

												<div style="display: inline-flex" id="dynamic-table_filter" class="date-filter">
													<input type="search" onkeyup="FiltrarModelo(this.value)" class="form-control input-sm" placeholder="buscar por destinatário" aria-controls="dynamic-table">
													<!-- Filtro de Data Inicial -->
													<input type="date" id="dataInicial" class="form-control input-sm" placeholder="Data Inicial" aria-controls="dynamic-table">
													<!-- Filtro de Data Final -->
													<input type="date" id="dataFinal" class="form-control input-sm" placeholder="Data Final" aria-controls="dynamic-table">
													<!-- Botão para Aplicar o Filtro -->
													<button onclick="FiltrarPorData()" class="btn btn-app btn-purple btn-xs">
														<i class="ace-icon fa fa-search bigger-110"></i>
													</button>
												</div>
											</div>

											<div id="tabela_result_email">
												<table id="dynamic-table" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th class="sorting_desc">Destinatário</th>
															<th class="hidden-480">Data do envio</th>
															<th class="hidden-480">Assunto</th>
															<th>Ações</th>
														</tr>
													</thead>
													<tbody>
														<?php for ($i = 0; $i < count(@$dadosEmail); $i++) { ?>
															<tr>
																<td>
																	<?= $dadosEmail[$i]['destinatario'] ?>
																</td>
																<td class="hidden-480">
																	<span class=""><?= ($dadosEmail[$i]['data_envio'] == "0000-00-00 00:00:00" ? "" : Util::ExibirDataBr($dadosEmail[$i]['data_envio'], 'datahora')) ?></span>
																</td>
																<td class="hidden-480">
																	<span class=""><?= $dadosEmail[$i]['assunto'] ?></span>
																</td>
																<td>
																	<!-- Botão para exibir/ocultar detalhes -->
																	<button type="button" class="btn btn-link">Mostrar Detalhes</button>
																</td>
															</tr>
															<tr class="email-details">
																<td colspan="4">
																	<!-- Conteúdo dos detalhes aqui -->

																	<div class="message-content" id="id-message-content">
																		<div class="message-header clearfix">
																			<div class="pull-left">
																				<span class="blue bigger-125">Assunto: <?= $dadosEmail[$i]['assunto'] ?> </span>

																				<div class="space-4"></div>

																				&nbsp;
																				<!-- <img class="middle" alt="John's Avatar" src="assets/images/avatars/avatar.png" width="32" /> -->
																				&nbsp;
																				<a href="#" class="sender"><?= $dadosEmail[$i]['destinatario'] ?></a>

																				&nbsp;
																				<i class="ace-icon fa fa-clock-o bigger-110 orange middle"></i>
																				<span class="time grey"><?= Util::obterDiaDaSemana($dadosEmail[$i]['data_envio']); ?>
																					<?php $dataBanco = $dadosEmail[$i]['data_envio'];
																					$timestamp = strtotime($dataBanco);
																					$horaFormatada = date('H:i', $timestamp); ?>


																					<?= $horaFormatada ?></span>
																			</div>

																			<div class="pull-right action-buttons">
																				<!-- 	
																				<a href="#">
																					<i class="ace-icon fa fa-mail-forward blue icon-only bigger-130"></i>
																				</a> -->


																			</div>
																		</div>

																		<div class="hr hr-double"></div>

																		<div class="message-body">
																			<p>
																				Mensagem: <?= $dadosEmail[$i]['mensagem'] ?></span>
																			</p>


																		</div>

																		<div class="hr hr-double"></div>
																		<?php if ($dadosEmail[$i]['anexo'] != null) { ?>

																			<div class="message-attachment clearfix">
																				<div class="attachment-title">
																					<span class="blue bolder bigger-110">Anexos</span>
																					&nbsp;
																					<span class="grey"><?= Util::bytesToMB($dadosEmail[$i]['tamanho_anexo']); ?></span>

																					<!-- <div class="inline position-relative">
																						<a href="#" data-toggle="dropdown" class="dropdown-toggle">
																							&nbsp;
																							<i class="ace-icon fa fa-caret-down bigger-125 middle"></i>
																						</a>

																						<ul class="dropdown-menu dropdown-lighter">
																							<li>
																								<a href="#">Download all as zip</a>
																							</li>

																							<li>
																								<a href="#">Display in slideshow</a>
																							</li>
																						</ul>
																					</div> -->
																				</div>

																				&nbsp;
																				<ul class="attachment-list pull-left list-unstyled">
																					<li>
																						<a href="../../Resource/dataview/<?= $dadosEmail[$i]['anexo'] ?>" target="_blank" class="attached-file">
																							<i class="ace-icon fa fa-file-o bigger-110"></i>
																							<span class="attached-name"><?= $dadosEmail[$i]['nome_anexo'] ?></span>
																						</a>

																						<span class="action-buttons">
																							<a href="../../Resource/dataview/<?= $dadosEmail[$i]['anexo'] ?>">
																								<i class="ace-icon fa fa-download bigger-125 blue"></i>
																							</a>

																							<!-- <a href="#">
																									<i class="ace-icon fa fa-trash-o bigger-125 red"></i>
																								</a> -->
																						</span>
																					</li>

																					<!-- <li>
																										<a href="#" class="attached-file">
																											<i class="ace-icon fa fa-film bigger-110"></i>
																											<span class="attached-name">Sample.mp4</span>
																										</a>

																										<span class="action-buttons">
																											<a href="#">
																												<i class="ace-icon fa fa-download bigger-125 blue"></i>
																											</a>

																											<a href="#">
																												<i class="ace-icon fa fa-trash-o bigger-125 red"></i>
																											</a>
																										</span>
																									</li> -->
																				</ul>

																				<div class="attachment-images pull-right">
																					<div class="vspace-4-sm"></div>

																					<!-- <div>
																					<img width="36" alt="image 4" src="assets/images/gallery/thumb-4.jpg" />
																					<img width="36" alt="image 3" src="assets/images/gallery/thumb-3.jpg" />
																					<img width="36" alt="image 2" src="assets/images/gallery/thumb-2.jpg" />
																					<img width="36" alt="image 1" src="assets/images/gallery/thumb-1.jpg" />
																				</div> -->
																				</div>
																			</div><?php } ?>
																	</div><!-- /.message-content -->














																</td>
															</tr>
														<?php } ?>

													</tbody>
												</table>
											</div>
										</div><!-- /.tabbable -->
									</div><!-- /.col -->
								</div><!-- /.row -->



								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.row -->
		</div><!-- /.row -->
		<form id="form_email" action="sendmail.php" method="post">
			<?php include_once 'modal/_sendMail.php' ?>
			<?php include_once 'modal/_excluir.php' ?>

		</form>
		<?php include_once PATH_URL . '/Template/_includes/_footer.php' ?>
	</div><!-- /.final do conteudo Princial -->


	<?php include_once PATH_URL . '/Template/_includes/_scripts.php' ?>
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

	<script src="../../Resource/js/mensagem.js"></script>
	<script src="../../Resource/ajax/sendmail-ajx.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#summernote').summernote();
		});
		$(document).ready(function() {
			$('#summernote').summernote({
				height: 300, // Altura do editor
				placeholder: 'Digite seu texto aqui...',
				tabsize: 2,
				height: 250,
				toolbar: [
					['style', ['bold', 'italic', 'underline', 'clear']],
					['font', ['strikethrough', 'superscript', 'subscript']],
					['fontsize', ['fontsize']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['insert', ['link', 'picture', 'video']],
					['view', ['fullscreen', 'codeview', 'help']],
				],
			});
		});



		$(document).ready(function() {
			// Esconde todos os detalhes iniciais
			$('.email-details').hide();

			// Adicione um manipulador de clique para as linhas da tabela
			$('#dynamic-table tbody tr').on('click', function() {
				// Encontre os detalhes relacionados a esta linha
				var details = $(this).next('.email-details');

				// Alternar a visibilidade dos detalhes
				details.slideToggle();
			});
		});
	</script>



</body>


</html>