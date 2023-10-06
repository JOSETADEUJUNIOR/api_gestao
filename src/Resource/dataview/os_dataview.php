<?php
include_once '_include_autoload.php';

use Src\Controller\OsController;
use Src\Controller\ClienteController;
use Src\Controller\ProdutoController;
use Src\Controller\ServicoController;
use Src\Controller\UsuarioController;
use Src\Controller\SendMailController;
use Src\VO\OsVO;
use Src\_public\Util;
use Src\Controller\ChamadoController;
use Src\mail\ModeloOrdemServico;
use Src\VO\ServicoOSVO;
use Src\VO\ProdutoOSVO;
use Src\VO\AnxOSVO;
use Src\VO\ChamadoVO;
use Src\mail\ModeloUsuario;
use Src\VO\SendMailVO;

Util::VerificarLogado();
$ordemOS = 0;
$OsID = 0;
$ctrlEmp = new UsuarioController();
$ctr_chamado = new ChamadoController();
$ch = $ctr_chamado->FiltrarChamadoGeralAdminController($tipo = 4);


$dadosEmp = $ctrlEmp->RetornarDadosCadastraisController();
$cliCtrl = new ClienteController();
$ctrlProd = new ProdutoController();
$ctrlServ = new ServicoController();
$servicos = $ctrlServ->RetornarServicoController();
$produtos = $ctrlProd->SelecioneProdutoCTRL();
$clientes = $cliCtrl->SelecioneClienteCTRL();
$ctrl = new OsController();
$vo = new OsVO;
$ProdOrdem = $ctrl->RetornaProdOrdem($vo);

$dadosOS = $ctrl->RetornarDadosOsController();

if (isset($_POST['EnviarEmail']) && $_POST['EnviarEmail'] == 'ajx') {
	$mailSender = new SendMailController();
	$email = $_POST['destinatario'];
	$assunto = $_POST['assunto'];
	$mensagem = $_POST['mensagem'];
	if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['anexo']['tmp_name'])) {
		// Código para processar o anexo
		$arquivos = $_FILES['anexo'];
		$pasta = CAMINHO_PARA_SALVAR_IMG_PRODUTO;
		@mkdir($pasta);
		$nomeDoArquivo = $arquivos['name'];
		$tamanhoArquivo = $arquivos['size'];
		$novoNomeDoArquivo = uniqid();
		$extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
		$path = $pasta . $novoNomeDoArquivo . "." . $extensao;
		$deu_certo = move_uploaded_file($arquivos["tmp_name"], $path);
	}
	$vo = new SendMailVO;
	$vo->setDestinatario($email);
	$vo->setAssunto($assunto);
	$vo->setMensagem($mensagem);
	$vo->setAnexo($path);
	$vo->setNome_anexo($nomeDoArquivo);
	$vo->setTamanho_arquivo($tamanhoArquivo);
	$dadosEmail = $ctrl->GravarDadosEmailController($vo);
	$body = (new ModeloOrdemServico)->EmailUser($email, $assunto, $mensagem);

	// Verifique se um anexo foi enviado


	$envio = $mailSender->sendEmail($email, $assunto, $body, $path, $novoNomeDoArquivo);

	if ($envio) {
		echo 1;
	} else {
		echo -1;
	}
} else if (isset($_POST['btn_consultarData']) && $_POST['btn_consultarData'] == 'ajx') {

	if (isset($_POST['dataInicial']) && isset($_POST['dataFinal'])) {
		$dataInicial = $_POST['dataInicial'];
		$dataFinal = $_POST['dataFinal'];
	}

	$dadosEmail = $ctrl->RetornarDadosEmailController($dataInicial, $dataFinal);
  if (count($dadosEmail) > 0) { ?>
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

	<script>
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
 <?php } else {
        echo '<h4><center>Nenhum registro encontrado!</center><h4>';
    }


} else if (isset($_POST['btn_consultarEmail']) && $_POST['btn_consultarEmail'] == 'ajx') {

	$dadosEmail =  $ctrl->RetornarDadosEmailController(); ?>


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
				<?php for ($i = 0; $i < count($dadosEmail); $i++) { ?>
					<tr>
						<td>
							<?= $dadosEmail[$i]['destinatario'] ?>
						</td>
						<td class="hidden-480">
							<span class="label label-sm label-warning"><?= $dadosEmail[$i]['data_envio'] ?></span>
						</td>
						<td class="hidden-480">
							<span class="label label-sm label-warning"><?= $dadosEmail[$i]['assunto'] ?></span>
						</td>
						<td>
							<div class="hidden-sm hidden-xs action-buttons">
								<a class="green" href="#modelo" role="button" data-toggle="modal" onclick="AlterarModeloModal('<?= $$dadosEmail[$i]['id'] ?>', '<?= $modelo[$i]['nome'] ?>')">
									<i title="Alterar Tipo Equipamento" class="ace-icon fa fa-pencil bigger-130"></i>
								</a>
								<a class="red" href="#modalExcluir" data-toggle="modal" onclick="ExcluirModal('<?= $$dadosEmail[$i]['id'] ?>', '<?= $modelo[$i]['nome'] ?>')">
									<i title="Excluir Tipo Equipamento" class="ace-icon fa fa-trash-o bigger-130"></i>
								</a>
							</div>

						</td>
					</tr>
				<?php } ?>

			</tbody>
		</table>
	</div>



<?php } else if (isset($_POST['btn_consultar_os']) && $_POST['btn_consultar_os'] == 'ajx') {


	$vo = new ChamadoVO;
	$vo->setLoteID($_POST['OsID']);
	$lotes = $ctr_chamado->RetornarEquipamentosLoteControllerAdmin($vo); 
	?>
	 <div class="table-responsive">
        <table id="dynamic-table" class="table table-striped table-bordered table-hover ">
            <thead>
                <tr>
					<th>#</th>
                    <th>Lote</th>
                    <th>Equipamento</th>
                    <th>numero de serie</th>
                    <th>Versão</th>
                    <th>Insumos/Materiais</th>
                    <th>Valor dos materiais/insumos</th>
                    <th>Serviços</th>
                    <th>Valor dos serviços</th>
                    <th>Valor Total</th>

                </tr>
            </thead>
            <tbody>
                <?php $subTotal = 0;
                for ($i = 0; $i < count($lotes); $i++) {
                    if ($lotes[$i]['lote_id'] != '') {
                        $subTotal = $subTotal + $lotes[$i]['total_geral']  ?>
                        <tr>
							<td><?= $i + 1 ?></td>
                            <td><?= $lotes[$i]['lote_id'] ?></td>
                            <td><?= $lotes[$i]['descricao'] ?></td>
                            <td><?= $lotes[$i]['numero_serie_equipamento'] ?></td>
                            <td><?= $lotes[$i]['versao'] ?></td>
                            <td><?= $lotes[$i]['insumos_nome'] ?></td>
                            <td><?= $lotes[$i]['total_insumos'] ?></td>
                            <td><?= $lotes[$i]['servicos_nome'] ?></td>
                            <td><?= $lotes[$i]['total_servicos'] ?></td>
                            <td><?= $lotes[$i]['total_geral'] ?></td>


                        </tr>
                <?php }
                } ?>

            </tbody>
            </hr>
            <tbody>
                <tr style="background-color: #FFA500;">
                    <td colspan="9"><span><strong>Total gasto nos equpamentos: </strong></span></td>
                    <td colspan="1"><strong><?= 'R$: ' . Util::FormatarValor($subTotal) ?></strong></td>

                </tr>
            </tbody>
        </table>
    </div>

<?php } else if (isset($_POST['btnSituacao'])) {


	$filtro = $_POST['situacao'];

	$ch = $ctr_chamado->FiltrarChamadoGeralController($_SESSION['UserEmpID'], $filtro, $setor = '');
?>
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
							<?php if ($ch[$i]['data_atendimento'] == "") { ?>
								<span class="label label-sm label-purple">Aguardando atendimento</span>

							<?php } elseif ($ch[$i]['data_atendimento'] != "" && $ch[$i]['data_encerramento'] == "") { ?>
								<span class="label label-sm label-warning">Em atendimento</span>
							<?php } else { ?>
								<span class="label label-sm label-warning">Encerrado</span>
							<?php } ?>
						</td>
						<td>
							<div class="hidden-sm hidden-xs action-buttons">
								<a class="green" href="#itens-os" role="button" data-toggle="modal" onclick="VerItens('<?= $ch[$i]['LoteID'] ?>')">
									<i title="Itens da os" class="ace-icon fa fa-list bigger-130"></i>
								</a>
								<a href="#" id="busca_nf" type="button" onclick="Imprimir('<?= $ch[$i]['LoteID'] ?>')" class="red"><i class="ace-icon fa fa-file-pdf-o bigger-130"></i></a>
								<a href="#" id="busca_nf" type="button" onclick="ImprimirExcel('<?= $ch[$i]['LoteID'] ?>')" class="green"><i class="ace-icon fa fa-file-excel-o bigger-130"></i></a>
							</div>


							<?php #table_data += '<a id="print-button" href="#" onclick="PrintLote(\'' + this.id+ '\')" class="green"><i title="Imprir os dados do lote" class="ace-icon fa fa-file-excel-o bigger-130"></i></a>';
							?>




							<div class="hidden-md hidden-lg">
								<div class="inline pos-rel">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
										<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
									</button>

									<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">

										<a href="#setor" role="button" class="btn btn-info btn-xs" data-toggle="modal">Adicionar Setor</a>
										<li>
											<a href="#modalExcluir" role="button" data-toggle="modal" class="tooltip-error" title="Delete" onclick="ExcluirModal('<?= $ch[$i]['id'] ?>')">
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




<?php } else if (isset($_POST['btnFiltrar'])) {
	$filtro = $_POST['FiltrarNome'];

	$ch = $ctr_chamado->FiltrarNFController($_SESSION['UserEmpID'], $filtro);
?>
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
							<?php if ($ch[$i]['data_atendimento'] == "") { ?>
								<span class="label label-sm label-purple">Aguardando atendimento</span>

							<?php } elseif ($ch[$i]['data_atendimento'] != "" && $ch[$i]['data_encerramento'] == "") { ?>
								<span class="label label-sm label-warning">Em atendimento</span>
							<?php } else { ?>
								<span class="label label-sm label-warning">Encerrado</span>
							<?php } ?>
						</td>
						<td>
							<div class="hidden-sm hidden-xs action-buttons">
								<a class="green" href="#itens-os" role="button" data-toggle="modal" onclick="VerItens('<?= $ch[$i]['LoteID'] ?>')">
									<i title="Itens da os" class="ace-icon fa fa-list bigger-130"></i>
								</a>
								<a href="#" id="busca_nf" type="button" onclick="Imprimir('<?= $ch[$i]['LoteID'] ?>')" class="red"><i class="ace-icon fa fa-file-pdf-o bigger-130"></i></a>
								<a href="#" id="busca_nf" type="button" onclick="ImprimirExcel('<?= $ch[$i]['LoteID'] ?>')" class="green"><i class="ace-icon fa fa-file-excel-o bigger-130"></i></a>
							</div>


							<?php #table_data += '<a id="print-button" href="#" onclick="PrintLote(\'' + this.id+ '\')" class="green"><i title="Imprir os dados do lote" class="ace-icon fa fa-file-excel-o bigger-130"></i></a>';
							?>




							<div class="hidden-md hidden-lg">
								<div class="inline pos-rel">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
										<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
									</button>

									<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">

										<a href="#setor" role="button" class="btn btn-info btn-xs" data-toggle="modal">Adicionar Setor</a>
										<li>
											<a href="#modalExcluir" role="button" data-toggle="modal" class="tooltip-error" title="Delete" onclick="ExcluirModal('<?= $ch[$i]['id'] ?>')">
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


<?php } else {

	$os = $ctrl->RetornarOsController();
	$dadosEmail =  $ctrl->RetornarDadosEmailController();
	#var_dump($dadosEmail);
}

?>