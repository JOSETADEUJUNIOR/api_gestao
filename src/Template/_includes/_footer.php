<?php

use Src\_public\Util;
?>
<div class="footer">
    <div class="footer-inner">
        <div class="footer-content">
            <span class="bigger-120">
                <span class="blue bolder">Usuário logado:</span>
                <?= Util::NomeLogado() ?> &copy; <?php echo date('d/m/Y') ?>
            </span>

        </div>
    </div>
</div>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>