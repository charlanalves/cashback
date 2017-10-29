// Para usar essa função coloque o id no elemento html igual ao nome da imagem que deseja mostrar no tooltip e acrescente uma classe ao mesmo elemento:
// Ex <span id="titulo" class="tooltipestalecas">(Será exibido abaixo do nome da empresa.)</span>
// Em seguida execute passe a classe desejada para a função: $('.tooltipestalecas').tooltipsterESTALECAS();
$.fn.tooltipsterESTALECAS = function () {
    this.each(function (k) {
        var id = $(this).attr('id');
        var data = id + k;
        var tmpl =
                '<div class="tooltip_templates" style="display:none">' +
                    '<span id="' + data + '">' +
                        '<img src="img/tooltips/' + id + '.png" />' +
                    '</span>' +
                '</div>';
        $(this).attr("data-tooltip-content", "#" + data);
        $(this).prepend(tmpl);
    });
    $(this).tooltipster({});
} 
