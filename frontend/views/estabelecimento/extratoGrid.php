<?php
if (!empty($error)) {
    echo '<tr><td colspan="6"><h2>' . $error . '<h2></td></tr>';
    exit();
    
} else {
    
    foreach ($extrato as $value) {

        $transId = $value['TRANSFERENCIA_ID'];
        $dtOp = \Yii::$app->u->dateBR($value['DT_CRIACAO']);
        $dtPrev = \Yii::$app->u->dateBR($value['DT_PREVISAO']);
//        $dtPag = \Yii::$app->u->dateBR($value['DT_DEPOSITO']);
        $ref = $value['PEDIDO_ID'];
        $descricao = $tipo[$value['TIPO']];
        $valor = 'R$ ' . \Yii::$app->u->moedaReal($value['VALOR']);
        
        ?>
        <tr id="pedido-<?= $transId ?>">
            <td class="text-align-center">
                <?= $dtOp ?>
            </td>
            <td class="text-align-center">
                <?= $dtPrev ?>
            </td>
            <!--<td class="text-align-center"></td>-->
            <td>
                <h6 class="no-margin text-align-center">
                    <?= $ref ?>
                </h6>
            </td>
            <td>
                <?= $descricao ?>
                <!--<small class="font-xs"><?= '' ?></small>-->
            </td>
            <td>
                <h6 class="no-margin text-align-right" style="color: <?= ($value['VALOR'] > 0) ? 'blue' : 'red' ?>" >
                    <?= $valor ?>
                </h6>
            </td>
        </tr>
        <?php
    }
}
?>