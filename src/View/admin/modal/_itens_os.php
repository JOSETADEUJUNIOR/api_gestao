<div class="modal fade" id="itens-os">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Inserir insumos e serviços</h4>

            </div>
            <form action="" id="form_chamado" method="post">
                <div class="modal-body">
                    <div class="row">
                       <div class="col-md-12 col-xs-12" id="div_listagem_itens_os" style="display:block">
                            <h3 class="widget-title grey lighter">
                                <i class="ace-icon fa fa-list green"></i>
                               Dados de insumos/materiais e serviços do lote
                            </h3>
                            <div class="table-responsive">
                                <table id="tabela_itens_os" class="table table-striped table-bordered table-hover">

                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btnCancelar" onclick="FecharItensOS()" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
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