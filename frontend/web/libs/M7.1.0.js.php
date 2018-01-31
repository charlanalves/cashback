<script type="text/javascript" charset="utf-8">
    var Validation = function () {
        var errorMessage = '';

        this.setErrorMessage = function (value) {
            if (errorMessage == '' && typeof value != 'undefined') {
                errorMessage = value;
            }
        };

        this.getErrorMessage = function (value) {
            return errorMessage;
        };

        this.validate = function (actionRules, validator) {

            var values = actionRules[0];

            // Verifica se a validação é do formato : mensagem de erro por elemento (Grid, variável etc)
            if (typeof values[0] == "undefined") {
                return this.runValidate(values, validator);
            } else if (typeof values[0]['value'] != "undefined") {

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
        };

        this.runValidate = function (value, validator) {
            var error = true;

            error = this.hasError(value, validator);

            if (error) {
                M7.FirstErrorValidate = {value: SYSTEM[value], error: this.errorMessage};
                return true;
            }

            return false;
        };

        this.hasError = function (value, validator) {

            if (typeof SYSTEM[value] != "undefined") {

                error = this[validator](SYSTEM[value]);

            } else if (typeof M7[value] != "undefined") {

                error = this[validator](M7[value]);

            } else {
                if (typeof this[validator] == 'function') {
                    error = this[validator](value);
                } else if (M7['action' + validator]) {
                    error = M7['action' + validator]();
                }
            }

            return error;
        };

        this.isEmptyGrid = function (grid) {
            var msg = "<?= Yii::t("app", "O grid não pode ficar vazio.") ?>";

            this.setErrorMessage(msg);

            if (this['_gridIsEmpty'](grid)) {
                return true;
            }

            return false;
        };

        this._gridIsEmpty = function (grid) {
            return (M7.totalRowsVisibleGrid(grid)) ? false : true;
        };

        this.isAnyRowChecked = function (grid) {

            var msg = "<?= Yii::t("app", "Gentileza selecionar um registro no grid.") ?>";

            this.setErrorMessage(msg);

            if (grid.getTotalCheckedRowsMMS() == 0) {
                return true;
            }

            return false;
        };

        this.isAnyRowSelected = function (grid) {
            return (grid.getSelectedRowId() == null) ? true : false;
        };

        this.checkValue = function (a, b) {
            var c = M7.grid[a.grid].getCellTextSelected(a.colId);
            return !c;
        };
    };

    var M7 = function () {};
    SYSTEM = {};

    M7.main = {};
    M7.window = {};
    M7.window.Main = {};
    M7.main.modal = {};
    M7.FilterA = {};
    M7.form = {};
    M7.grid = {};
    M7.tree = {};
    M7.layout = {};
    M7.formOpen = true;
    M7.sendForm = true;
    M7.async = true;
    M7.params = {};
    M7.gParams = {};
    M7.gParams.saveRelated = {};

    M7.crud = {};
    M7.crud.modal = {};
    M7.FilterA = {};

    M7._init = function (conf) {
        $.extend(this.settings, conf);

        M7.load('Toolbar', 'Main');
        M7.load('Window', 'Main');
    };

    M7.getToolbarMain = function () {
        SYSTEM.Toolbar.titulo(this.settings.toolbarTitle);
        SYSTEM.Toolbar.setIconesAcoes([this.settings.toolbarBtn]);
        SYSTEM.Toolbar.core.attachEvent("onClick", M7.Toolbar);
    };

    M7.getWindowMain = function () {

        SYSTEM.Windows = new dhtmlXWindows();
        SYSTEM.Windows.createWindow("main", 0, 0, 1500, 1000);
        SYSTEM.Windows.setEffect("move", true);

        M7.windowMain = SYSTEM.Windows.window("main");
        M7.windowMain.button('minmax1').hide();
        M7.windowMain.button('park').hide();
        M7.windowMain.denyResize();
        M7.windowMain.center();
        M7.windowMain.hide();

        M7.windowMain.attachEvent("onClose", function (win) {
            M7.close();
        });

        M7.window.Main = M7.windowMain;
        M7.window.Main.showMMS = M7.showWindow;
    };

    M7.afterLoadFormCrud = function (formName) {
        var selectedRow = SYSTEM.Main.getRowIndex(SYSTEM.Main.getSelectedRowId());

        M7.main.bind(SYSTEM.Main);

        SYSTEM.Main.clearSelection();
        SYSTEM.Main.selectRow(selectedRow);
    };
    
    M7.bindFormGrid = function (form, grid) {
        var selectedRow = grid.getRowIndex(grid.getSelectedRowId());

        form.bind(grid);

        grid.clearSelection();
        grid.selectRow(selectedRow);
    };

    M7.load = function (component, nameItem, target, param, autoLoad, btns, gridBind) {
        var componentName = 'get' + component + nameItem,
                fnLoad = "load" + component,
                errorMessage = 'Erro: Você tentou chamar a função ' + componentName + '() mas ela não existe.';

        // Se for carregando qualquer componente exceto o Form e existir uma função
        // com o padrão getComponentNameItem a mesma será chamada
        if (typeof M7[componentName] === 'function' && component != "Form") {
            M7.callFunctionDynamically(componentName, errorMessage);
        }

        // Se não existir a função no formato getComponentNameItem mas existir 
        // um método no formato uma loadComponent ele será chamado abaixo 
        else if (typeof M7[fnLoad] === 'function') {
            var fnName = (nameItem || M7.currentFormName),
                    beforeLoadFnName = 'beforeLoad' + component + fnName,
                    afterLoadFnName = 'afterLoad' + component + fnName;

            // Executa o evento BeforeLoadForm
            M7.callFunctionDynamically(beforeLoadFnName, '');

            // load especifico do componente
            M7[fnLoad](target, fnName, param, autoLoad, btns, gridBind);

            // Executa o evento AfterLoadForm
            M7.callFunctionDynamically(afterLoadFnName, '');
        }

        // Do contrário exibe uma mensagem de alerta solictando o dev para criar 
        // uma função com o formato getComponentNameItem
        else {
            console.warn(errorMessage);
        }
    };

    M7.loadLayout = function (target, nameItem, pattern) {
        // Se não informar o target define o padrão é o layout 1C
        if (typeof target == 'undefined' || target == '') {
            target = M7.layout.tela;
        }

        M7.layout[nameItem] = target.attachLayout(pattern);
    };

    M7.loadTree = function (target, nameItem, param) {
        // Se não informar o target define o padrão é o layout 1C
        if (typeof target == 'undefined' || target == '') {
            target = M7.layout.tela;
        }

        var url = this.settings.urlLoadGridPrefix + nameItem + '&json=true&component=Tree&param=' + param;
        M7.tree[nameItem] = target.attachTree();

        if (typeof M7['getLoadSettingsTree' + nameItem] == 'function') {
            M7['setTree' + nameItem]();
        }

        M7.tree[nameItem].loadXML(url);
    };

    M7.loadForm = function (target, fnName, param, autoLoad, btns, gridBind) {
        var formData = M7.getFormData(fnName);
        target = (target || M7.windowMain);
        
        if (typeof target == 'string') {
            M7.main = new dhtmlXForm(target, formData);
        } else {
            M7.main = target.attachForm(formData);
            M7.main.resizeWindowMMS(target);
        }
        M7.currentForm = M7.main;
        M7.form[fnName] = M7.main;
        M7.attachEventClick();
        M7.attachMask();

        if (typeof gridBind != 'undefined') {
            M7.bindFormGrid(M7.form[fnName], gridBind);
        }
    };

    M7.loadGrid = function (target, fnName, param, autoLoad, btns) {

        autoLoad = (typeof autoLoad == "boolean" ? autoLoad : true);
        var layoutGrid_A;

        /* Verifica se existe um parametro no arquivo config.php no formato
         * 'Grid' + fnName + 'Layout igual a true. Caso exista, cria o layout
         * e associa-o ao grid do contrário o layout não é criado e o grid será
         * atachado ao target informado
         */
        if (typeof M7.settings['Grid' + fnName + 'Layout'] == 'undefined' || M7.settings['Grid' + fnName + 'Layout'] == true) {
            var layoutGrid = new dhtmlXLayoutObject(target, "1C");
            layoutGrid_A = layoutGrid.cells("a");
            layoutGrid_A.setText((this.settings["titleGrid" + fnName] || "<?= Yii::t('app', 'Listagem ') ?>"));

        } else {
            layoutGrid_A = target;
        }
        /***********************************************************************/

        var objGrid = layoutGrid_A.attachGrid();
        objGrid.init();
        objGrid.enableRowsHover(true, 'hover');
        // Ativa funcionalidade de copiar as celulas com ctrl + C
        objGrid.enableCopyMMS(true, true, '<?= Yii::t('app', 'Conteúdo copiado') ?>');
        objGrid.layoutCell = layoutGrid_A;

        SYSTEM[fnName] = objGrid;
        M7.grid[fnName] = objGrid;
        M7.grid[fnName]['name'] = fnName;
        M7.grid[fnName]['layout'] = layoutGrid_A;
        M7.grid[fnName]['btns'] = [];

        M7['actionReloadGrid' + fnName] = function (param) {
            M7.systemReloadGrid(fnName, param);
        };

        if (autoLoad === true) {
            M7['actionReloadGrid' + fnName](param);
        }

        if (typeof btns != 'undefined') {
            M7.setGridBtns(M7.grid[fnName], btns, layoutGrid);
        }

        if (typeof M7[ 'actionSetToolbarGrid' + fnName ] != 'undefined') {
            var _btns = M7.runActionC('setToolbarGrid' + fnName);
            M7.setGridBtns(M7.grid[fnName], _btns, layoutGrid);
        }
    };

    M7.setGridBtns = function (grid, btns, layoutGrid) {

        for (var i in btns) {

            var action = btns[i]['action'],
                title = btns[i]['title'],
                icon = btns[i]['icon'],
                params = btns[i]['params'],
                disabled = btns[i]['disabled'] || false,
                id = btns[i]['id'] || (icon + grid['name']),
                fnName,
                preffix = 'Params';

            if (typeof btns[i]['params'] != 'undefined') {
                // Criando parametros que poderá ser usado na action a ser chamada pelo btn do grid
                var nameFnParams = preffix + M7.action + btns[i]['title'].replace(' ', '');
                M7[nameFnParams] = params;

                fnName = 'M7.runActionC("' + action + '", M7.' + nameFnParams + ')';
            } else {
                fnName = 'M7.runActionC("' + action + '")';
            }

            var btn = U.btnTopCell(fnName, title, icon, id, disabled);
            layoutGrid.addBtnTitleMMS([btn]);
            grid['btns'].push(id);
        }
    };

    /*
     * Desabilita ou habilita botão da toolbar do grid
     * 
     * @param 	gridName 	Nome do grid
     * @param   botaoId		Identificador do botão na toolbar do grid		
     * @param	disable		Se verdadeiro, desativa botão, senão o habilita	
     */
    M7.changeToolbarGridBtn = function (gridName, botaoId, disable) {
        var cellElement = M7.grid[gridName].layout;
        var btns = cellElement.querySelectorAll('button[id="' + botaoId + '"]');

        if (btns.length > 0) {
            if (disable) {
                btns[0].setAttribute('disabled', true);
            } else {
                btns[0].removeAttribute('disabled');
            }
        } else {
            console.warn('Erro: Não existe o botão "' + botaoId + '" no grid ' + gridName + '.');
        }
    };

    /*
     * Desabilita botão da toolbar do grid
     * 
     * @param 	gridName 	Nome do grid
     * @param   botaoId		Identificador do botão na toolbar do grid			
     */
    M7.disableToolbarGridBtn = function (gridName, botaoId) {
        M7.changeToolbarGridBtn(gridName, botaoId, true);
    };

    /*
     * Habilita botão da toolbar do grid
     * 
     * @param 	gridName 	Nome do grid
     * @param   botaoId		Identificador do botão na toolbar do grid			
     */
    M7.enableToolbarGridBtn = function (gridName, botaoId) {
        M7.changeToolbarGridBtn(gridName, botaoId, false);
    };

    /*
     * Desabilita ou habilita botão da toolbar do grid
     * 
     * @param 	gridName 	Nome do grid
     * @param   botaoId		Identificador do botão na toolbar do grid		
     * @param	disable		Se verdadeiro, desativa botão, senão o habilita	
     */
    M7.changeToolbarGridBtn = function (gridName, botaoId, disable) {
        var cellElement = M7.grid[gridName].layout;
        var btns = cellElement.querySelectorAll('button[id="' + botaoId + '"]');

        if (btns.length > 0) {
            if (disable) {
                btns[0].setAttribute('disabled', true);
            } else {
                btns[0].removeAttribute('disabled');
            }
        } else {
            console.warn('Erro: Não existe o botão "' + botaoId + '" no grid ' + gridName + '.');
        }
    };

    M7.systemReloadGrid = function (fnName, param) {
        param = (typeof param == "object" ? JSON.stringify(param) : param);
        var callbackLoadGrid = (typeof M7['callbackLoadGrid' + fnName] == "function" ? M7['callbackLoadGrid' + fnName] : function () {});

        SYSTEM[fnName].clearAll();

        var urlLoad = this.settings.urlLoadGridPrefix + fnName + '&json=true&param=' + param;
        $.blockUI();

        SYSTEM[fnName].load(urlLoad, function () {
            callbackLoadGrid();
            $.unblockUI();
        });
    };

    M7.FilterA.init = function () {
        var formData = M7.getFormData('FilterA');

        M7.FilterA = SYSTEM.Layout.innerLayout.cells("a").attachForm();
        M7.FilterA.loadStruct(formData, 'json');
        M7.FilterA.setFocusOnFirstActive();

        M7.currentForm = M7.FilterA;

        M7.attachEventClick();
    };

    M7.runActionClient = function (action, params, checkPermissions, validate) {
        M7.runAction(action, checkPermissions, validate, params, false, 'client');
    };

    M7.runActionBackend = function (action, params, checkPermissions, validate, sendForm) {
        M7.runAction(action, checkPermissions, validate, params, sendForm, 'backend');
    };

    M7.runActionC = function (action, params, callback, checkPermissions, validate) {
        return M7.runAction(action, checkPermissions, validate, params, false, 'client');
    };

    M7.runActionB = function (action, params, callback, sendForm, checkPermissions, validate) {
        M7.runAction(action, checkPermissions, validate, params, sendForm, 'backend', callback);
    };

    M7.Toolbar = function (itemId) {
        /*
         * Seta a action atual com a action definida ao chamar a função
         * SYSTEM.Toolbar.setIconesAndActions([{"adicionar": "create"},{ "atualizar": "update"}]);
         *
         * Ou seja se o usuário clicar no botão 'adicionar' na toolbar será chamado a função create
         *
         * A action atual é usada para fazer um ajax para o backend. Ex:
         *
         * Se o M7.action == 'create', ao executar a função M7.executeAction será disparado uma
         * requisição para o método create do controller atual.
         */
        var action = SYSTEM.Layout.icons[0][itemId];

        M7.runAction(action, true);
    };

    M7.runAction = function (action, checkPermissions, validate, params, sendForm, actionType, callback) {

        var ret = null,
            action = M7.capitalise(action),
            checkPermissions = (checkPermissions || this.settings.checkPermissions),
            validate = (validate || this.settings.validate),
            actionType = (actionType || M7.actionType || this.settings.actionType),
            callback = (callback || M7.sendDatacustomCallback || this.settings.sendDatacustomCallback),
            centerRequest = (M7.centerRequest === false) ? false : this.settings.centerRequest,
            methodPrefix = 'action',
            actionFnName = methodPrefix + action,
            beforeActionFnName = 'beforeAction' + action,
            afterActionFnName = 'afterAction' + action,
            errorMessage = 'Você precisa criar uma função nesse padrão action' + action + '() para a ação ' + action + ' funcionar.';
        
        params = (params || M7.params || this.settings.params);
        M7.action = action;
        // M7.sendForm = (sendForm === false)  ? false : (M7.sendForm === false)  ?  false : this.settings.sendForm;
        M7.sendForm = (typeof sendForm == "boolean") ? sendForm : (typeof M7.sendForm == "boolean") ? M7.sendForm : this.settings.sendForm;

        // Checa se o usuário logado tem permissão para executar a ação
        var hasPermission = (checkPermissions) ? M7.checkPermissions(action) : true;

        if (hasPermission) {

            if (validate) {
                if (!M7.validate()) {
                    return;
                }
            }

            // Executa o evento BeforeAction
            M7.callFunctionDynamically(beforeActionFnName, '');

            if (actionType === 'client') {
                // Executa a Action no cliente
                ret = M7.callFunctionDynamically(actionFnName, errorMessage, params);
            } else {
                // executa a Action no backend - obviamente deverá existir
                // uma action com o mesmo nome no controller em questão
                ret = M7.executeAction(centerRequest, callback, params);
            }

            // Executa o evento AfterAction
            M7.callFunctionDynamically(afterActionFnName, '');

            M7.setDefaultValuesFields();

            M7.sendForm = M7.settings.sendForm;
            M7.async = M7.settings.async;
            M7.params = '';
            M7.centerRequest = M7.settings.centerRequest;
            M7.actionType = M7.settings.actionType;
            M7.sendDatacustomCallback = M7.settings.sendDatacustomCallback;

            return ret;
        }
    };

    M7.validate = function () {
        var rules = M7.rules(),
            error = true,
            validator = '',
            message = '',
            actionRules = '',
            values = [];

        for (i in rules) {
            actionRules = rules[i][M7.action];
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
                        error = validator(validator);
                    }
                }

                if (error) {
                    dhtmlx.alert({
                        title: "Atenção!",
                        type: "alert-error errorCustom",
                        text: validation.getErrorMessage()
                    });
                    return false;
                }
            }
        }

        return true;
    };

    M7.rules = function () {
        return [];
    };

    M7.checkPermissions = function (action) {
        ret = false;
        var fs = M7.settings,
            action = (action || M7.action),
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
    };

    M7.close = function () {
        M7.windowMain.hide();
        M7.windowMain.setModal(false);
    };

    M7.show = function () {
        M7.windowMain.show();
        M7.windowMain.setModal(true);

        var fnNameAction = this.settings['titleWindow' + M7.action];
        if (fnNameAction !== 'undefined') {
            this.windowMain.setText(fnNameAction);
        }
    };

    M7.showWindow = function () {
        this.show();
        this.setModal(true);
        var fnNameAction = M7.settings['titleWindowMain' + M7.action];
        if (fnNameAction !== 'undefined') {
            this.setText(fnNameAction);
        }
    };

    M7.getGlobalCreateAndUpdateSets = function () {
        M7.currentForm = M7.main;
        M7.sendDatacustomCallback = '';

        M7.show();

        M7.load('WindowSets', 'GlobalCreate');
        M7.load('Form', 'Crud', M7.windowMain);

        M7.setDefaultValuesFields();
    };
    
    M7.getWindowSetsGlobalCreate = function () {
    };

    M7.setDefaultParamsG = function (p = {}){

        if (typeof p == 'string') {
            return p;
        } else {

            if (typeof p.form == 'undefined') {
                p.form = 'Main';
            }
            if (typeof p.grid == 'undefined') {
                p.grid = 'Main';
            }
            if (typeof p.title == 'undefined') {
                if (M7.action == 'GCreate' || M7.action == 'GlobalCreate') {
                    p.title = 'titleWindowCreate';
                } else if (M7.action == 'GUpdate' || M7.action == 'GlobalUpdate') {
                    p.title = 'titleWindowUpdate';
                }
            } else {
                p.title = 'titleWindow' + p.title;
            }

            if (typeof p.callback == 'undefined') {
                p.callback = 'GsendDataCallbackDefault';
            }
        }

        return p;
    };

    M7.actionGlobalCreate = function () {
        M7.grid.Main.clearSelection();
        M7.getGlobalCreateAndUpdateSets();
        M7.form.Crud.clear();
        M7.windowMain.setText(this.settings.titleWindowCreate);
    };

    M7.actionGlobalUpdate = function () {
        M7.getGlobalCreateAndUpdateSets();
        M7.windowMain.setText(this.settings.titleWindowUpdate);
    };

    M7.actionGCreate = function (b) {
        var p = M7.setDefaultParamsG(b);

        M7.grid[p.grid].clearSelection();
        M7.getGCreateGUpdate(p);
        M7.form[p.form].clear();
        M7.windowMain.setText(this.settings[p.title]);
    };

    M7.actionGUpdate = function (p) {
        var p = M7.setDefaultParamsG(p);

        M7.getGCreateGUpdate(p);
        M7.window.Main.setText(this.settings[p.title]);
        M7.bindFormGrid(M7.form[p.form], M7.grid[p.grid]);
    };

    M7.getGCreateGUpdate = function (p) {
        M7.sendDatacustomCallback = '';
        M7.window.Main.showMMS();
        M7.load('Form', p.form, M7.window.Main);
        M7.setDefaultValuesFields();
    };

    M7.actionRefresh = function () {
        M7.reloadGrid();
    };

    M7.actionGlobalInactivate = function (grid, modelName) {
        M7.actionGlobalDelete(grid, modelName);
    };

    M7.actionGlobalDelete = function (grid, modelName) {
        M7.setDefaultValuesFields();

        if (typeof grid == 'object') {
            grid = (Object.keys(grid).length === 0) ? "Main" : grid;
        } else {
            grid = (grid || "Main");
        }

        var rowId = M7.grid[grid].getSelectedId(),
            params = {id: rowId, inactivateModel: modelName},
            callback = 'reloadGrid',
            sendForm = false;

        // Defini o grid que será atualizado no callback
        M7.settings.gridReload = grid;

        dhtmlx.confirm({
            title: this.settings.titleWindowDelete,
            ok: '<?= Yii::t('app', 'Não ') ?>',
            cancel: '<?= Yii::t('app', 'Sim ') ?>',
            text: '<?= Yii::t('app', 'Excluindo Registro ') ?>',
            callback: function (excluir) {
                if (!excluir) {
                    M7.runActionB(M7.action, params, callback, sendForm);
                }
            }
        });
    };

    M7.actionGInactivate = function (params) {
        M7._actionDelete(params);
    };
    
    M7.actionGDelete = function (params) {
        M7._actionDelete(params);
    };

    M7.actionGDelete = function (params) {
        M7._actionDelete(params);
    };

    M7._actionDelete = function (params = {}) {

        p = M7.setDefaultParamsG(params);

        M7.setDefaultValuesFields();

        var rowId = M7.grid[p.grid].getSelectedId(),
            sendForm = false;

        $.extend(params, {id: rowId, inactivateModel: p.model});

        // Define o grid que será atualizado no callback
        M7.settings.gridReload = p.grid;

        dhtmlx.confirm({
            title: this.settings.titleWindowDelete,
            ok: '<?= Yii::t('app', 'Não ') ?>',
            cancel: '<?= Yii::t('app', 'Sim ') ?>',
            text: '<?= Yii::t('app', 'Excluindo Registro ') ?>',
            callback: function (excluir) {
                if (!excluir) {
                    M7.runActionB(M7.action, params, p.callback, sendForm);
                }
            }
        });
    };
    
    M7.actionReloadGrid = function (params) {
        M7.reloadGrid(params);
    };

    M7.actionGReloadGrid = function () {
        if (typeof M7.grid[M7.settings.gridReload] != 'undefined') {
            M7.grid[M7.settings.gridReload].load(M7.settings.urlLoadGridPrefix + M7.settings.gridReload);
        }
    };

    M7.reloadGrid = function (params) {
        if (typeof M7.settings.gridReload != 'undefined') {
            if (typeof M7.settings.callbackReloadGrid == 'function') {
                M7.settings.gridReload.load(M7.settings.urlReloadGrid, M7.settings.callbackReloadGrid);
            } else {
                if (typeof SYSTEM[M7.settings.gridReload] != 'undefined') {
                    SYSTEM[M7.settings.gridReload].load(M7.settings.urlReloadGrid);
                } else if (typeof M7.settings.gridReload == 'function') {
                    M7.settings.gridReload.load(M7.settings.urlReloadGrid);
                }
            }
        }
    };

    M7.actionExportExcel = function () {
        var urlSend = '../libs/dhtmlx/excel/generate.php';
        SYSTEM.Grid.toExcel(urlSend, 'full_color');
    };

    M7.globalExportExcel = function (gridName, param) {
        M7.sendDatacustomCallback = function (a) {
            if (typeof a.excel !== "undefined") {
                var uri = 'data:application/vnd.ms-excel,' + encodeURIComponent(a.excel);
                var fileName = a.fileName;
                U.downloadURI(uri, fileName);
            } else {
                M7.alertAtencao((typeof a.message !== "undefined" ? a.message : "<?= Yii::t("app", "Não foi possivel gerar o excel") ?>"));
            }
        };

        if (typeof param == "object") {
            param = JSON.stringify(param);
        }

        M7.runActionBackend("globalExportExcel", {grid: gridName, param: param}, false, false, false);
    };

    M7.executeAction = function (centerRequest, callback, params) {

        var url = M7.getUrlCurrentAction(centerRequest);
        params = (params || M7.params);

        if (Object.keys(M7.params).length == 0 && params != '') {
            M7.params = params;
        }

        if (M7.sendForm) {
            M7.sendFormData(url, callback);
        } else {
            if (typeof M7.ajaxOptions != 'undefined') {
                if (M7.ajaxOptions.dataType == 'binary') {
                    M7.ajaxBinary(params, callback, M7.async, centerRequest);
                } else {
                    M7.ajax(params, callback, M7.async, centerRequest);
                }
            } else {
                M7.ajax(params, callback, M7.async, centerRequest);
            }
        }
    };

    M7.ajax = function (params, callback, async, centerRequest, url, type) {
        params = (params || '');
        async = async === false ? false : true;
        callback = (callback || 'sendDataCallbackDefault');
        centerRequest = centerRequest === false ? false : true;
        url = (url || M7.getUrlCurrentAction(centerRequest));

        if (async) {
            if (typeof callback == 'function') {
                $.post(url, params, callback);
            } else {
                $.post(url, params, M7[callback]);
            }
        } else {
            return dhtmlxAjax.postSync(url, params);
        }
    };

    M7.ajaxJquery = function (ajaxParams, centerRequest) {

        ajaxParams.url = (ajaxParams.url || M7.getUrlCurrentAction(centerRequest));
        ajaxParams.success = (ajaxParams.success || M7['sendDataCallbackDefault']);
        ajaxParams.dataType = (ajaxParams.dataType || 'xml');

        $.ajax(ajaxParams);
    };

    M7.getFormData = function (typeForm) {
        var methodPrefix = 'getFormData';
        var functionName = methodPrefix + typeForm;

        if (typeof M7[functionName] !== 'function') {
            console.warn('Erro: Você deve criar uma função nesse formato ' + functionName + '() retornando o json do formulário.');
            return false;
        }

        return M7[functionName]();
    };

    M7.callFunctionDynamically = function (functionName, errorMessage, params) {
        params = (params || null);

        if (typeof M7[functionName] !== 'function') {
            if (typeof errorMessage != 'undefined' && errorMessage != '') {
                console.warn(errorMessage)
            }
            return false;
        }

        return (params === null) ? M7[functionName]() : M7[functionName](params);
    };

    M7.sendFormData = function (urlSend, callback) {
        if ((callback == '' || typeof callback == 'undefined')) {
            callback = 'sendDataCallbackDefault';
        }

        var conf = {
            sendDataCustomCallback: 'sendDataCallbackDefault'
        };

        $.extend(conf, {sendDataCustomCallback: callback});

        var ajaxOptionsDefault = {
            url: urlSend,
            success: M7[conf.sendDataCustomCallback],
            data: M7.params
        };

        if (typeof M7.ajaxOptions != 'undefined' && typeof M7.ajaxOptions != 'undefined') {
            $.extend(M7.ajaxOptions.data, ajaxOptionsDefault.data);
        }

        $.extend(ajaxOptionsDefault, M7.ajaxOptions);

        M7.currentForm.sendMMS(ajaxOptionsDefault);
    };

    M7.callbackReload = function (loader, response) {
        SYSTEM.Grid.clearAll(true);
        SYSTEM.Grid.loadXMLString(response);
    };

    M7.GsendDataCallbackDefault = function (response) {

        if (response.status) {
            M7.actionGReloadGrid();

            if (response.message === 'undefined' || response.message == '') {
                response.message = "Operação realizada com sucesso!";
            }

            dhtmlx.alert({text: response.message, ok: "ok"});

            if (M7.formOpen) {
                M7.close();
            }
        } else {

            if (response.message === 'undefined' || response.message == '') {
                response.message = "Erro ao realizar a operação.";
            }

            dhtmlx.alert({
                title: "Atenção!",
                type: "alert-error errorCustom",
                text: response.message,
                width: "30%"
            });
        }
    };
    
    M7.sendDataCallbackDefault = function (response) {

        if (response.status) {
            M7.reloadGrid();

            if (response.message === 'undefined' || response.message == '') {
                response.message = "Operação realizada com sucesso!";
            }

            dhtmlx.alert({text: response.message, ok: "ok"});

            if (M7.formOpen) {
                M7.close();
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
    };

    M7.getUrlCurrentAction = function (centerRequest) {
        ctrlAction = M7.action;

        if (centerRequest == false) {
            var ctrlAction = M7.toIfemCase(M7.action);
        }

        <?php
            $currentController = Yii::$app->controller->id;
            $currentModule = Yii::$app->controller->module->id;
        ?>

        if ((this.settings.centerRequest && typeof centerRequest == 'undefined') || (centerRequest == true) || (centerRequest == "true")) {
            return './index.php?c=<?= $this->seg()->urlEncode($currentModule . "/" . $currentController . "/") ?>||CNH||' + M7.settings.currentCenterMethod + '&action=' + ctrlAction;
        } else {
            return './index.php?c=<?= $this->seg()->urlEncode($currentModule . "/" . $currentController . "/") ?>||CNH||' + ctrlAction;
        }
    };

    M7.attachEventClick = function () {

        M7.currentForm.attachEvent("onButtonClick", function (action) {

            var validate = this.getUserData(action, 'validate');
            if (validate !== false) {
                if (!this.validate()) {
                    return;
                }
            }

            M7.centerRequest = (this.getUserData(action, 'centerRequest') === false ? false : true);
            M7.actionType = (this.getUserData(action, 'actionType') || 'backend');
            M7.sendDatacustomCallback = this.getUserData(action, 'callback');
            params = (this.getUserData(action, 'params') || null);
            M7.currentForm = this;
            M7.ajaxOptions = this.getUserData(action, 'ajaxOptions');
            action = (this.getUserData(action, 'action') || action);
            var sendForm = (this.getUserData(action, 'sendForm') || true);
            M7.settings.gridReload = (this.getUserData(action, 'gridReload') || M7.settings.gridReload);
            
            if (typeof this.getUserData(action, 'globalSaveRelated') == 'object') {
                M7.gParams.saveRelated = this.getUserData(action, 'globalSaveRelated');
            } else {
                M7.gParams.saveRelated = false;
            }
            var checkPermissions = (this.getUserData(action, 'checkPermissions') || 'false');

            if (typeof action != 'undefined' || typeof M7.action === 'undefined' || M7.action == '') {
                M7.action = action;
            } else {
                action = M7.action;
            }

            if (M7.actionType == 'client') {
                M7.runActionC(M7.action, params, M7.sendDatacustomCallback, checkPermissions, validate);
            } else {
                M7.runActionB(M7.action, params, M7.sendDatacustomCallback, sendForm, checkPermissions, validate);
            }
        });
    };

    M7.attachMask = function () {
        M7.currentForm.forEachItem(function (name) {
            // mascara numerica
            maskNumber = (M7.currentForm.getUserData(name, 'maskNumber') || false);
            if (maskNumber) {
                M7.currentForm.inputMaskNumberMMS(name, (Number.isInteger(maskNumber[0]) ? maskNumber[0] : ((Number.isInteger(maskNumber) ? maskNumber : false))), (maskNumber[1] || false), (maskNumber[2] || false));
            }
        });
    };

    M7.beforeAttachEventSearch = function () {
        M7.centerRequest = false;
        M7.sendDatacustomCallback = 'callbackReload';
    };

    M7.modalBoxImportFile = function (actionBackend, callbackImportFile, layoutFileName, layoutBtnName, layoutPath) {

        if (actionBackend) {
            var layout = (layout || false),
                    antigoFormAtivo = M7.currentForm,
                    layoutFileName = (layoutFileName || false);
            layoutBtnName = (layoutBtnName || "<?= Yii::t("app", 'Baixar Layout') ?>"),
                    layoutPath = (layoutBtnName || "files");

            botoes = ["<?= Yii::t("app", 'Importar') ?>"];
            if (layoutFileName) {
                botoes.push(layoutBtnName);
            }
            botoes.push("<?= Yii::t("app", 'Fechar') ?>");

            var boxImportFile = dhtmlx.modalbox({
                title: M7.settings.titleModalboxImportFile,
                text: "<div id='formImportFile'></div>",
                buttons: botoes
            });

            $('.dhtmlx_popup_button').on('click', function () {
                a = this.getAttribute('result');
                M7.actionImportFile(a);
                return false;
            });

            M7.actionImportFile = function (a) {
                if (a == 0) {
                    file = M7.currentForm.getItemValue('file');
                    if (!file) {
                        dhtmlx.alert({
                            text: "<?= Yii::t("app", "Selecione um arquivo para importar.") ?>"
                        });
                        return false;
                    } else {

                        $.blockUI();
                        M7.sendDatacustomCallback = callbackImportFile;
                        M7.runActionBackend(actionBackend, false, false, false, true);
                        $.unblockUI();
                        document.querySelectorAll('input[type="file"]')[0].value = '';
                    }

                    // btn layout
                } else if (layoutFileName && a == 1) {
                    U.downloadURI(layoutPath + '/' + layoutFileName, layoutFileName);
                    return false;
                }

                M7.currentForm = antigoFormAtivo;
                dhtmlx.modalbox.hide(boxImportFile);
                return false;
            };

            M7.getFormDataImportFile = function () {
                return [
                    {type: "settings", position: "label-top", labelAlign: "left", labelWidth: "auto"},
                    {type: "block", list: [
                        {type: "file", name: "file", offsetLeft: 5, validate: 'Empty'}
                    ]}
                ];
            };

            // form dhtmlx
            M7.currentForm = new dhtmlXForm('formImportFile', M7.getFormDataImportFile());

        }
    };

    /* TODO
     * Mover as funções abaixo para um arquivo útil
     */
    M7.getCellTextSelected = function (gridObj, colId) {
        return gridObj.cells(gridObj.getSelectedRowId(), gridObj.getColIndexById(colId)).getValue();
    };

    M7.getCellText = function (gridObj, rowId, colId) {
        return gridObj.cells(rowId, gridObj.getColIndexById(colId)).getValue();
    };

    M7.setCellVal = function (gridObj, colId, val) {
        gridObj.cells(gridObj.getSelectedRowId(), gridObj.getColIndexById(colId)).setValue(val);
    };

    M7.getHiddenRowsId = function (gridObj) {
        var rowsHidden = [];

        gridObj.forEachRow(function (id) {
            if (gridObj.getRowById(id).style.display == "none") {
                rowsHidden.push(id);
            }
        });

        return rowsHidden;
    };

    M7.totalRowsVisibleGrid = function (grid) {
        var hiddenRows = M7.getHiddenRowsId(grid).length,
            totalRows = grid.getRowsNum() - hiddenRows;

        return totalRows;
    };

    M7.actionGlobalGenReport = function (fileName, params = '', callback = M7.globalShowReportPdf, format = 'pdf') {

        M7.ajaxOptions = {
            dataType: "binary",
            error: M7.gCbErrorShowReport
        };

        M7.centerRequest = false;
        M7.sendForm = false;

        M7.runActionB('gerarRelatorio', {format: format, fileName: fileName, params: params}, callback);
    };

    M7.gCbErrorShowReport = function (jqXHR, textStatus, errorThrown) {
        dhtmlx.alert({
            title: "Atenção!",
            type: "alert-error errorCustom",
            text: "<?= Yii::t('app', '<strong>Erro ao gerar o relatório </strong><br>') ?>" + errorThrown,
            width: "50%"
        });
    };
    
    M7.globalShowReport = function (binario, formato) {

        if (binario.status == false || binario.size < 100) {
            dhtmlx.alert({
                title: "Atenção!",
                type: "alert-error errorCustom",
                text: "<?= Yii::t('app', '<strong>Erro ao gerar o relatório. Possíveis razões:</strong><br> - O Relatório não existe no servidor<br> - O DataSource esta configurado de forma incorreta.') ?>",
                width: "30%"
            });
        } else {
            formato = (formato || 'pdf');

            arquivoBin = new Blob([binario], {type: 'application/' + formato});

            if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(arquivoBin); // para IE
            } else {
                var a = document.createElement("a");
                document.body.appendChild(a);
                a.style = "display: none";

                var fileURL = URL.createObjectURL(arquivoBin);
                a.href = fileURL;
                a.download = 'Relatorio.' + formato;
                a.click();
                window.URL.revokeObjectURL(fileURL);
            }
        }

        M7.ajaxOptions = {
            dataType: "json"
        };

        M7.sendForm = true;
    };

    M7.ajaxBinary = function (params, callback, async, centerRequest, url, type) {
        params = (params || '');
        async = async === false ? false : true;
        callback = (callback || 'sendDataCallbackDefault');
        centerRequest = centerRequest === false ? false : true;
        url = (url || M7.getUrlCurrentAction(centerRequest));
        var ajaxParams = {};

        ajaxParams.url = url;
        ajaxParams.type = 'POST';
        ajaxParams.data = (params || {});
        ajaxParams.success = callback;

        $.extend(ajaxParams, M7.ajaxOptions);
        $.ajax(ajaxParams);

        M7.ajaxOptions = {
            dataType: "json"
        };
    };

    M7.globalShowReportPdf = function (binario) {
        M7.globalShowReport(binario, 'pdf');
    };

    M7.globalShowReportXls = function (binario) {
        M7.globalShowReport(binario, 'xls');
    };
    M7.beforeSendMMS = function () {};

    M7.settings = {
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
        params: null,
        sendForm: true,
        async: true,
        params: '',
        sendDatacustomCallback: '',
        checkPermissions: false,
        validate: true
    };

    M7.capitalise = function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    };

    M7.toIfemCase = function (s) {
        return s.replace(/\.?([A-Z]+)/g, function (x, y) {
            return "-" + y.toLowerCase()
        }).replace(/^-/, "");
    };

    M7.alertAtencao = function (message) {
        dhtmlx.alert({
            title: "<?= Yii::t("app", "Atenção!") ?>",
            type: "alert-error errorCustom",
            text: message,
            width: "100%",
        });
    };
    /**
     * prepareSaveMultiple
     * Prepara os dados objetivando a compatibilidade com a função global saveMultiple 
     * 
     * 
     * @author Charlan Santos
     * @since 09/2017
     */
    M7.prepareSaveMultiple = function (data, model, flgAtivo, transacao) {
        var params = {SM: {}};

        params['SM']['dados'] = data,
                params['SM']['model'] = model,
                params['SM']['flgAtivo'] = flgAtivo,
                params['SM']['transacao'] = transacao;

        return params;
    };

    /**
     * notContainedIn
     * Realiza um diff entre arrays
     * 
     * @author Charlan Santos
     * @since 09/2017
     * 
     */
    M7.notContainedIn = function (arr) {
        return function arrNotContains(element) {
            return arr.indexOf(element) === -1 && !isNaN(parseInt(element));
        };
    };
</script>
