
function globalGetEnderecoByCEP (cep, callback) {
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
     }
};
