
function ConsultarEquipamentos() {
    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("equipamento_dataview"),
        data: {
            btn_consultar: 'ajx'
        }, success: function (tabela_preenchida) {
            $("#tabela_result_equipamento").html(tabela_preenchida);
        }
    })
}


function alocarEquipamento(id_form) {

    if (NotificarCampos(id_form)) {
        let equip = $("#equipamento").val();
        let setor = $("#setor").val();
        $.ajax({
            type: "POST",
            url: BASE_URL_AJAX("alocar_dataview"),
            data: {
                btn_cadastrar: 'ajx',
                equipamento: equip,
                setor: setor,
            },
            success: function (ret) {
                $("#alocado").modal("hide");
                RemoverLoad();
                if (ret == 1) {
                    MensagemSucesso();
                    ConsultarEquipamentos();
                    LimparCampos(id_form);
                } else {
                    MensagemErro();
                }
            }
        })
    }
    return false;


}
function Excluir() {
    let id = $("#ExcluirID").val();
    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("equipamento_dataview"),
        data: {
            btnExcluir: 'ajx',
            ExcluirID: id
        }, success: function (ret) {
            $("#modalExcluir").modal("hide");
            if (ret == 1) {
                MensagemSucesso();
                ConsultarEquipamentos();
            } else {
                MensagemExcluirErro();
            }
        }
    })
    return false;
}

function AlterarModelo() {
    let nome = $("#AlteraNome").val();
    let id = $("#AlteraID").val();
    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("modelo_dataview"),
        data: {
            btnAlterar: 'ajx',
            AlteraID: id,
            AlteraNome: nome
        },
        success: function (ret) {
            $("#alterarModelo").modal("hide");
            if (ret == 1) {
                MensagemSucesso();
                ConsultarModelo();
            } else {
                MensagemErro();
            }
        }
    })
    return false;
}


function CadastrarEquipamento(id_form) {

    if (NotificarCampos(id_form)) {
        $("#spinner").show();

        let modelo = $("#modelo").val();
        let tipoequip = $("#tipo").val();
        let identificacao = $("#identificacao").val();
        let descricao = $("#descricao").val();
        let ID = $("#idEquip").val();
        let servico = $("#alterar_servico").val();
        let insumo = $("#alterar_insumo").val();
        if (!Array.isArray(servico)) {
            servico = [servico];
        }
        if (!Array.isArray(insumo)) {
            insumo = [insumo];
        }
        let servicosString = servico.length > 0 ? servico.join(",") : null;
        let insumosString = insumo.length > 0 ? insumo.join(",") : null;
        $.ajax({
            type: "POST",
            url: BASE_URL_AJAX("equipamento_dataview"),
            data: {
                btn_cadastrar: 'ajx',
                modelo: modelo,
                tipoequip: tipoequip,
                identificacao: identificacao,
                descricao: descricao,
                idEquip: ID,
                IdProdutoEquipamento: insumosString,
                IdServicoEquipamento: servicosString
            },
            success: function (ret) {
                console.log(ret);
                $("#spinner").hide();
                RemoverLoad();
                if (ret == 1) {
                    $("#equipamento").modal("hide");
                    MensagemSucesso();
                    ConsultarEquipamentos();
                    LimparCampos(id_form);
                    $('#alterar_servico').val([]).trigger('change');
                    $('#alterar_insumo').val([]).trigger('change');
                } else if (ret == -5) {
                    MensagemGenericaWarning("Selecionar o serviço!");
                } else if (ret == -6) {
                    MensagemGenericaWarning("Selecionar o insumo!");
                } else {
                    $("#equipamento").modal("hide");
                    MensagemErro();
                }
            }
        })
    }
    return false;
}


function FiltrarEquipamentos(id_form) {
    if (NotificarCampos(id_form)) {
        let tipo = $("#tipoFiltro").val();
        let filtrar_palavra = $("#filtro_palavra").val();
        $.ajax({
            type: "POST",
            url: BASE_URL_AJAX("equipamento_dataview"),
            data: {
                btn_filtro: 'ajx',
                BuscarTipo: tipo,
                filtro_palavra: filtrar_palavra
            }, success: function (tabela_preenchida) {
                if (tabela_preenchida != "") {

                    $("#divResult").show();
                    $("#tabela_result_equipamento").html(tabela_preenchida);
                    $("#relatorioEquipamento").removeClass("ocultar");
                } else if (tabela_preenchida == "") {
                    $("#relatorioEquipamento").addClass("ocultar");
                    $("#tabela_result_equipamento").html('');
                    $("#divResult").hide();
                    MensagemGenerica("Nenhum dado encontrado");
                }
            }
        })
    }
    return false;
}

function Imprimir() {
    let tipo = $("#tipoFiltro").val();
    let filtrar_palavra = $("#filtro_palavra").val();
    url = "relatorio_equipamento.php?filtro=" + tipo + "&desc_filtro=" + encodeURIComponent(filtrar_palavra);
    window.open(url, "_blank");
}
function FiltrarEquipamento(nome_filtro) {

    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("equipamento_dataview"),
        data: {
            btnFiltrar: 'ajx',
            FiltrarNome: nome_filtro
        }, success: function (dados) {
            $("#tabela_result_equipamento").html(dados);
        }
    })
}

function SelecionarServicoDoEquipamentoCadastradoAjx(id_equipamento) {
    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("equipamento_dataview"),
        data: {
            btn_Servico: 'ajx',
            id_servico_equipamento_lista: id_equipamento
        }, success: function (dados) {
            if (id_equipamento != "") {
                $("#tipo_servico").show();
                $("#tipo_servico").html(dados);
            }
        }
    })
}

function SelecionarInsumoDoEquipamentoCadastradoAjx(id_equipamento) {
    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("equipamento_dataview"),
        data: {
            btn_insumo: 'ajx',
            id_produto_equipamento_lista: id_equipamento
        }, success: function (dados) {
            if (id_equipamento != "") {
                $("#tipo_insumo").show();
                $("#tipo_insumo").html(dados);
            }
        }
    })
}

function RemoverServicoEquipamentoAjx(id, id_equipamento) {
    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("equipamento_dataview"),
        data: {
            btnRemoverServico: 'ajx',
            RemoverIdServico: id
        }, success: function (ret) {
            if (ret == 1) {
                MensagemSucesso();
                SelecionarServicoDoEquipamentoCadastradoAjx(id_equipamento);
            } else {
                MensagemExcluirErro();
            }
        }
    })
    return false;
}

function RemoverInsumoEquipamentoAjx(id, id_equipamento) {

    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("equipamento_dataview"),
        data: {
            btnRemoverInsumo: 'ajx',
            RemoverIdInsumo: id
        }, success: function (ret) {
            if (ret == 1) {
                MensagemSucesso();
                SelecionarInsumoDoEquipamentoCadastradoAjx(id_equipamento);
            } else {
                MensagemExcluirErro();
            }
        }
    })
    return false;
}

function MudarStatusEquipamento(id_equipamento, valor) {
    let status_atual = valor;
    let id = id_equipamento;
    $.ajax({
        type: 'post',
        url: BASE_URL_AJAX("equipamento_dataview"),
        data: {
            id_equipamento: id,
            status_equipamento: status_atual,
            mudar_status: 'ajx'
        },
        success: function (resultado) {
            if (resultado == 1) {
                MensagemSucesso();
            } else {
                MensagemErro();
            }
        }
    })
}
