<?php
if (!empty($error)) {
    echo '<tr><td colspan="6"><h2>' . $error . '<h2></td></tr>';
    exit();
} else {

    $statusPedido = function ($cod, $status) {
        $retorno = [];
        switch ($cod) {
            case '1':
                $retorno['label'] = 'danger';
                $retorno['text'] = $status[$cod];
                $retorno['disabled'] = 'disabled';
            break;
            case '10':
                $retorno['label'] = 'danger';
                $retorno['text'] = $status[$cod];
                $retorno['disabled'] = 'disabled';
            break;
            case '20':
                $retorno['label'] = 'primary';
                $retorno['text'] = $status[$cod];
                $retorno['disabled'] = 'disabled';
            break;
            case '30':
                $retorno['label'] = 'success';
                $retorno['text'] = $status[$cod];
                $retorno['disabled'] = 'onclick="baixarCompra(this)"';
            break;
            default:
                $retorno['label'] = 'danger';
                $retorno['text'] = '';
                $retorno['disabled'] = 'disabled';
            break;
        }
        return $retorno;
    };

    foreach ($pedidos as $value) {

        $pedido = $value['CB16_ID'];
        $dt = $value['CB16_DT'];
        $cliente = $value['name'];
        $produto = $value['CB17_NOME_PRODUTO'];
        $currentStatus = $statusPedido($value['CB16_STATUS'], $status);

        ?>
        <tr id="pedido-<?= $pedido ?>">
            <td>
                <h6 class="no-margin">
                    <?= $pedido ?>
                </h6>
            </td>
            <td>
                <h6 class="no-margin">
                    <?= $dt ?>
                </h6>
            </td>
            <td>
                <h6 class="no-margin">
                    <?= $cliente ?><br />
                    <small class="font-xs"><?= $cpf ?></small>
                </h6>
            </td>
            <td>
                <h6 class="no-margin">
                    <?= $produto ?>
                </h6>
            </td>
            <td class="text-align-center label-<?= $currentStatus['label'] ?>">
                <label class="labelStatusCliente text-align-center"> <?= $currentStatus['text'] ?> </label>
            </td>
            <td>
                <button class="btn btn-success btn-small" value="<?= $pedido ?>" <?= $currentStatus['disabled'] ?>>BAIXAR</button>
            </td>
        </tr>
        <?php
    }
}
?>