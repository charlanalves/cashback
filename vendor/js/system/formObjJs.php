<script type="text/javascript" charset="utf-8">
    var Form = function () {};
    var Validation = function () {
        var errorMessage = '';

        this.setErrorMessage = function (value) {
            if (errorMessage == '' && typeof value != 'undefined') {
                errorMessage = value;
            }
        }

        this.getErrorMessage = function (value) {
            return errorMessage;
        }


        this.validate = function (actionRules, validator) {

            var values = actionRules[0];

            // Verifica se a validação é do formato : mensagem de erro por elemento (Grid, variável etc)
            if (typeof values[0]['value'] != "undefined") {

                for (var i in values) {
                    var value = values[i]['value'],
                            message = values[i]['message'];

                    this.setErrorMessage(message);

                    return this.runValidate(value, validator);
                }
            } else {
                // Executa a validacao com o formato: mensagem de erro por action
                return this.runValidate(values, validator);
            }
        }


        this.runValidate = function (value, validator) {
            var error = true;

            error = this.hasError(value, validator);

            if (error) {
                Form.FirstErrorValidate = {value: SYSTEM[value], error: this.errorMessage};
                return true;
            }

            return false;
        }

        this.hasError = function (value, validator) {

            if (typeof SYSTEM[value] != "undefined") {

                error = this[validator](SYSTEM[value]);

            } else if (typeof Form[value] != "undefined") {

                error = this[validator](Form[value]);

            } else {

                error = this[validator](value);
            }

            return error;
        }

        this.isEmptyGrid = function (grid) {
            var msg = "<?= Yii::t("app", "O grid não pode ficar vazio.") ?>";

            this.setErrorMessage(msg);

            if (this['_gridIsEmpty'](grid)) {
                return true;
            }

            return false;
        }


        this._gridIsEmpty = function (grid) {
            return (Form.totalRowsVisibleGrid(grid)) ? false : true;
        }


        this.isAnyRowChecked = function (grid) {
            var msg = "<?= Yii::t("app", "Gentileza selecionar um registro no grid.") ?>";

            this.setErrorMessage(msg);

            if (grid.getTotalCheckedRowsMMS() == 0) {
                return true;
            }

            return false;
        }

        this.isAnyRowSelected = function (grid) {
            return (grid.getSelectedRowId() == null) ? true : false;
        }

    };

    SYSTEM = {};

    Form.main = {};
    Form.main.modal = {};
    Form.FilterA = {};
    Form.form = {};
    Form.formOpen = true;
    Form.sendForm = true;
    Form.async = true;
    Form.params = {};


    Form.crud = {};
    Form.crud.modal = {};
    Form.FilterA = {};


    Form._init = function (conf) {
        $.extend(this.settings, conf);

        Form.load('Toolbar', 'Main');
        Form.load('Window', 'Main');
    }

    Form.getToolbarMain = function () {
        SYSTEM.Toolbar.titulo(this.settings.toolbarTitle);
        SYSTEM.Toolbar.setIconesAcoes([this.settings.toolbarBtn]);
        SYSTEM.Toolbar.core.attachEvent("onClick", Form.Toolbar);
    }

    Form.getWindowMain = function () {

        SYSTEM.Windows = new dhtmlXWindows();

        SYSTEM.Windows.createWindow("main", 0, 0, 1500, 1000);

        Form.windowMain = SYSTEM.Windows.window("main");

        Form.windowMain.button('minmax1').hide();
        Form.windowMain.button('park').hide();
        Form.windowMain.denyResize();
        Form.windowMain.center();
        Form.windowMain.hide();

        Form.windowMain.attachEvent("onClose", function (win) {
            Form.close();
        });
    }

    Form.afterLoadFormCrud = function (formName) {
        var selectedRow = SYSTEM.Grid.getRowIndex(SYSTEM.Grid.getSelectedRowId());

        Form.main.bind(SYSTEM.Grid);

        SYSTEM.Grid.clearSelection();

        SYSTEM.Grid.selectRow(selectedRow);
    }

    Form.load = function (component, nameItem, target, param, autoLoad) {
        var componentName = 'get' + component + nameItem,
                fnLoad = "load" + component,
                errorMessage = 'Erro: Você tentou chamar a função ' + componentName + '() mas ela não existe.';

        if (typeof Form[fnLoad] === 'function' && !(component === 'Grid' && typeof Form[componentName] === 'function')) {

            var fnName = (nameItem || Form.currentFormName),
                    beforeLoadFnName = 'beforeLoad' + component + fnName,
                    afterLoadFnName = 'afterLoad' + component + fnName;

            // Executa o evento BeforeLoadForm
            Form.callFunctionDynamically(beforeLoadFnName, '');

            // load especifico do componente
            Form[fnLoad](target, fnName, param, autoLoad);

            // Executa o evento AfterLoadForm
            Form.callFunctionDynamically(afterLoadFnName, '');

        } else {
            Form.callFunctionDynamically(componentName, errorMessage);
        }
    }

    Form.loadForm = function (target, fnName, param) {
        formData = Form.getFormData(fnName);
        target = (target || Form.windowMain);
        if (typeof target == 'string') {
            Form.main = new dhtmlXForm(target, formData);
        } else {
            Form.main = target.attachForm(formData);
            //@todo verificar se o "target" é window para exec a linha abaixo
            //Form.main.resizeWindowMMS(target);
        }
        Form.currentForm = Form.main;
        Form.form[fnName] = Form.main;
        Form.attachEventClick();
        Form.attachMask();
    }

    Form.loadGrid = function (target, fnName, param, autoLoad) {
        autoLoad = (typeof autoLoad == "boolean" ? autoLoad : true);
        layoutGrid = new dhtmlXLayoutObject(target, "1C");
        layoutGrid_A = layoutGrid.cells("a");
        layoutGrid_A.setText((this.settings["titleGrid" + fnName] || "<?= Yii::t('app', 'Listagem ') ?>"));
        objGrid = layoutGrid_A.attachGrid();
        objGrid.init();
        objGrid.enableRowsHover(true, 'hover');
        objGrid.layout = layoutGrid;
        objGrid.layoutCell = layoutGrid_A;
        SYSTEM[fnName] = objGrid;
        Form['actionReloadGrid' + fnName] = function (param) {
            Form.systemReloadGrid(fnName, param);
        };
        if (autoLoad === true) {
            Form['actionReloadGrid' + fnName](param);
        }
    }

    Form.systemReloadGrid = function (fnName, param) {
        param = (typeof param == "object" ? JSON.stringify(param) : param);
        callbackLoadGrid = (typeof Form['callbackLoadGrid' + fnName] == "function" ? Form['callbackLoadGrid' + fnName] : function () {});
        SYSTEM[fnName].clearAll();
        urlLoad = this.settings.urlLoadGridPrefix + fnName + '&json=true&param=' + param;
        $.blockUI();
        SYSTEM[fnName].load(urlLoad, function () {
            callbackLoadGrid();
            $.unblockUI();
        });
    }


    Form.FilterA.init = function () {

        var formData = Form.getFormData('FilterA');

        Form.FilterA = SYSTEM.Layout.innerLayout.cells("a").attachForm();
        Form.FilterA.loadStruct(formData, 'json');
        Form.FilterA.setFocusOnFirstActive();

        Form.currentForm = Form.FilterA;

        Form.attachEventClick();
    }

    Form.Toolbar = function (itemId) {

        /* 
         * Seta a action atual com a action definida ao chamar a função 
         * SYSTEM.Toolbar.setIconesAndActions([{"adicionar": "create"},{ "atualizar": "update"}]); 
         *
         * Ou seja se o usuário clicar no botão 'adicionar' na toolbar será chamado a função create
         *
         * A action atual é usada para fazer um ajax para o backend. Ex:
         *
         * Se o Form.action == 'create', ao executar a função Form.executeAction será disparado uma 
         * requisição para o método create do controller atual.
         */
        var action = SYSTEM.Layout.icons[0][itemId];


        Form.runAction(action, true);
    }

    Form.runActionClient = function (action, params, checkPermissions, validate) {
        Form.runAction(action, checkPermissions, validate, params, false, 'client');
    }

    Form.runActionBackend = function (action, params, checkPermissions, validate, sendForm) {
        Form.runAction(action, checkPermissions, validate, params, sendForm, 'backend');
    }

    Form.runAction = function (action, checkPermissions, validate, params, sendForm, actionType) {

        var action = Form.capitalise(action),
                checkPermissions = (checkPermissions || this.settings.checkPermissions),
                validate = (validate || this.settings.validate),
                actionType = (actionType || Form.actionType || this.settings.actionType),
                callback = (Form.sendDatacustomCallback || this.settings.sendDatacustomCallback),
                centerRequest = (Form.centerRequest || this.settings.centerRequest),
                methodPrefix = 'action',
                actionFnName = methodPrefix + action,
                beforeActionFnName = 'beforeAction' + action,
                afterActionFnName = 'afterAction' + action,
                errorMessage = 'Você precisa criar uma função nesse padrão action' + action + '() para a ação ' + action + ' funcionar.';
        params = (params || Form.params || this.settings.params);
        Form.action = action;
        // Form.sendForm = (sendForm === false)  ? false : (Form.sendForm === false)  ?  false : this.settings.sendForm;
        Form.sendForm = (typeof sendForm == "boolean") ? sendForm : (typeof Form.sendForm == "boolean") ? Form.sendForm : this.settings.sendForm;

        // Checa se o usuário logado tem permissão para executar a ação 
        hasPermission = (checkPermissions) ? Form.checkPermissions(action) : true;

        if (hasPermission) {

            if (validate) {
                if (!Form.validate()) {
                    return;
                }
            }

            // Executa o evento BeforeAction
            Form.callFunctionDynamically(beforeActionFnName, '');

            if (actionType === 'client') {
                // Executa a Action no cliente
                Form.callFunctionDynamically(actionFnName, errorMessage, params);
            } else {
                // executa a Action no backend - obviamente deverá existir 
                // uma action com o mesmo nome no controller em questão
                Form.executeAction(centerRequest, callback, params);
            }

            // Executa o evento AfterAction
            Form.callFunctionDynamically(afterActionFnName, '');

            Form.setDefaultValuesFields();
        }
    }

    Form.validate = function () {
        var rules = Form.rules(),
                error = true,
                validator = '',
                message = '',
                actionRules = '',
                values = [];

        for (i in rules) {
            actionRules = rules[i][Form.action];
            for (k in actionRules) {

                values = actionRules[0];
                validator = actionRules[1];

                var validation = new Validation();

                if (typeof actionRules[2] != "undefined" && typeof actionRules[2]['message'] != "undefined") {
                    validation.setErrorMessage(actionRules[2]['message']);
                }

                if (typeof validation[validator] != "undefined") {
                    error = validation.validate(actionRules, validator);

                } else {
                    if (typeof eval(validator) == 'function') {
                        error = eval(validator + '(' + values + ')');
                    }
                }


                if (error) {
                    dhtmlx.alert({
                        title: "Atenção!",
                        type: "alert-error errorCustom",
                        text: validation.getErrorMessage(),
                    });
                    return false;
                }

            }

        }

        return true;
    }



    Form.rules = function () {
        return [];
    }

    Form.checkPermissions = function (action) {
        ret = false;
        var fs = Form.settings,
                action = (action || Form.action),
                route = fs.currentModule + '/' + fs.currentController.toLowerCase() + '/' + 'valida-permissao-acao',
                param = '&rotaController=' + route + action,
                url = './index.php?r=' + route + param;

        $.ajax({
            type: 'get',
            url: url,
            async: false,
            success: function (response) {
                if (typeof response.status == "undefined" || typeof response.status == true) {
                    ret = true;
                }
            }
        });

        return ret;
    }

    Form.close = function () {
        Form.windowMain.hide();
        Form.windowMain.setModal(false);
    }

    Form.show = function () {
        Form.windowMain.show();
        Form.windowMain.setModal(true);

        fnNameAction = this.settings['titleWindow' + Form.action];
        if (fnNameAction !== 'undefined') {
            this.windowMain.setText(fnNameAction);
        }

    }

    Form.getGlobalCreateAndUpdateSets = function () {
        Form.currentForm = Form.main;
        Form.sendDatacustomCallback = '';

        Form.show();

        Form.load('WindowSets', 'GlobalCreate');
        Form.load('Form', 'Crud', Form.windowMain);

        Form.setDefaultValuesFields();
        Form.windowMain.setText(this.settings.titleWindowCreate);
    }

    Form.actionGlobalCreate = function () {
        SYSTEM.Grid.clearSelection()
        Form.getGlobalCreateAndUpdateSets();
        Form.main.clear();
    }

    Form.actionGlobalUpdate = function () {
        Form.getGlobalCreateAndUpdateSets();
    }

    Form.actionRefresh = function () {
        Form.reloadGrid();
    }

    Form.actionGlobalInactivate = function () {
        Form.setDefaultValuesFields();

        dhtmlx.confirm({
            title: this.settings.titleWindowDelete,
            ok: '<?= Yii::t('app', 'Não ') ?>',
            cancel: '<?= Yii::t('app', 'Sim ') ?>',
            text: '<?= Yii::t('app', 'Excluindo Registro ') ?>',
            callback: function (excluir) {
                if (excluir) {

                    var rowId = SYSTEM.Grid.getSelectedId();
                    var params = 'id=' + rowId;
                    var callback = 'reloadGrid';

                    Form.ajax(params, callback);

                }
            }
        });
    }

    Form.actionReloadGrid = function (params) {
        Form.reloadGrid(params);
    }

    Form.reloadGrid = function (params) {
        if (typeof Form.settings.gridReload != 'undefined') {
            if (typeof Form.settings.callbackReloadGridMain == 'function') {
                Form.settings.gridReload.load(Form.settings.urlReloadGridMain, Form.settings.callbackReloadGridMain);
            } else {
                Form.settings.gridReload.load(Form.settings.urlReloadGridMain);
            }
        }
    }

    Form.actionExportExcel = function () {
        var urlSend = '../libs/dhtmlx/excel/generate.php';
        SYSTEM.Grid.toExcel(urlSend, 'full_color');
    }

    Form.globalExportExcel = function (gridName, param) {
        Form.sendDatacustomCallback = function (a) {
            if (typeof a.excel !== "undefined") {
                uri = 'data:application/vnd.ms-excel,' + encodeURIComponent(a.excel);
                fileName = a.fileName;
                U.downloadURI(uri, fileName);
            } else {
                Form.alertAtencao((typeof a.message !== "undefined" ? a.message : "<?= Yii::t("app", "Não foi possivel gerar o excel") ?>"));
            }
        }

        if (typeof param == "object") {
            param = JSON.stringify(param);
        }

        Form.runActionBackend("globalExportExcel", {grid: gridName, param: param}, false, false, false);
    }

    Form.executeAction = function (centerRequest, callback, params) {

        var url = Form.getUrlCurrentAction(centerRequest);
        params = (params || Form.params);

        if (Form.sendForm) {
            Form.sendFormData(url, callback);
        } else {
            Form.ajax(params, callback, Form.async, centerRequest);
        }

        Form.sendForm = Form.settings.sendForm;
        Form.async = Form.settings.async;
        Form.params = Form.settings.params;
        Form.centerRequest = Form.settings.centerRequest;
        Form.actionType = Form.settings.actionType;
        Form.sendDatacustomCallback = Form.settings.sendDatacustomCallback;
    }

    Form.ajax = function (params, callback, async, centerRequest, url, type) {
        params = (params || '');
        async = async === false ? false : true;
        callback = (callback || 'sendDataCallbackDefault');
        centerRequest = centerRequest === false ? false : true;
        url = (url || Form.getUrlCurrentAction(centerRequest));


        if (async) {
            if (typeof callback == 'function') {
                $.post(url, params, callback)
            } else {
                $.post(url, params, Form[callback])
            }
        } else {
            return dhtmlxAjax.postSync(url, params);
        }

    }

    Form.ajaxJquery = function (ajaxParams, centerRequest) {

        ajaxParams.url = (ajaxParams.url || Form.getUrlCurrentAction(centerRequest));

        ajaxParams.success = (ajaxParams.success || Form['sendDataCallbackDefault']);

        ajaxParams.dataType = (ajaxParams.dataType || 'xml');

        $.ajax(ajaxParams);
    }


    Form.getFormData = function (typeForm) {
        methodPrefix = 'getFormData';
        functionName = methodPrefix + typeForm;

        if (typeof Form[functionName] !== 'function') {
            console.warn('Erro: Você deve criar uma função nesse formato ' + functionName + '() retornando o json do formulário.')
            return false;
        }

        return Form[functionName]();
    }

    Form.callFunctionDynamically = function (functionName, errorMessage, params) {
        params = (params || null);

        if (typeof Form[functionName] !== 'function') {
            if (typeof errorMessage != 'undefined' && errorMessage != '') {
                console.warn(errorMessage)
            }
            return false;
        }

        return (params === null) ? Form[functionName]() : Form[functionName](params);
    }

    Form.sendFormData = function (urlSend, callback) {
        if ((callback == '' || typeof callback == 'undefined')) {
            callback = 'sendDataCallbackDefault';
        }

        var conf = {
            sendDataCustomCallback: 'sendDataCallbackDefault'
        };

        $.extend(conf, {sendDataCustomCallback: callback});

        var ajaxOptionsDefault = {
            url: urlSend,
            success: Form[conf.sendDataCustomCallback],
            data: Form.params
        }


        if (typeof Form.ajaxOptions != 'undefined' && typeof Form.ajaxOptions != 'undefined') {
            $.extend(Form.ajaxOptions.data, ajaxOptionsDefault.data);
        }

        $.extend(ajaxOptionsDefault, Form.ajaxOptions);

        Form.currentForm.sendMMS(ajaxOptionsDefault);
    }

    Form.callbackReload = function (loader, response) {

        SYSTEM.Grid.clearAll(true);
        SYSTEM.Grid.loadXMLString(response);
    }


    Form.sendDataCallbackDefault = function (response) {

        if (response.status) {
            Form.reloadGrid();

            if (response.message === 'undefined' || response.message == '') {
                response.message = "Operação realizada com sucesso!";
            }

            dhtmlx.alert({text: response.message, ok: "ok"});

            if (Form.formOpen) {
                Form.close();
            }
        } else {

            if (response.message === 'undefined' || response.message == '') {
                response.message = "Erro ao realizar a operação.";
            }

            dhtmlx.alert({
                title: "Atenção!",
                type: "alert-error errorCustom",
                text: response.message,
                width: "100%",
            });
        }
    }

    Form.getUrlCurrentAction = function (centerRequest) {
        ctrlAction = Form.action;

        if (centerRequest == false) {
            var ctrlAction = Form.toIfemCase(Form.action);
        }

        if ((this.settings.centerRequest && typeof centerRequest == 'undefined') || (centerRequest == true) || (centerRequest == "true")) {
            return './index.php?r=' + Form.settings.currentModule + '/' + Form.settings.currentController + '/' + Form.settings.currentCenterMethod + '&action=' + ctrlAction;
        } else {
            return './index.php?r=' + Form.settings.currentModule + '/' + Form.settings.currentController + '/' + ctrlAction;
        }
    }

    Form.attachEventClick = function () {

        Form.currentForm.attachEvent("onButtonClick", function (action) {

            var validate = this.getUserData(action, 'validate');
            if (validate !== false) {
                if (!this.validate()) {
                    return;
                }
            }

            Form.centerRequest = (this.getUserData(action, 'centerRequest') === false ? false : true);
            Form.actionType = (this.getUserData(action, 'actionType') || 'backend');
            Form.sendDatacustomCallback = this.getUserData(action, 'callback');
            params = (this.getUserData(action, 'params') || null);
            Form.currentForm = this;
            Form.ajaxOptions = this.getUserData(action, 'ajaxOptions');
            action = (this.getUserData(action, 'action') || action);

            var checkPermissions = (this.getUserData(action, 'checkPermissions') || 'false');

            if (typeof action != 'undefined' || typeof Form.action === 'undefined' || Form.action == '') {
                Form.action = action;
            } else {
                action = Form.action;
            }

            Form.runAction(Form.action, checkPermissions, validate, params);

        });
    }

    Form.attachMask = function () {
        Form.currentForm.forEachItem(function (name) {
            // mascara numerica
            maskNumber = (Form.currentForm.getUserData(name, 'maskNumber') || false);
            if (maskNumber) {
                Form.currentForm.inputMaskNumberMMS(name, (Number.isInteger(maskNumber[0]) ? maskNumber[0] : ((Number.isInteger(maskNumber) ? maskNumber : false))), (maskNumber[1] || false), (maskNumber[2] || false));
            }
        });
    }

    Form.beforeAttachEventSearch = function () {
        Form.centerRequest = false;
        Form.sendDatacustomCallback = 'callbackReload';
    }


    Form.modalBoxImportFile = function (actionBackend, callbackImportFile, layoutFileName, layoutBtnName, layoutPath) {

        if (actionBackend) {
            var layout = (layout || false),
                    antigoFormAtivo = Form.currentForm,
                    layoutFileName = (layoutFileName || false);
            layoutBtnName = (layoutBtnName || "<?= Yii::t("app", 'Baixar Layout') ?>"),
                    layoutPath = (layoutPath || "files");

            botoes = ["<?= Yii::t("app", 'Importar') ?>"];
            if (layoutFileName) {
                botoes.push(layoutBtnName);
            }
            botoes.push("<?= Yii::t("app", 'Fechar') ?>");

            var boxImportFile = dhtmlx.modalbox({
                title: Form.settings.titleModalboxImportFile,
                text: "<div id='formImportFile'></div>",
                buttons: botoes,
            });

            $('.dhtmlx_popup_button').on('click', function () {
                a = this.getAttribute('result');
                Form.actionImportFile(a);
                return false;
            });

            Form.actionImportFile = function (a) {
                if (a == 0) {
                    file = Form.currentForm.getItemValue('file');
                    if (!file) {
                        dhtmlx.alert({
                            text: "<?= Yii::t("app", "Selecione um arquivo para importar.") ?>",
                        });
                        return false;
                    } else {

                        $.blockUI();
                        if (callbackImportFile) {
                            Form.sendDatacustomCallback = callbackImportFile;
                        }
                        Form.runActionBackend(actionBackend, false, false, false, true);
                        $.unblockUI();
                        document.querySelectorAll('input[type="file"]')[0].value = '';
                    }

                    // btn layout
                } else if (layoutFileName && a == 1) {
                    U.downloadURI(layoutPath + '/' + layoutFileName, layoutFileName);
                    return false;
                }

                Form.currentForm = antigoFormAtivo;
                dhtmlx.modalbox.hide(boxImportFile);
                return false;
            }

            Form.getFormDataImportFile = function () {
                return [
                    {type: "settings", position: "label-top", labelAlign: "left", labelWidth: "auto"},
                    {type: "block", list: [
                            {type: "file", name: "file", offsetLeft: 5, validate: 'Empty'},
                        ]},
                ];
            }

            // form dhtmlx
            Form.currentForm = new dhtmlXForm('formImportFile', Form.getFormDataImportFile());

        }
    }

    /* TODO
     * Mover as funções abaixo para um arquivo útil 
     */
    Form.getCellTextSelected = function (gridObj, colId) {
        return gridObj.cells(gridObj.getSelectedRowId(), gridObj.getColIndexById(colId)).getValue();
    }

    Form.getCellText = function (gridObj, rowId, colId) {
        return gridObj.cells(rowId, gridObj.getColIndexById(colId)).getValue();
    }

    Form.setCellVal = function (gridObj, colId, val) {
        gridObj.cells(gridObj.getSelectedRowId(), gridObj.getColIndexById(colId)).setValue(val);
    }

    Form.getHiddenRowsId = function (gridObj) {
        var rowsHidden = [];

        gridObj.forEachRow(function (id) {
            if (gridObj.getRowById(id).style.display == "none") {
                rowsHidden.push(id);
            }
        });

        return rowsHidden;
    }

    Form.totalRowsVisibleGrid = function (grid) {
        var hiddenRows = Form.getHiddenRowsId(grid).length,
                totalRows = grid.getRowsNum() - hiddenRows;

        return totalRows;
    }


    Form.settings = {
        titleWindowCreate: 'Adicionar Registro',
        titleWindowUpdate: 'Editar Registro',
        titleWindowDelete: 'Excluir Registro',
        titleModalboxImportFile: '<?= Yii::t('app', 'Importar arquivo') ?>',
        subtitleWindow: '',
        centerRequest: true,
        currentModule: '',
        currentController: '',
        currentCenterMethod: '',
        actionReloadGrid: 'read',
        actionType: 'client',
        urlReloadGrid: '',
        gridReload: SYSTEM.Grid,
        formReloadGrid: 'main',
        sendForm: true,
        async: true,
        params :'',
        sendDatacustomCallback: '',
        checkPermissions: false,
        validate: true
    };

    Form.capitalise = function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    Form.toIfemCase = function (s) {
        return s.replace(/\.?([A-Z]+)/g, function (x, y) {
            return "-" + y.toLowerCase()
        }).replace(/^-/, "");
    }

    Form.alertAtencao = function (message) {
        dhtmlx.alert({
            title: "<?= Yii::t("app", "Atenção!") ?>",
            type: "alert-error errorCustom",
            text: message,
            width: "100%",
        });
    }

</script>