var Form = function (formId) {
    this.form = $('#' + formId),
    this.getMoney = [],
    this.setMoney = function (input) { 
        if(typeof input != 'undefined' && Array.isArray(input)) {
            for (var i in input) {
                $(this[input[i]]).maskMoney({thousands:'.', decimal:',', allowZero: true});
                this.getMoney.push(input[i]);
            }
        }
    },
    this.inputs = function () {
        var $inputs = this.form.find(':input'), data = {};
        $inputs.each(function (k, v) {
            if (v.name) {
                data[v.name] = this;
            }
        });
        return data;
    },
    this.send = function (url, callback)
    {
        var ajax = $.ajax({
            url: url,
            type: 'POST',
            data: this.getFormData(),
            dataType: "json"
        });
        ajax.always(function (data) {
            if (typeof callback == 'function')
                callback(data);
        });
    },
    this.getFormData = function ()
    {
        var form = this, $inputs = form.form.find(':input'), data = {};
        $inputs.each(function (k, v) {
            if (v.name) {
                if (v.type == 'checkbox' || v.type == 'radio') {
                    if(typeof data[v.name] == 'undefined'){
                        data[v.name] = [];
                    }
                    if ($(this).is(':checked')) {
                        data[v.name].push(v.value);
                    }
                } else {
                    // teste se é valor monetario
                    if (form.getMoney.indexOf(v.name) !== -1) {
                        data[v.name] = $(form[v.name]).maskMoney('unmasked')[0];
                    } else {
                        data[v.name] = v.value;
                    }
                }
            }
        });
        return data;
    },
    this.setFormData = function (data)
    {
        var $inputs = this.form.find(':input');
        $inputs.each(function (k, v) {
            nameCompare = v.name.replace('[', '').replace(']', '');
            // input
            if (typeof data[nameCompare] != "undefined") {

                if (v.type == 'checkbox' || v.type == 'radio') {
                    if (Object.prototype.toString.call(data[nameCompare]) == "[object Array]") {
                        for (var i in data[nameCompare]) {
                            if ($(this).val() == data[nameCompare][i]) {
                                $(this).prop('checked', true);
                            }
                        }
                    } else {
                        if ($(this).val() == data[nameCompare]) {
                            $(this).prop('checked', true);
                        }
                    }

                } else {
                    $(this).val(data[nameCompare]);

                }
            }
        });
    },
    this.addOptionsSelect = function (selectName, data)
    {
        var select = this.form.find('select[name=' + selectName + ']');
        $.each(data, function (key, value) {
            if (typeof value == "object") {
                key = value.ID;
                value = value.TEXTO;
            }
            select.append($("<option></option>").attr("value", key).text(value));
        });
    },
    this.addCheckboxInLine = function (destinyId, checkboxName, data)
    {
        var destiny = this.form.find('#' + destinyId), checkbox = '';
        $.each(data, function (key, value) {
            if (typeof value == "object") {
                key = value.ID;
                value = value.TEXTO;
            }
            checkbox += '<label class="checkbox"><input type="checkbox" name="' + checkboxName + '[]" value="' + key + '"><i></i>' + value + '</label>' + "\n";
        });
        destiny.append($("<div></div>").attr("class", "inline-group").html(checkbox));
    };
    
    var $inputs = this.form.find(':input'), data = this;
    $inputs.each(function (k, v) {
        if (v.name) {
            data[v.name] = this;
        }
    });
    
};

var Util = {
    copyElement: function ($element)
    {
        $element.select();
        this.copyText($element.text());
    },
    copyText: function (text)
    {
        var $tempInput = $("<textarea>");
        $("body").append($tempInput);
        $tempInput.val(text).select();
        document.execCommand("copy");
        $tempInput.remove();

        $.smallBox({
            title: "Copiado...",
            color: "#739E73",
            iconSmall: "fa fa-copy",
            timeout: 1000
        });
    },
    formatNumber: function (n, c, d, t)
    {
        var n,
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    },
    getEnderecoByCEP: function (cep, callback) {
        cep = cep.replace('-', '');
        var ajax = $.ajax({
            url: 'http://viacep.com.br/ws/' + cep + '/json/',
            //type: 'GET',
            dataType: "json"
        });
        ajax.always(function (data) {
            callback(data);
        });
    },
    smallBox: function (title, message, type, ico, time) {
        var title = (title || ''), message = (message || ''), type = (type || 'success'), ico = (ico || ''), time = (time || 4000);
        switch (type){
            case 'success': color = "#739E73"; break; // verde
            case 'default': color = "#999"; break; // cinza
            case 'primary': color = "#3276B1"; break; // azul
            case 'info': color = "#57889C"; break; // azul claro
            case 'danger': color = "#A90329"; break; // vermelho
            case 'warning': color = "#C79121"; break; // laranja
            default: color = "#FFF";
        };
        $.smallBox({
            title: title,
            content: message,
            color: color,
            iconSmall: "fa fa-" + ico + " fadeInRight animated",
            timeout: time
        });
    }
};
