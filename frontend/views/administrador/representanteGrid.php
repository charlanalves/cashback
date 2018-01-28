<?php
if (!empty($error)) {
    echo '<tr><td colspan="4"><h2>' . $error . '<h2></td></tr>';
    exit();
} else {

    foreach ($representantes as $value) {

        $cod = $value['CB04_ID'];
        $nome = $value['CB04_NOME'];
        $currentStatus = ($value['CB04_STATUS'] ? 'checked' : '');
        ?>
        <tr id="pedido-<?= $cod ?>">
            <td>
                <h6 class="no-margin text-align-center">
                    <?= $cod ?>
                </h6>
            </td>
            <td>
                <h6 class="no-margin">
                    <?= $nome ?>
                </h6>
            </td>
            <td class="smart-form text-align-center" style="padding: 5px 16px;">
                <label class="checkbox">
                    <input type="checkbox" id="ativo-<?= $cod ?>" onchange="gridRepresentante.representanteAtivo(<?= $cod ?>)" value="<?= $cod ?>" <?= $currentStatus ?> /><i></i>
                </label>
            </td>
            <td>
                <select class="btn btn-primary btn-xs" onchange="gridRepresentante.acaoRepresentante($(this), <?= $cod ?>)">
                    <option value="">Selecione</option>
                    <option value="editar">Editar</option>
                    <!--<option value="excluir">Excluir</option>-->
                </select>
            </td>
        </tr>
        <?php
    }
}
?>

<script>

    var gridRepresentante = {};

    gridRepresentante.representanteAtivo = function (id) {
        var checkbox = $('#ativo-' + id)[0], 
            status = checkbox.checked, 
            callback = function (data) {
                if (data.responseText) {
                    checkbox.checked = !status;
                    Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                }
            };
            
        Util.ajaxGet('index.php?r=administrador/representante-ativar&representante=' + id + '&status=' + (status ? 1 : 0), false, callback);
        
    };

    gridRepresentante.acaoRepresentante = function (obj, id) {
        var tpAcao = obj.val();
        gridRepresentante[tpAcao](id);
        obj.val('');
    };

    gridRepresentante.editar = function (id) {
        modalRepresentante(id);
    };

    gridRepresentante.excluir = function (id) {
        
    };

</script>