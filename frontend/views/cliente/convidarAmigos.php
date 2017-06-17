
<!--Modal convidar amigo-->
        <div id="convidar_amigo_dialog" class="ui-dialog-content ui-widget-content text-align-center">
            <i class='fa fa-lg fa-5x fa-group padding-top-15 padding-bottom-10'></i>
            <p class="text-justify font-md">
                Assim que seu amigo começar a utilizar o CashBack, você e ele <strong>ganham R$ 10,00</strong> cada!
            </p>
            <div class="alert alert-info no-margin text-align-right">
                <textarea rows="10" class="width-100 font-xs text-justify" id="texto-convite-amigo" style="width: 100%; overflow: hidden" onclick="Util.copyElement($(this))" readonly="true"><?= \common\models\SYS01PARAMETROSGLOBAIS::getValor('1') . $user->getAuthKey() ?></textarea>
                <small class="font-xs"><i>Sua mensagem personalizada</i></small>
            </div>
            <button class="btn btn-default btn-info margin-top-10" onclick="Util.copyElement($('textarea#texto-convite-amigo'))"><i class="fa fa-copy"></i> copiar </button>
            &nbsp; &nbsp;
            <button class="btn btn-default btn-success margin-top-10"><i class="fa fa-share-alt"></i> compartilhar </button>
        </div>

<script type="text/javascript">
    
    $('#convidar_amigo_dialog').dialog({
        autoOpen: false,
        width: 300,
        height: 380,
        resizable: false,
        modal: true,
        title: "<div class='widget-header'><h4>INDIQUE UM AMIGO</h4></div>",
    });
    
    $('#convidar_amigo_dialog').dialog('open');

</script>

<style>
div#convidar_amigo_dialog {
    padding: 29px;
}
 .fixed-header #main {
    margin-top: 10px;
}   

.font-xs {
    font-size: 101%!important;
}
b, strong {
    font-weight: 700;
    color: #dc1264;
}
</style>