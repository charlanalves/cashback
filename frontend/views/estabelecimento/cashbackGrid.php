<?php
/* @var $this yii\web\View */
?>

<script type="text/javascript">
    function excluirCashback(param) {
        $.SmartMessageBox({
            title: "Deseja excluir o Cashback?",
            buttons: '[Não][Sim]'
        }, function (ButtonPressed) {
            if (ButtonPressed === "Sim") {
                var ajax = $.ajax({
                    url: 'index.php?r=estabelecimento/global-crud&action=deleteCashback',
                    type: 'POST',
                    data: param,
                    dataType: "json"
                });
                ajax.always(function (data) {
                    if (data.responseText) {
                        Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                    } else {
                        loadGridCashback(produto.CB05_ID);
                    }
                });
            }
        });
    }
</script>

<style>

</style>

<table class="table table-bordered padding-10">
    <thead>
        <tr>
            <th colspan="3" style="text-align: left">CASHBACK CADASTRADO</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($cashback as $value) {
            if ($value['PRODUTO']) {
                $texto = $value['PRODUTO'];
                $param = "{CB07_PRODUTO_ID:" . $value['PRODUTO_ID'] . "}";
            } else {
                $texto = $value['VARIACAO'];
                $param = "{CB07_VARIACAO_ID:" . $value['VARIACAO_ID'] . "}";
            }
            ?>
            <tr>
                <td style="width: 100%"><?= $texto ?></td>
                <td>
                    <table class="table table-bordered table-striped table-cashback-produto no-margin">
                        <thead>
                            <tr>
                                <th>
                                    <div>SEG<br><span><?= $value['DIA_SEG'] ? : '0,00' ?>%</span></div>
                                </th>
                                <th>
                                    <div>TER<br><span><?= $value['DIA_TER'] ? : '0,00' ?>%</span></div>
                                </th>
                                <th>
                                    <div>QUA<br><span><?= $value['DIA_QUA'] ? : '0,00' ?>%</span></div>
                                </th>
                                <th>
                                    <div>QUI<br><span><?= $value['DIA_QUI'] ? : '0,00' ?>%</span></div>
                                </th>
                                <th>
                                    <div>SEX<br><span><?= $value['DIA_SEX'] ? : '0,00' ?>%</span></div>
                                </th>
                                <th>
                                    <div>SAB<br><span><?= $value['DIA_SAB'] ? : '0,00' ?>%</span></div>
                                </th>
                                <th>
                                    <div>DOM<br><span><?= $value['DIA_DOM'] ? : '0,00' ?>%</span></div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </td>
                <td align="center"><button class="btn btn-danger btn-xs no-margin" style="height: 100%" onclick="excluirCashback(<?= $param ?>)">Excluir &nbsp;<i class="fa fa-trash-o"></i></button></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>