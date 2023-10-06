<div class="modal fade" id="equipamento">
    <div class="modal-dialog modal-xs">
        <div class="modal-content bg-white">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Equipamento</h4>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="idEquip" id="idEquip">
                        <div class="form-group">
                            <label>Tipo</label>
                            <select class="form-control obg" id="tipo" name="tipo">
                                <option value="">Selecione</option>
                                <?php foreach ($tipos as $tipo) { ?>
                                    <option value="<?= $tipo['id'] ?>"><?= $tipo['nome'] ?></option>
                                <?php } ?>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Modelo</label>
                            <select class="form-control obg" id="modelo" name="modelo">
                                <option value="">Selecione</option>
                                <?php foreach ($modelo as $md) { ?>
                                    <option value="<?= $md['id'] ?>"><?= $md['nome'] ?></option>
                                <?php } ?>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Identificação</label>
                            <input class="form-control obg" id="identificacao" name="identificacao" placeholder="Digite o aqui....">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label id="alterarLabelServico">Serviço</label>
                            <center>
                                <select multiple="multiple" class="select2" id="alterar_servico" name="alterar_servico[]">
                                    <?php foreach ($servico as $sc) { ?>
                                        <option value="<?= $sc['ServID'] ?>"><?= $sc['ServNome'] ?></option>
                                    <?php } ?>

                                </select>
                            </center>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label id="alterarLabelInsumo">Insumo</label>
                            <center>
                                <select multiple="multiple" class="select2" id="alterar_insumo" name="alterar_insumo[]">
                                    <?php foreach ($produto as $prod) { ?>
                                        <option value="<?= $prod['ProdID'] ?>"><?= $prod['ProdDescricao'] ?></option>
                                    <?php } ?>

                                </select>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-12" class="table-responsive" id="tipo_servico" style="display: none;">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            
                        </table>
                    </div>
                    <div class="col-md-12" class="table-responsive" id="tipo_insumo" style="display: none;">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" style="resize: vertical" placeholder="Digite o aqui...."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="btnCancelar" onclick="FechandoModal('form_equipamento')" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                <button name="btnGravar" class="btn btn-success" onclick="return CadastrarEquipamento('form_equipamento')">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    /* $(window).on("load", function(){
   // página totalmente carregada (DOM, imagens etc.)
   $("#nome").focus();
   $("#nome").reset();
}); */
</script>