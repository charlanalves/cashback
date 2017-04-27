<?php
/* @var $this yii\web\View */
?>

<script type="text/javascript">

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
            ?>
            <tr>
                <td style="width: 100%"><?= $value['PRODUTO'] ? : $value['VARIACAO'] ?></td>
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
                <td align="center"><button class="btn btn-danger btn-xs no-margin" style="height: 100%" onclick="excluirCahsback()">Excluir &nbsp;<i class="fa fa-trash-o"></i></button></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>