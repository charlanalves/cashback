	function formataDataBR(data){
	    var dia = data.getDate();
	    if (dia.toString().length == 1)
	      dia = "0"+dia;
	    var mes = data.getMonth()+1;
	    if (mes.toString().length == 1)
	      mes = "0"+mes;
	    var ano = data.getFullYear();
	    return dia+"/"+mes+"/"+ano;
	}

	function formataHoraBR(data){
	    var hora = data.getHours();
	    if (hora.toString().length == 1)
	      hora = "0"+hora;
	    var minuto = data.getMinutes();
	    if (minuto.toString().length == 1)
	      minuto = "0"+minuto;
	    return hora+":"+minuto;
	}

	// converte xml em obj JSON
	function xml2json(xml) {
		try {
			var obj = {};
			if (xml.children.length > 0) {
				for (var i = 0; i < xml.children.length; i++) {
					var item = xml.children.item(i);
					var nodeName = item.nodeName;

					if (typeof (obj[nodeName]) == "undefined") {
						obj[nodeName] = xml2json(item);

					} else {
						if (typeof (obj[nodeName].push) == "undefined") {
							var old = obj[nodeName];
							obj[nodeName] = [];
							obj[nodeName].push(old);

						}
						obj[nodeName].push(xml2json(item));
					}
				}
			} else {
				obj = xml.textContent;
			}
		return obj;
		} catch (e) {
			console.log(e.message);
		}
	}

	function validaCheckList(nameCampo){
		alert('*** ESTE ARQIVO ENCONTR-SE EM: V2/libs/jquery/lib.js ***');
	}

	function str_pad(value, length, text, type = "left") {

		textValue = (type == "left") ? text+value : value+text;
		return (value.toString().length < length) ? str_pad(textValue, length,text, type ):value;
	}

	function CSVToArray( strData, strDelimiter ){
        // Check to see if the delimiter is defined. If not,
        // then default to comma.
        strDelimiter = (strDelimiter || ",");

        // Create a regular expression to parse the CSV values.
        var objPattern = new RegExp(
            (
                // Delimiters.
                "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

                // Quoted fields.
                "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

                // Standard fields.
                "([^\"\\" + strDelimiter + "\\r\\n]*))"
            ),
            "gi"
            );


        // Create an array to hold our data. Give the array
        // a default empty first row.
        var arrData = [[]];

        // Create an array to hold our individual pattern
        // matching groups.
        var arrMatches = null;


        // Keep looping over the regular expression matches
        // until we can no longer find a match.
        while (arrMatches = objPattern.exec( strData )){

            // Get the delimiter that was found.
            var strMatchedDelimiter = arrMatches[ 1 ];

            // Check to see if the given delimiter has a length
            // (is not the start of string) and if it matches
            // field delimiter. If id does not, then we know
            // that this delimiter is a row delimiter.
            if (
                strMatchedDelimiter.length &&
                strMatchedDelimiter !== strDelimiter
                ){

                // Since we have reached a new row of data,
                // add an empty row to our data array.
                arrData.push( [] );

            }

            var strMatchedValue;

            // Now that we have our delimiter out of the way,
            // let's check to see which kind of value we
            // captured (quoted or unquoted).
            if (arrMatches[ 2 ]){

                // We found a quoted value. When we capture
                // this value, unescape any double quotes.
                strMatchedValue = arrMatches[ 2 ].replace(
                    new RegExp( "\"\"", "g" ),
                    "\""
                    );

            } else {

                // We found a non-quoted value.
                strMatchedValue = arrMatches[ 3 ];

            }


            // Now that we have our value string, let's add
            // it to the data array.
            arrData[ arrData.length - 1 ].push( strMatchedValue );
        }

        // Return the parsed data.
        return( arrData );
    }


	/*
	Parameters: a - array of keys to be used, b - array of values to be used
	IMPORTANT: The number of elements for each array must be equal
	*/
	function array_combine(a, b) {
		if(a.length != b.length) {
			return false;
		} else {
			new_array = new Array();
			for (i = 0; i < a.length; i++) {
				new_array[a[i]] = b[i];
			}
			return new_array;
		}
	}

	function getUnique(array) {
		if( Object.prototype.toString.call( array ) === '[object Array]' ) {
			 for (var i=0; i<array.length; i++) {
				var listI = array[i];
				loopJ: for (var j=0; j<array.length; j++) {
				    var listJ = array[j];
				    if (listI === listJ) continue; //Ignore itself
				    for (var k=listJ.length; k>=0; k--) {
					if (listJ[k] !== listI[k]) continue loopJ;
				    }

				    // At this point, their values are equal.
				    array.splice(j, 1);
				}
			  }
			return array;
		}
	}

	function addQuotesCsvStr(str, delimiter){
		delimiter = (delimiter || ",");

		return sprintf( "'%s'", str.replace( delimiter, "'" + delimiter + "'" ) );
	}

	function sprintf() {
	 var regex = /%%|%(\d+\$)?([\-+'#0 ]*)(\*\d+\$|\*|\d+)?(?:\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g
		  var a = arguments
		  var i = 0
		  var format = a[i++]

		  var _pad = function (str, len, chr, leftJustify) {
		    if (!chr) {
		      chr = ' '
		    }
		    var padding = (str.length >= len) ? '' : new Array(1 + len - str.length >>> 0).join(chr)
		    return leftJustify ? str + padding : padding + str
		  }

		  var justify = function (value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
		    var diff = minWidth - value.length
		    if (diff > 0) {
		      if (leftJustify || !zeroPad) {
		        value = _pad(value, minWidth, customPadChar, leftJustify)
		      } else {
		        value = [
		          value.slice(0, prefix.length),
		          _pad('', diff, '0', true),
		          value.slice(prefix.length)
		        ].join('')
		      }
		    }
		    return value
		  }

		  var _formatBaseX = function (value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
		    // Note: casts negative numbers to positive ones
		    var number = value >>> 0
		    prefix = (prefix && number && {
		      '2': '0b',
		      '8': '0',
		      '16': '0x'
		    }[base]) || ''
		    value = prefix + _pad(number.toString(base), precision || 0, '0', false)
		    return justify(value, prefix, leftJustify, minWidth, zeroPad)
		  }

		  // _formatString()
		  var _formatString = function (value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
		    if (precision !== null && precision !== undefined) {
		      value = value.slice(0, precision)
		    }
		    return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar)
		  }

		  // doFormat()
		  var doFormat = function (substring, valueIndex, flags, minWidth, precision, type) {
		    var number, prefix, method, textTransform, value

		    if (substring === '%%') {
		      return '%'
		    }

		    // parse flags
		    var leftJustify = false
		    var positivePrefix = ''
		    var zeroPad = false
		    var prefixBaseX = false
		    var customPadChar = ' '
		    var flagsl = flags.length
		    var j
		    for (j = 0; j < flagsl; j++) {
		      switch (flags.charAt(j)) {
		        case ' ':
		          positivePrefix = ' '
		          break
		        case '+':
		          positivePrefix = '+'
		          break
		        case '-':
		          leftJustify = true
		          break
		        case "'":
		          customPadChar = flags.charAt(j + 1)
		          break
		        case '0':
		          zeroPad = true
		          customPadChar = '0'
		          break
		        case '#':
		          prefixBaseX = true
		          break
		      }
		    }

		    // parameters may be null, undefined, empty-string or real valued
		    // we want to ignore null, undefined and empty-string values
		    if (!minWidth) {
		      minWidth = 0
		    } else if (minWidth === '*') {
		      minWidth = +a[i++]
		    } else if (minWidth.charAt(0) === '*') {
		      minWidth = +a[minWidth.slice(1, -1)]
		    } else {
		      minWidth = +minWidth
		    }

		    // Note: undocumented perl feature:
		    if (minWidth < 0) {
		      minWidth = -minWidth
		      leftJustify = true
		    }

		    if (!isFinite(minWidth)) {
		      throw new Error('sprintf: (minimum-)width must be finite')
		    }

		    if (!precision) {
		      precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type === 'd') ? 0 : undefined
		    } else if (precision === '*') {
		      precision = +a[i++]
		    } else if (precision.charAt(0) === '*') {
		      precision = +a[precision.slice(1, -1)]
		    } else {
		      precision = +precision
		    }

		    // grab value using valueIndex if required?
		    value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++]

		    switch (type) {
		      case 's':
		        return _formatString(value + '', leftJustify, minWidth, precision, zeroPad, customPadChar)
		      case 'c':
		        return _formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad)
		      case 'b':
		        return _formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
		      case 'o':
		        return _formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
		      case 'x':
		        return _formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
		      case 'X':
		        return _formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
		        .toUpperCase()
		      case 'u':
		        return _formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
		      case 'i':
		      case 'd':
		        number = +value || 0
		        // Plain Math.round doesn't just truncate
		        number = Math.round(number - number % 1)
		        prefix = number < 0 ? '-' : positivePrefix
		        value = prefix + _pad(String(Math.abs(number)), precision, '0', false)
		        return justify(value, prefix, leftJustify, minWidth, zeroPad)
		      case 'e':
		      case 'E':
		      case 'f': // @todo: Should handle locales (as per setlocale)
		      case 'F':
		      case 'g':
		      case 'G':
		        number = +value
		        prefix = number < 0 ? '-' : positivePrefix
		        method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())]
		        textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2]
		        value = prefix + Math.abs(number)[method](precision)
		        return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]()
		      default:
		        return substring
		    }
		  }

		  return format.replace(regex, doFormat)
		}

	/*
	Validações para todas as chamadas AJAX (nao sendo chamado pelo DHtmlX) para validar as permissões às ações
	*/
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
							//console.log('Possui permissão à ação. Cod. R1');
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
							//console.log('Possui permissão à ação. Cod. R2');
						}
					}
				}
			};
		}
	});

	/*
	Ajusta window automaticamente de acordo com o tamanho do formulario apenas manipulando o DOM
	*/
	function WinHeight(){
		var heightWin = $(".dhtmlx_window_active").height();
		var heightForm = $(".dhxform_base").height();
		if (heightWin == 0) {
			parseInt(document.getElementsByClassName("dhtmlx_wins_body_outer")[0].style.height = heightForm+42);
			//console.log("Altura automatica");
		} else {
			//console.log("Altura manual "+heightWin);
		}
	}

	function WinWidth(){
		var widthWin = $(".dhtmlx_window_active").width();
		var widthForm = $(".dhxform_base").width();

		if (widthWin == 0) {
			parseInt(document.getElementsByClassName("dhtmlx_wins_body_outer")[0].style.width = widthForm+30);
			//console.log("Largura automatica");
		} else{
			//console.log("Largura manual "+widthWin);
		}
	}

	/*
		Ajuste automatico Window DHTMLX - fornecendo os valores para o DHTML atraves de seleção de objetos no DOM

		#################### Instruções para utilização ####################
		>	A função deve ser inicializada após o Form.show() na view.
			Exemplo:	WinSizeAuto(form,id,domL,domA)
						WinSizeAuto(SYSTEM.Windows,"actions",1,1)

		>	form = form DHTMLX
		>	id = id do formulário.
		>	domL = numero do array do DOM que deverá ser considerado para calcular a largura da window.
		>	domL = numero do array do DOM  que deverá ser considerado para calcular a altura da window.
	*/
	function WinSizeAuto(form,id,domL,domA){

		function wForm(){
			var widthForm = parseInt($(".dhxform_base").eq(domL).width());
			var widthWin = widthForm+30;

			return widthWin;
		}

		function hForm(){
			var heightForm = parseInt($(".dhxform_base").eq(domA).height());
			var heightWin = heightForm+45;

			return heightWin;
		}

		x = wForm();
		y = hForm();
		form.window(id).setDimension(x, y);
		form.window(id).center();
		console.log("Win auto ok.");
	}

	/*
		Ajuste automatico Window DHTMLX versao 1.5 Beta do Gerador - fornecendo os valores para o DHTML atraves de seleção de objetos no DOM

		#################### Instruções para utilização ####################
		>	A função deve ser inicializada após o Form.show() na view.
			Exemplo:	WinSizeAutoN(form,domL,domA)
						WinSizeAutoN(Form.windowMain,1,1)

		>	form = form DHTMLX
		>	domL = numero do array do DOM que deverá ser considerado para calcular a largura da window.
		>	domL = numero do array do DOM  que deverá ser considerado para calcular a altura da window.
	*/
	function WinSizeAutoN(form,domL,domA){

		function wForm(){
			var widthForm = parseInt($(".dhxform_base").eq(domL).width());
			var widthWin = widthForm+30;

			return widthWin;
		}

		function hForm(){
			var heightForm = parseInt($(".dhxform_base").eq(domA).height());
			var heightWin = heightForm+45;

			return heightWin;
		}

		x = wForm();
		y = hForm();
		form.setDimension(x, y);
		form.center();
		console.log("Win auto ok.");
	}

	/*
		Ajuste automatico cabeçalho celula
	*/
	var buttonCell = "";
	var parentButton = "";
	var parentButtonParent = "";

	function align_button_cell() {;
		buttonCell = $(".button-cell-icon");
		parentButton = $(buttonCell).parent();
		parentButtonParent = $(parentButton).parent();

		$(parentButtonParent).addClass("display_none_2");
		$(parentButtonParent).attr('id', 'idParentButtonParent');

		$(parentButtonParent).each(function() {
			if ($(this).attr("id") === "idParentButtonParent") {
				var id = $(this).attr("id");
				var ButtonFake1 = "#"+id+" "+"div:nth-child(5)";

				$(ButtonFake1).each(function() {
					if (this.style.display === "none") {
						$(this).siblings('.dhtmlxInfoBarLabel').css({"padding": "0px 1px 0px 12px"});
					} else{
						$(this).siblings('.dhtmlxInfoBarLabel').css({"padding": "0px 16px 0px 12px"});
					}
				});
			}
		});
		}

		$(document).ready(function(){
			align_button_cell();
	});

	function loadCSSForm(url) {
		var lnk = document.createElement('link');
		lnk.setAttribute('type', "text/css" );
		lnk.setAttribute('rel', "stylesheet" );
		lnk.setAttribute('href', "/MMS/cnhind/mms/V2/libs/dhtmlx/terrace/window_form.css" );
		document.getElementsByTagName("head").item(0).appendChild(lnk);
	}

	function loadCSSLayout(url) {
		var lnk = document.createElement('link');
		lnk.setAttribute('type', "text/css" );
		lnk.setAttribute('rel', "stylesheet" );
		lnk.setAttribute('href', "/MMS/cnhind/mms/V2/libs/dhtmlx/terrace/window_layout.css" );
		document.getElementsByTagName("head").item(0).appendChild(lnk);
	}
