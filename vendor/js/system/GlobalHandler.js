/* Classe responsável por manipular funções e eventos globais do javascript
*
* @autor Charlan Santos
*
*/
var GlobalHandler = function() {

	var _onerror = function(message, url, lineNumber, columnNo, error) {

		if (typeof parent.$.unblockUI != 'undefined') {
			parent.$.unblockUI();
			if (typeof $.unblockUI != 'undefined') {
				$.unblockUI();
			}
		}

		if (typeof error.stack != 'undefined') {
			console.error(error.stack);
		} else {
			console.error(error);
		}

		return true;
	};

	this.mmsError = function(msg) {
		m = {mmsMessage: msg, status: false};

		return m;
	}

	this.attachBlockUIAjaxRequest = function() {
		parent.$.blockUI.defaults.css.border =  'none';
		parent.$.blockUI.defaults.css.padding = '0px';
		parent.$.blockUI.defaults.css.textAlign = 'center';
		parent.$.blockUI.defaults.css.backgroundColor = 'rgba(8, 4, 4, 1))';
		parent.$.blockUI.defaults.message = '<img src="./images/loading.svg">';
		
		$.blockUI.defaults.css.border =  'none';
		$.blockUI.defaults.css.padding = '0px';
		$.blockUI.defaults.css.textAlign = 'center';
		$.blockUI.defaults.css.backgroundColor = 'rgba(8, 4, 4, 1))';
		$.blockUI.defaults.message = '<img src="./images/loading.svg">';

		// Insere o loader antes de todas requisições Jquery Ajax
		$(document).ajaxStart(parent.$.blockUI).ajaxStop(parent.$.unblockUI).ajaxError(parent.$.unblockUI);

		// Caso aconteça um erro de js no contexto da requisição e a tela não fique travada com o loader é executado UnblockUI na função global abaixo
		window.onerror = _onerror;
	}

	this.setCustomMessageOnErrorGrid = function () {

		dhtmlxError.catchError("LoadXML", gridErrorCustomMMS);

		function gridErrorCustomMMS(name, xhr){
				dhtmlx.alert({
		    		title:"Atenção!",
		    		type:"alert-error errorCustom",
		    		text: 'Erro ao carregar o grid. Entre em contato com o suporte técnico',
		    		width: "100%",
				});
		}
	}


	/* Validações para todas as chamadas AJAX (nao sendo chamado pelo DHtmlX) para validar as permissões às ações*/
	this.validateActionPermission = function() {

		$.ajaxPrefilter(function( options ) {
			if (typeof options.success != "undefined") {
				var originalSuccess = options.success;
				options.success = function (response) {
					if (response == null) {
						originalSuccess(response);
					} else {
						try {
							response = JSON.parse(response);
							if (typeof response.msgPermissaoAction != "undefined") {
								dhtmlx.alert({
									title:'Erro',
									type:'alert-error',
									text: response.msgPermissaoAction
								});
							   response.status = false;
							} else {
								originalSuccess(response);
								console.log('Possui permissão à ação. Cod. R1');
							}
						} catch (e) {
							if (typeof response.msgPermissaoAction != "undefined") {
								dhtmlx.alert({
									title:'Erro',
									type:'alert-error',
									text: response.msgPermissaoAction
								});
							   response.status = false;
							} else {
								originalSuccess(response);
								console.log('Possui permissão à ação. Cod. R2');
							}
						}
					}
				};
			}
		});
	}
	
	this.enableAjaxRequestBinaryData = function() {
		$.ajaxTransport("+binary", function(options, originalOptions, jqXHR){
		    // check for conditions and support for blob / arraybuffer response type
		    if (window.FormData && ((options.dataType && (options.dataType == 'binary')) || (options.data && ((window.ArrayBuffer && options.data instanceof ArrayBuffer) || (window.Blob && options.data instanceof Blob)))))
		    {
		        return {
		            // create new XMLHttpRequest
		            send: function(headers, callback){
				// setup all variables
		                var xhr = new XMLHttpRequest(),
				url = options.url,
				type = options.type,
				async = options.async || true,
				// blob or arraybuffer. Default is blob
				dataType = options.responseType || "blob",
				data = options.data || null,
				username = options.username || null,
				password = options.password || null;
							
		                xhr.addEventListener('load', function(){
					var data = {};
					data[options.dataType] = xhr.response;
					// make callback and send data
					callback(xhr.status, xhr.statusText, data, xhr.getAllResponseHeaders());
		                });
		 
		                xhr.open(type, url, async, username, password);
						
				// setup custom headers
				for (var i in headers ) {
					xhr.setRequestHeader(i, headers[i] );
				}
						
		                xhr.responseType = dataType;
		                xhr.send(data);
		            },
		            abort: function(){
		                jqXHR.abort();
		            }
		        };
		    }
		});
	}

};


(function() {

	// Executando as configurações globais

	GlobalHandler = new GlobalHandler();

	GlobalHandler.attachBlockUIAjaxRequest();

	GlobalHandler.setCustomMessageOnErrorGrid();

	GlobalHandler.validateActionPermission();
	
	GlobalHandler.enableAjaxRequestBinaryData();
})();
