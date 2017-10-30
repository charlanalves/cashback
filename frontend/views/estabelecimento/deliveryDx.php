<style>
    .not_m_line{
        white-space:nowrap; overflow:hidden;
    }
</style>
<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();

        C7.init();
        
        C7.callbackLoadGridDeliveryMain = function () {};

        C7.load('Grid', 'DeliveryMain', 'gridDelivery');

        C7.exportGridToCSV('DeliveryMain');
        
        C7.grid.DeliveryMain.enableCopyMMS();

    });

    // alterar status - Modal
    var alterarStatusDelivery = function (pedido) {
        $('#remoteModalSetStatus')
            .modal('show')
            .find('.modal-body button#btn-salvar')
            .attr('onclick', 'setStatusDelivery(' + pedido + ')');
    
    }

    // alterar status - Action
    setStatusDelivery = function (pedido) {
        var new_status = $('select#statusDelivery').val();
        if(!new_status){
            Util.smallBox('Selecione um status para a entrega.', '', 'danger');
        } else {
            $('#remoteModalSetStatus').modal('hide');
            $.blockUI();
            Util.ajaxPost('index.php?r=estabelecimento/global-crud&action=setStatusDelivery', {pedido: pedido, new_status: new_status}, callbackSetStatusDelivery);
        }
    }

    // alterar status - Callback
    callbackSetStatusDelivery = function (data) {            
        $.unblockUI();
        if (data.responseText) {
            Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
            $('#remoteModalSetStatus').modal('show');
        } else {
            C7.reloadGrid();
        }
    }


</script>

<?php 
    $optionsStatus = function ($status, $default = false) {
        $opt = '';
        $opt .= "<option value=''>Selecione</option>";
        foreach ($status as $key => $value) {
            $opt .= "<option value='" . $key . "' " . ($default === $key ? 'selected' : '') . ">" . $value . "</option>";
        }
        return $opt;
    };
?>

<!-- MODAL SET STATUS DELIVERY -->
<div class="modal fade" id="remoteModalSetStatus" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 350px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Alterar Status da entrega</h4>
            </div>
            <div class="modal-body no-padding">
                <form action="" class="smart-form" novalidate="novalidate">
                    <fieldset>
                        <section class="">
                            <label class="">Status</label>
                            <label class="select"> 
                                <select id="statusDelivery">
                                    <?= $optionsStatus($status) ?>
                                </select>
                            </label>
                        </section>
                    </fieldset>

                    <footer style="padding: 10px;">
                        <button id="btn-salvar" type="button" class="btn btn-success" style="margin:0px 4px" onclick="">
                            Salvar
                        </button>
                        <button id="btn-cancelar" type="button" class="btn btn-danger" data-dismiss="modal" style="margin:0px 4px">
                            Cancelar
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-truck"></i> 
            Delivery <span></span>
        </h1>
    </div>
</div>

<div class="row">

    <article class="col-sm-12 col-md-12 col-lg-12 no-padding">

        <div role="content">

            <div class="widget-body dx-grid" id="gridDelivery"></div>
            
        </div>

    </article>
    
</div>