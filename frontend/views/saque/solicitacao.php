<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$fieldOptions = [
    'CB03_COD_BANCO' => ['options' => ['class' => ''], 'inputTemplate' => "{input}<span class=''></span>"],
    'CB03_AGENCIA' => ['options' => ['class' => ''], 'inputTemplate' => "{input}<span class=''></span>"],
    'CB03_NUM_CONTA' => ['options' => ['class' => ''], 'inputTemplate' => "{input}<span class=''></span>"],
    'CB03_TP_CONTA' => ['options' => ['class' => ''], 'inputTemplate' => "{input}<span class=''></span>"],
    'CB03_VALOR' => ['options' => ['class' => ''], 'inputTemplate' => "{input}<span class=''></span><div class='note'>O valor mínimo para realizar o saque é de <strong>R$ " . number_format($this->params['saqueMin'], 2, ",", ".") . "</strong></div>"],
];

?>

<div class="row">

    <div class="col-sm-12 col-md-12 col-lg-12 col-no-padding">

        <div class="well well-light well-sm">

            <div class="row">

                <?php if (!$this->params['saldo']) { ?>
                
                    <div class="col-sm-12">
                        <p class="alert alert-warning no-margin">
                            <span class="glyphicon glyphicon-info-sign"></span>&nbsp; Você não possui saldo suficiente para sacar, o valor mínimo é de <?= "<strong>R$ " . number_format($this->params['saqueMin'], 2, ",", ".") . "</strong>" ?>.
                        </p>
                    </div>
                
                <?php } else { ?>
                
                    <div class="col-sm-12">

                        <?php $form = ActiveForm::begin(['id' => 'solicitacao-saque-form', 'enableClientValidation' => false, 'class' => 'smart-form']); ?>

                        <?= $form
                            ->field($conta_bancaria, 'CB03_COD_BANCO', $fieldOptions['CB03_COD_BANCO'])
                            ->label('Banco')
                            ->dropDownList(\Yii::$app->u->getBancos()) ?>

                        <?= $form
                            ->field($conta_bancaria, 'CB03_AGENCIA', $fieldOptions['CB03_AGENCIA'])
                            ->label('Agência')
                            ->textInput(['']) ?>

                        <?= $form
                            ->field($conta_bancaria, 'CB03_NUM_CONTA', $fieldOptions['CB03_NUM_CONTA'])
                            ->label('Conta')
                            ->textInput(['']) ?>

                        <?= $form
                            ->field($conta_bancaria, 'CB03_TP_CONTA', $fieldOptions['CB03_TP_CONTA'])
                            ->label('Tipo')
                            ->dropDownList(\Yii::$app->u->getTipoContaBancaria()) ?>

                        <?= $form
                            ->field($conta_bancaria, 'CB03_VALOR', $fieldOptions['CB03_VALOR'])
                            ->label('Valor')
                            ->textInput(['data-affixes-stay' => true,
                                        'data-prefix' => 'R$ ',
                                        'data-thousands' => '.',
                                        'data-decimal' => ',',
                                        'data-allow-zero' => true,
                                ]) ?>

                        <p class="alert alert-warning no-margin">
                            <span class="glyphicon glyphicon-info-sign"></span>&nbsp; O Saque permite apenas transferência para conta bancária registradas com o mesmo CPF desta conta CashBack.
                        </p>

                        <?= Html::submitButton('SOLICITAR SAQUE', ['class' => 'btn btn-success btn-block btn-flat margin-top-10', 'name' => 'btn-solicitar-saque']) ?>

                        <?php ActiveForm::end(); ?>

                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<script>
    
    var valorMinimo = parseFloat(<?= $this->params['saqueMin'] ?>);
    var valorMaximo = parseFloat(<?= $this->params['saqueMax'] ?>);

    document.addEventListener("DOMContentLoaded", function (event) {
        
        valorSolicitacao = $('input#cb03contabanc-cb03_valor');
        valorSolicitacao.maskMoney();
        
        $('form#solicitacao-saque-form').on('submit', function(){
            
            var message = '', 
                v = parseFloat(valorSolicitacao.maskMoney('unmasked')[0]);
            
            if(valorMinimo > v) {
                message = 'O valor mínimo para saque é de <strong>R$ ' + Util.formatNumber(valorMinimo, 2, '.', ',') + '</strong>';
                
            } else if (valorMaximo < v) {
                message = 'O valor máximo para saque é de <strong>R$ ' + Util.formatNumber(valorMaximo, 2, '.', ',') + '</strong>';

            }

            if (message) {
                $.smallBox({
                        title : message,
                        content : "<p class='text-align-right'><a href='javascript:valorSolicitacao.focus(); void(0);' class='btn btn-default btn-sm'>Entendi &nbsp;<i class='fa fa-thumbs-up'></i></a></p>",
                        color : "#3276B1",
                        //timeout: 8000,
                        icon : "fa fa-bell swing animated"
                });
                return false;
            }
            
            return true;
        });

        <?php if ($solicitacao_criada) { ?>
            setTimeout(function () {$.smallBox({
                title : "Solicitação de saque realizada",
                content : "<i class='fa fa-clock-o'></i> <i>O valor será transferido em até 48hs...</i>",
                color : "#739E73",
                iconSmall : "fa fa-thumbs-up bounce animated",
                timeout : 10000
            });}, 0);
        <?php } ?>
        

    
    });
</script>

<style>
    div.SmallBox div.foto {
        top: 10px;
    }
</style>