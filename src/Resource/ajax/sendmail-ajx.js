
function SendMail(id_form) {
    
    if (NotificarCampos(id_form)) {
    let assunto = $("#assunto").val();
    let destinatario = $("#destinatario").val();
    var conteudo = $('#summernote').summernote('code');
    console.log(conteudo);

    let mensagem = conteudo;

    // Crie um objeto FormData para enviar o formulário e o anexo
    let formData = new FormData();
    formData.append('EnviarEmail', 'ajx');
    formData.append('assunto', assunto);
    formData.append('destinatario', destinatario);
    formData.append('mensagem', mensagem);

    // Adicione o anexo ao FormData (substitua 'fileInput' pelo ID real do campo de arquivo)
    let anexoInput = document.getElementById('fileInput');
    if (anexoInput.files.length > 0) {
        formData.append('anexo', anexoInput.files[0]);
    }

    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("os_dataview"),
        data: formData,
        processData: false, // Evite que o jQuery processe os dados
        contentType: false, // Evite que o jQuery defina automaticamente o cabeçalho "Content-Type"
        success: function (ret) {
            $("#sendMail").modal("hide");
            RemoverLoad();
            if (ret == 1) {
                MensagemSucesso();
                LimparCampos(id_form);
                ConsultarModelo();
            } else {
                MensagemErro();
            }
        }
    });
    }
    return false;
}

function ConsultarEmail() {
    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("os_dataview"),
        data: {
            btn_consultarEmail: 'ajx'
        }, success: function (tabela_preenchida) {
            $("#tabela_result_servico").html(tabela_preenchida);
        }
    })
}

function FiltrarPorData() {
    
    var dataInicial = document.getElementById("dataInicial").value;
    var dataFinal = document.getElementById("dataFinal").value;

    // Certifique-se de que ambas as datas tenham sido selecionadas
    if (dataInicial === "" || dataFinal === "") {
        MensagemGenerica("Por favor, selecione ambas as datas de início e fim.","warning");
        $("#dataInicial").focus();
        return;
    }

    $.ajax({
        type: "POST",
        url: BASE_URL_AJAX("os_dataview"),
        data: {
            btn_consultarData: 'ajx',
            dataInicial: dataInicial,
            dataFinal: dataFinal // Envie as datas selecionadas para o servidor
        },
        success: function (tabela_preenchida) {
            console.log(tabela_preenchida);
            $("#tabela_result_email").html(tabela_preenchida);
        }
    });
}