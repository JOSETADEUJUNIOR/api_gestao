<div class="modal fade" id="modal_remover_servico_equipamento">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-red" style="background-color:#D15B47">
                <h4 class="modal-title">Remover Registro</h4>
            </div>
            <div class="modal-body">
                <input type="text" id="id_servico_equipamento" name="id_servico_equipamento">
                <input type="text" id="id_equipamento_remover_servco" name="id_equipamento_remover_servco">
                <p>Deseja realmente remover o registro: <span id="nome_servico_equipamento"></span>?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-info" data-dismiss="modal">fechar</button>
                <button name="btnRemoverServico" class="btn btn-danger" onclick="return RemoverServicoEquipamentoAjx()">Remover registro</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>