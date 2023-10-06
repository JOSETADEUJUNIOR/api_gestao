<div class="modal fade" id="sendMail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white">
            <div class="modal-header bg-primary" style="background-color: #9585BF;">
                <h4 class="modal-title">Envio de e-mail</h4>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Destinatário</label>
                            <input type="hidden" name="AlteraID" id="AlteraID">
                            <input type="email" class="form-control obg" id="destinatario" name="destinatario" placeholder="Digite o aqui...." required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Assunto</label>
                            <input type="hidden" name="AlteraID" id="AlteraID">
                            <input class="form-control obg" id="assunto" name="assunto" placeholder="Digite o aqui....">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="summernote">
                            Coloque o conteúdo aqui...
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="form-group">
                            <input type="file" class="form-control" id="fileInput" name="fileInput">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" id="btnCancelar" onclick="FechandoModal('form_email')" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                <button name="btnGravar" class="btn btn-success" onclick="return SendMail('form_email')">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>