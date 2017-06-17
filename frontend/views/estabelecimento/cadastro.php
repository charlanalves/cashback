<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Cadastro - CashBack';

$fieldOptions0 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}"
];

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];

$fieldOptions3 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions4 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

$fieldOptions5 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-tags form-control-feedback'></span>"
];

$urlResetPass = Url::to(['site/request']);

?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Cadastro </b>CashBack</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Cadastre-se e receba dinheiro de volta!</p>

        <?php $form = ActiveForm::begin(['id' => 'signup-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'cpf_or_cnpj', $fieldOptions0)
            ->label(false)
            ->radioList(['CPF','CNPJ']) ?>

        <?= $form
            ->field($model, 'cpf_cnpj', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => 'CPF', 'title' => 'CPF']) ?>

        <?= $form
            ->field($model, 'name', $fieldOptions2)
            ->label(false)
            ->textInput(['placeholder' => 'Nome', 'title' => 'Nome']) ?>

        <?= $form
            ->field($model, 'email', $fieldOptions3)
            ->label(false)
            ->textInput(['placeholder' => 'Email', 'title' => 'Email']) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions4)
            ->label(false)
            ->passwordInput(['placeholder' => 'Senha', 'title' => 'Senha']) ?>

        <?php 
            
            if ($convidado['codIndicacao'] && $convidado['idIndicacao']) { 
                
                echo $form
                     ->field($model, 'cod_indicacao')
                     ->label(false)
                     ->textInput(['value' => $convidado['codIndicacao'], 'readonly' => true, 'title' => 'Código da indicação']);
                
                echo $form
                     ->field($model, 'id_indicacao')
                     ->label(false)
                     ->hiddenInput(['value' => $convidado['idIndicacao']]);
                
            }
        ?>

        <div class="row">
            <div class="col-xs-8 text-align-left text-sm">
                já tem uma conta? <a href="index.php?r=site/login">Fazer login</a>
            </div>
            <!-- /.col -->
            <div class="col-xs-4 ">
                <?= Html::submitButton('Criar conta', ['class' => 'btn btn-success btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

<!--        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in
                using Facebook</a>
            <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign
                in using Google+</a>
        </div>-->
        <!-- /.social-auth-links -->

        <!--<a href="$urlResetPass">Esqueci minha senha</a><br>-->
        <!--<a href="register.html" class="text-center">Register a new membership</a>-->

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

<style>
    div#signupform-cpf_or_cnpj div.radio{
        
    }
    .login-box-msg{
        padding-bottom: 0px; 
    }
</style>

<script>

    document.addEventListener("DOMContentLoaded", function (event) {
        // CPF ou CNPJ
        function validaMask_CPF_CNPJ (){
            if($("input[name='SignupForm[cpf_or_cnpj]']:checked").val() == '0') {
                $("#signupform-cpf_cnpj").mask("999.999.999-99").attr('placeholder', 'CPF');
            } else {
                $("#signupform-cpf_cnpj").mask("99.999.999/9999-99").attr('placeholder', 'CNPJ');
            }
        }
        
        $("input[name='SignupForm[cpf_or_cnpj]']").click(function(){
            validaMask_CPF_CNPJ();
        });
        
        validaMask_CPF_CNPJ();
    });

</script>