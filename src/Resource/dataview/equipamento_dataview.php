<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use Src\_public\Util;
Util::VerificarLogado();
use Src\Controller\EquipamentoController;
use Src\VO\EquipamentoVO;
use Src\Controller\TipoEquipamentoController;
use Src\Controller\ModeloController;


$id = '';
$equipamento = [];
$ctrlTipo = new TipoEquipamentoController();
$ctrlModelo = new ModeloController();
$ctrl = new EquipamentoController();

$tipos = $ctrlTipo->RetornarTiposEquipamentosController();
$modelo = $ctrlModelo->RetornaModeloController();
$produto = $ctrl->SelecionarProdutoEquipamentoController();
$servico = $ctrl->SelecionarServicoEquipamentoController();


if (isset($_POST['btn_cadastrar']) && $_POST['btn_cadastrar'] == 'ajx') {
    $vo = new EquipamentoVO;
    $vo->setId($_POST['idEquip']);
    $vo->setIdentificacao($_POST['identificacao']);
    $vo->setDescricao($_POST['descricao']);
    $vo->setTipoEquipID($_POST['tipoequip']);
    $vo->setModeloEquipID($_POST['modelo']);
    $vo->setIdProdutoEquipamento($_POST['IdProdutoEquipamento']);
    $vo->setIdServicoEquipamento($_POST['IdServicoEquipamento']);
    $tem_produto = "S"; $tem_servico = "S"; $ret = "";
    if ($_POST['idEquip']==""){
        if ($_POST['IdServicoEquipamento']==""&&$_POST['idEquip']==""){
            $tem_servico = "N";
        }
        if ($_POST['IdProdutoEquipamento']==""&&$_POST['idEquip']==""){
            $tem_produto = "N";
        }
        if ($tem_produto == "S" && $tem_servico == "S"){
            $ret = $ctrl->CadastrarEquipamentoController($vo);
        }
    }else{
        if ($_POST['IdProdutoEquipamento']!="" && $_POST['idEquip']!=""){
            $ret = $ctrl->CadastrarInsumoEquipamentoController($vo);
        }
        if ($_POST['IdServicoEquipamento']!="" && $_POST['idEquip']!=""){
            $ret = $ctrl->CadastrarServicoEquipamentoController($vo);
        }
        $ret = $ctrl->AlterarEquipamentoController($vo);
    }
    if ($tem_produto == "S" && $tem_servico == "S") {
        echo $ret;
    }elseif ($tem_servico == "N"){ 
        echo -5;
    }elseif ($tem_produto == "N"){ 
        echo -6;
    }else {
        echo -1;
    }
} else if (isset($_POST['mudar_status']) && $_POST['mudar_status'] == 'ajx') {

    $vo =  new EquipamentoVO;

    $vo->setId($_POST['id_equipamento']);
    $vo->setStatusEquipamento($_POST['status_equipamento']);
    echo $ctrl->AlterarStatusEquipamentoCTRL($vo);

} else if (isset($_POST['btnExcluir'])) {
    $vo = new EquipamentoVO;
    $vo->setID($_POST['ExcluirID']);
    $ret = $ctrl->ExcluirEquipamentoController($vo);

    if ($_POST['btnExcluir'] == 'ajx') {
        echo $ret;
    }
} else if (isset($_POST['btnRemoverServico']) && $_POST['btnRemoverServico']) {
    $vo = new EquipamentoVO;
    $vo->setId($_POST['RemoverIdServico']);
    $ret = $ctrl->RemoverServicoEquipamentoController($vo);
    echo $ret;
    
} else if (isset($_POST['btnRemoverInsumo']) && $_POST['btnRemoverInsumo'] == 'ajx') {
    $vo = new EquipamentoVO;
    $vo->setId($_POST['RemoverIdInsumo']);
    $ret = $ctrl->RemoverProdutoEquipamentoController($vo);
    echo $ret;
    
} else if (isset($_POST['btn_filtro']) && $_POST['btn_filtro'] == 'ajx') {
    $BuscarTipo = $_POST['BuscarTipo'];
    $filtro_palavra = $_POST['filtro_palavra'];
    $equipamento = $ctrl->ConsultarEquipamentoController($BuscarTipo, $filtro_palavra); 
    if (count($equipamento) > 0) { ?>
        <div class="table-responsive" id="tabela_result_equipamento">
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
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
                    <?php for ($i = 0; $i < count($equipamento); $i++) { ?>
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
                                    <a class="green" href="#equipamento" role="button" data-toggle="modal" onclick="AlterarEquipamentoModal('<?= $equipamento[$i]['idEquip'] ??'' ?>', '<?= $equipamento[$i]['idTipo'] ??'' ?>', '<?= $equipamento[$i]['idModelo'] ??'' ?>', '<?= $equipamento[$i]['identificacao'] ??'' ?>', '<?= $equipamento[$i]['descricao'] ??'' ?>')">
                                        <i title="Alterar Setor" class="ace-icon fa fa-pencil bigger-130"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php }
} else if (isset($_POST['btn_Servico']) && $_POST['btn_Servico'] == 'ajx' && isset($_POST['id_servico_equipamento_lista'])) {

    $id_servico_equipamento_lista = $_POST['id_servico_equipamento_lista'];

    $servico_equipamento = $ctrl->ListaServicoDoEquipamentoController($id_servico_equipamento_lista);
    
    if (count($servico_equipamento) > 0) { ?>
        <div class="table-responsive" id="tipo_servico">
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="sorting_desc">Serviço</th>
                        <th class="sorting_desc">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($servico_equipamento); $i++) { ?>
                        <tr>
                            <td>
                                <?= $servico_equipamento[$i]['ServNome'] ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a class="red" role="button" onclick="RemoverServicoEquipamentoAjx('<?= $servico_equipamento[$i]['id'] ?>','<?= $servico_equipamento[$i]['equipamento_id'] ?>')">
                                        <i title="Excluir Serviço" class="ace-icon fa fa-trash-o bigger-130"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
    } else {
        echo '<h4><center>Nenhum registro encontrado!</center><h4>';
    }
} else if (isset($_POST['btn_insumo']) && $_POST['btn_insumo'] == 'ajx' && isset($_POST['id_produto_equipamento_lista'])) {

    $id_produto_equipamento_lista = $_POST['id_produto_equipamento_lista'];

    $produto_equipamento = $ctrl->ListaProdutoDoEquipamentoController($id_produto_equipamento_lista);
    
    if (count($produto_equipamento) > 0) { ?>
        <div class="table-responsive" id="tipo_insumo">
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="sorting_desc">Produto</th>
                        <th class="sorting_desc">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($produto_equipamento); $i++) { ?>
                        <tr>
                            <td>
                                <?= $produto_equipamento[$i]['ProdDescricao'] ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a class="red" role="button" onclick="RemoverInsumoEquipamentoAjx('<?= $produto_equipamento[$i]['id'] ?>','<?= $produto_equipamento[$i]['equipamento_id'] ?>')">
                                        <i title="Excluir Insumo" class="ace-icon fa fa-trash-o bigger-130"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
    } else {
        echo '<h4><center>Nenhum registro encontrado!</center><h4>';
    }
} else if (isset($_POST['btnFiltrar']) && isset($_POST['FiltrarNome'])) {

    $nome_filtro = $_POST['FiltrarNome'];

    $equipamento = $ctrl->FiltrarEquipamentoController($nome_filtro);
    

    if (count($equipamento) > 0) { ?>
        <div  class="table-responsive" id="tabela_result_equipamento">
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
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
                    <?php for ($i = 0; $i < count($equipamento); $i++) { ?>
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
                                    <a class="green" href="#equipamento" role="button" data-toggle="modal" onclick="AlterarEquipamentoModal('<?= $equipamento[$i]['idEquip'] ??'' ?>', '<?= $equipamento[$i]['idTipo'] ??'' ?>', '<?= $equipamento[$i]['idModelo'] ??'' ?>', '<?= $equipamento[$i]['identificacao'] ??'' ?>', '<?= $equipamento[$i]['descricao'] ??'' ?>')">
                                        <i title="Alterar Setor" class="ace-icon fa fa-pencil bigger-130"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
    } else {
        echo '<h4><center>Nenhum registro encontrado!</center><h4>';
    }
} else if (isset($_POST['btn_consultar']) && $_POST['btn_consultar'] == 'ajx') {
    $tipo = "";
    $empresa_id="";
    $equipamento = $ctrl->ConsultarEquipamentoAllController($empresa_id,$tipo);

    if (count($equipamento) > 0) { ?>

        <div class="table-responsive" id="tabela_result_equipamento">
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
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
                    <?php for ($i = 0; $i < count($equipamento); $i++) { ?>
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
                                    <a class="green" href="#equipamento" role="button" data-toggle="modal" onclick="AlterarEquipamentoModal('<?= $equipamento[$i]['idEquip'] ??'' ?>', '<?= $equipamento[$i]['idTipo'] ??'' ?>', '<?= $equipamento[$i]['idModelo'] ??'' ?>', '<?= $equipamento[$i]['identificacao'] ??'' ?>', '<?= $equipamento[$i]['descricao'] ??'' ?>')">
                                        <i title="Alterar Setor" class="ace-icon fa fa-pencil bigger-130"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
<?php } else {
        echo '<h4><center>Nenhum registro encontrado!</center><h4>';
    }
} else {
    $tipo="";
    $empresa_id="";
    $equipamento = $ctrl->ConsultarEquipamentoAllController($empresa_id, $tipo);
} ?>