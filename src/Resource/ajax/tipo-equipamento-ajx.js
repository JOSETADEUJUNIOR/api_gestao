function ConsultarTipo() {
    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("tipoequipamento_dataview"),
        data: {
            btnConsultar: 'ajx'
        }, success: function (tabela_preenchida) {
            $("#tabela_result_tipo").html(tabela_preenchida);
        }
    })
}

function Excluir() {
    let id = $("#ExcluirID").val();
    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("tipoequipamento_dataview"),
        data: {
            btnExcluir: 'ajx',
            ExcluirID: id
        }, success: function (ret) {
            $("#modalExcluir").modal("hide");
            if (ret == 1) {
                MensagemSucesso();
                ConsultarTipo();
            } else {
                MensagemExcluirErro();
            }
        }
    })
    return false;
}

function AlterarTipo() {
    let nome = $("#AlteraNome").val();
    let id = $("#AlteraID").val();

    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("tipoequipamento_dataview"),
        data: {
            btnAlterar: 'ajx',
            AlteraID: id,
            AlteraNome: nome
        }, success: function (ret) {
            $("#alterarTipoEquipamento").modal("hide");
            if (ret == 1) {
                MensagemSucesso();
                ConsultarTipo();
            } else {
                MensagemErro();
            }

        }
    })

    return false;

}

function CadastrarTipo(id_form) {

    if (NotificarCampos(id_form)) {
        let id = $("#AlteraID").val();
        let nome_tipo = $("#nome").val();
        $.ajax({
            type: "POST",
            url: BASE_URL_AJAX("tipoequipamento_dataview"),
            data: {
                btn_cadastrar: 'ajx',
                AlteraID: id,
                nome: nome_tipo,
            },
            success: function (ret) {
                $("#tipoequipamento").modal("hide");
                RemoverLoad();
                if (ret == 1) {
                    MensagemSucesso();
                    LimparCampos(id_form);
                    ConsultarTipo();
                } else {

                    MensagemErro();
                }
            }
        })
    }
    return false;
}

function Imprimir() {
    let tipo = $("#tipo_equip").val();
    location = "relatorio_tipo_equipamento.php?filtro=" + tipo;

}

function FiltrarTipoEquipamentos() {
    let filtrar_palavra = $("#tipo_equip").val();

    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("tipoequipamento_dataview"),
        data: {
            btn_filtro: 'ajx',
            filtro_palavra: filtrar_palavra
        },
        success: function (tabela_preenchida) {
            if (tabela_preenchida !== "") {
                $("#divResult").show();
                $("#form_consultaTipoEquip").html(tabela_preenchida);
                $("#relatorioTipoEquipamento").removeClass("ocultar");
            } else {
                $("#relatorioTipoEquipamento").addClass("ocultar");
                $("#tabela_tipo_equipamento").html('');
                $("#divResult").hide();
                MensagemGenerica("Nenhum dado encontrado");
            }
        }
    });

    return false;
}

