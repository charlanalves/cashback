<?php
if (!empty($error)) {
    echo '<tr><td colspan="6"><h2>' . $error . '<h2></td></tr>';
    exit();
} else {

    $getStatusDelivery = function ($cod, $status) {
        $retorno = [];
        switch ($cod) {
            case '1':
                $retorno['label'] = 'danger';
                $retorno['text'] = $status[$cod];
                $retorno['disabled'] = 'disabled';
            break;
            case '2':
                $retorno['label'] = 'primary';
                $retorno['text'] = $status[$cod];
            break;
            case '3':
                $retorno['label'] = 'success';
                $retorno['text'] = $status[$cod];
            break;
            default:
                $retorno['label'] = 'danger';
                $retorno['text'] = '';
            break;
        }
        return $retorno;
    };

    $optionsStatus = function ($status, $default = false) {
        $opt = '';
        foreach ($status as $key => $value) {
            $opt .= "<option value='" . $key . "' " . ($default == $key ? 'selected' : '') . ">" . $value . "</option>";
        }
        return $opt;
    };

    foreach ($pedidos as $value) {

        $pedido = $value['CB16_ID'];
        $dt = $value['CB16_DT_APROVACAO'];
        $cliente = $value['name'];
        //$telefone = $value['telefone'];
        $endereco = $value['CB16_COMPRADOR_END_LOGRADOURO'] . ', ' . $value['CB16_COMPRADOR_END_NUMERO'] . ' - ' . $value['CB16_COMPRADOR_END_BAIRRO'] . ' - ' . $value['CB16_COMPRADOR_END_CIDADE'] . '/' . $value['CB16_COMPRADOR_END_UF'] . '<br />' . $value['CB16_COMPRADOR_END_COMPLEMENTO'];
        $produto = $value['CB17_NOME_PRODUTO'];
        $statusDelivery = $value['CB16_STATUS_DELIVERY'];
        $currentStatus = $getStatusDelivery($statusDelivery, $status);


        //$entrega_inicial = ($value['CB16_DT_APROVACAO'] && $value['CB06_TEMPO_MIN']) ? \Yii::$app->u->addMinutesToDateTime($value['CB16_DT_APROVACAO'], $value['CB06_TEMPO_MIN']) : '';
        //$entrega_termino = ($value['CB16_DT_APROVACAO'] && $value['CB06_TEMPO_MAX']) ? \Yii::$app->u->addMinutesToDateTime($value['CB16_DT_APROVACAO'], $value['CB06_TEMPO_MAX']) : '';

        ?>
        <tr id="pedido-<?= $pedido ?>">
            <td>
                <h6 class="no-margin text-align-center ">
                    <?= $pedido ?>
                </h6>
            </td>
            <td>
                <h6 class="no-margin text-align-center ">
                    <?= $dt ?>
                </h6>
            </td>
            <td>
                <h6 class="no-margin">
                    <?= $cliente ?><br />
                    <small class="font-xs"><?= $endereco ?></small>
                </h6>
            </td>
            <td>
                <h6 class="no-margin">
                    <?= $produto ?>
                </h6>
            </td>
            <td class="text-align-center label-<?= $currentStatus['label'] ?>">
                <select onchange="setStatusDelivery(this, <?= $pedido ?>)">
                    <?= $optionsStatus($status, $statusDelivery) ?>
                </select>
            </td>
        </tr>
        <?php
    }
}
?>