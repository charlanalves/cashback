
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