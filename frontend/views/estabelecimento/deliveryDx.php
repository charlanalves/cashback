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

        var setStatusDelivery = callbackSetStatusDelivery = {};

        setStatusDelivery = function (obj, pedido) {
            var new_status = $(obj).val();
            $.blockUI();
            Util.ajaxPost('index.php?r=estabelecimento/global-crud&action=setStatusDelivery', {pedido: pedido, new_status: new_status}, callbackSetStatusDelivery);
        }

        callbackSetStatusDelivery = function (data) {            
            $.unblockUI();
            if (data.responseText) {
                Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
            } else {
                loadGrid();
            }
        }

        C7.init();
        
        C7.callbackLoadGridDeliveryMain = function () {};

        C7.load('Grid', 'DeliveryMain', 'gridDelivery');

        C7.exportGridToCSV('DeliveryMain');
        
        C7.grid.DeliveryMain.enableCopyMMS();
    });
    
</script>

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