<?php

    use frontend\assets\SmartAdminAsset;
    SmartAdminAsset::register($this);
    
    $dataJson = json_encode($data);
?>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        
        $('#divCheckout').html('Aguarde . . .');
            
        var r = $.ajax({
            url: 'index.php?r=pagamento/pagamento',
            type: 'POST',
            data: {'dados': JSON.parse('<?= $dataJson ?>'), _csrf: $('meta[name="csrf-token"]').attr("content")},
            dataType: "jsonp"
        });
        
        r.always(function(data) {
            $('#divCheckout').html(data.responseText);
        });
        
    });
</script>

<div id="divCheckout" style="max-width: 650px; height: auto; border: 0px silver solid; margin: auto; margin-top: 0px;"></div>

<style>
    .wrap > .container {padding-top: 0px;}
</style>

