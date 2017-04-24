var Form = function (formId) {
    this.form = $('#' + formId);
    this.setFormData = function (data)
    {
        var $inputs = this.form.find(':input');
        $inputs.each(function(k, v) {
            nameCompare = v.name.replace('[','').replace(']','');
            // input
            if(typeof data[nameCompare] != "undefined"){
                
                if(v.type == 'checkbox' || v.type == 'radio'){
                    if(Object.prototype.toString.call(data[nameCompare]) == "[object Array]"){
                       for(var i in data[nameCompare]){
                            if($(this).val() == data[nameCompare][i]){
                               $(this).prop('checked', true);
                           }
                       }
                    } else {
                        if($(this).val() == data[nameCompare]){
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
        $.each(data, function(key, value) {
            if(typeof value == "object"){
                key = value.ID;
                value = value.TEXTO;
            }
            select.append($("<option></option>").attr("value",key).text(value)); 
        });
    },
    this.addCheckboxInLine = function (destinyId, checkboxName, data)
    {
        var destiny = this.form.find('#' + destinyId), checkbox = '';
        $.each(data, function(key, value) {
            if(typeof value == "object"){
                key = value.ID;
                value = value.TEXTO;
            }
            checkbox += '<label class="checkbox"><input type="checkbox" name="' + checkboxName + '[]" value="' + key + '"><i></i>' + value + '</label>'+"\n";
        });
        destiny.append($("<div></div>").attr("class","inline-group").html(checkbox)); 
    }
};

var Util = {
    copyElement: function ($element)
    {
        $element.select();
        this.copyText($element.text());  
    },
    copyText:function(text)
    {
        var $tempInput =  $("<textarea>");
        $("body").append($tempInput);
        $tempInput.val(text).select();
        document.execCommand("copy");
        $tempInput.remove();

        $.smallBox({
            title : "Copiado...",
            color : "#739E73",
            iconSmall : "fa fa-copy",
            timeout : 1000
        });
    },
    formatNumber: function(n, c, d, t)
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
    getEnderecoByCEP: function(cep, callback) {
        cep = cep.replace('-','');
        var ajax = $.ajax({
            url: 'http://viacep.com.br/ws/'+ cep +'/json/',
            //type: 'GET',
            dataType: "json"
        });
        ajax.always(function (data) {
            callback(data);
        });
    }
};
