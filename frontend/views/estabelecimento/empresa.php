<?php
/* @var $this yii\web\View */

//var_dump($v);

$this->title = '';
?>
<div id="layout-view" style="height: 100%;"></div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function (event) {

        SYSTEM.boot();

        var gridNameMain = 'ReferenciaVolumeManual';

        var urlLoadGridPrefix = './index.php?r=estabelecimento&gridName=';

        var formConf = {
            toolbarTitle: "<?= $tituloTela ?>",
            toolbarBtn: {"adicionar": "congelar", "atualizar": "refresh", "export_excel": "exportExcel"},
            currentCenterMethod: "global-crud",
            currentModule: "",
            currentController: 'volume-manual',
            actionReloadGrid: gridNameMain,
            gridReload: {}, // obj grid main
            urlLoadGridPrefix,
                    // Grid Main
                    urlLoadGridMain: urlLoadGridPrefix + gridNameMain,
            urlReloadGridMain: urlLoadGridPrefix + gridNameMain,
            callbackReloadGridMain: SYSTEM.callbackReloadGrid,
            titleWindowCongelar: "",
        };

        SYSTEM.boot();
        Form._init(formConf);

        Form.actionEstabelecimento = function () {
            SYSTEM.Layout.tituloCell("a", "Dados do estabelecimento");
            Form.load('Form', 'Estabelecimento', SYSTEM.Layout.tela);
        }

        Form.getFormDataEstabelecimento = function () {

            tb = 'CB10_CATEGORIA';
            val = 'CB10_ID';
            text = "CB10_NOME";
            where = "CB10_STATUS = 1";
            params_categoria = '&table=' + tb + '&columnId=' + val + '&columnText=' + text + '&where=' + where;

            return [
                {type: "settings", position: "label-top", labelAlign: "left", labelWidth: "auto"},
                {type: "block", list: [
                    {type:"hidden", name:"id", value:""},
                    {type:"input", name:"CB04_NOME", label:"<?= $al['CB04_NOME']?>:", labelWidth: 'auto', inputWidth: 300, value:"", required:true, offsetLeft: 10},
                    {type:"input", name:"CB04_FUNCIONAMENTO", label:"<?= $al['CB04_FUNCIONAMENTO']?>:", labelWidth: 'auto', inputWidth: 300, rows:3, value:"", required:true, offsetLeft: 10},
                    {type:"input", name:"CB04_OBSERVACAO", label:"<?= $al['CB04_OBSERVACAO']?>:", labelWidth: 'auto', inputWidth: 300, rows:3, value:"", required:true, offsetLeft: 10},
                    {type:"combo", name:"CB04_CATEGORIA_ID", label:"<?= $al['CB04_CATEGORIA_ID']?>:", connector:"./index.php?r=estabelecimento/combo" + params_categoria, labelWidth: 'auto', inputWidth: 300, rows:3, value:"", required:true, offsetLeft: 10, readonly: true},
                    {type: "fieldset", label:"Formas de pagamento", width:"auto", position:"label-left", list:[
                        {type:"settings", position:"label-right"},
                        {type:"checkbox", name:"pagamento[]", value:"p1", label:"Visa", checked:false},
                        {type:"checkbox", name:"pagamento[]", value:"p2", label:"MasterCard", checked:false},
                        {type:"checkbox", name:"pagamento[]", value:"p3", label:"Elo", checked:false},
                    ]},
                    {type: "fieldset", label:"Endereço", width:"auto", list:[
                        {type: "block", list: [
                            {type:"input", name:"CB04_END_CEP", label:"<?= $al['CB04_END_CEP']?>:", labelWidth: 'auto', inputWidth: 100, value:"", required:true, offsetLeft: 10},
                            {type:"newcolumn", offset:20},
                            {type: "button", name: "buscaCEP", value: "Buscar", offsetTop: 25, userdata:{validate:false}},
                        ]},
                        {type:"input", name:"CB04_END_BAIRRO", label:"<?= $al['CB04_END_BAIRRO']?>:", labelWidth: 'auto', inputWidth: 200, value:"", required:true, offsetLeft: 10},
                        {type:"newcolumn", offset:20},
                        {type:"input", name:"CB04_URL_LOGOMARCA", label:"<?= $al['CB04_URL_LOGOMARCA']?>:", labelWidth: 'auto', inputWidth: 300, value:"", required:true, offsetLeft: 10},
                        {type:"input", name:"CB04_END_CIDADE", label:"<?= $al['CB04_END_CIDADE']?>:", labelWidth: 'auto', inputWidth: 300, value:"", required:true, offsetLeft: 10},
                        {type:"newcolumn", offset:20},
                        {type:"input", name:"CB04_END_NUMERO", label:"<?= $al['CB04_END_NUMERO']?>:", labelWidth: 'auto', inputWidth: 60, value:"", required:true, offsetLeft: 10},
                        {type:"input", name:"CB04_END_UF", label:"<?= $al['CB04_END_UF']?>:", labelWidth: 'auto', inputWidth: 60, value:"", required:true, offsetLeft: 10},
                    ]},
                    {type: "button", name: "GlobalUpdate", value: "Salvar", offsetTop: 25,},
                ]},
            ];
        }
        
        Form.actionEstabelecimento();

    });
</script>