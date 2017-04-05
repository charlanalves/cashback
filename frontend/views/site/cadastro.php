<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Cadastro - CashBack';

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

$urlResetPass = Url::to(['site/request']);

        // cpf_cnpj, name, email, password
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Cadastro </b>CashBack</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Cadastre-se e receba dinheiro de volta!</p>

        <?php $form = ActiveForm::begin(['id' => 'signup-form', 'enableClientValidation' => false]); ?>
        
        <div class="radio">
            <label>
                <input type="radio" class="radiobox style-0" checked="checked" name="cpf_or_cnpj" value="CPF">
                <span>CPF</span> 
            </label>
            &nbsp; &nbsp; &nbsp;
            <label>
                <input type="radio" class="radiobox style-0" name="cpf_or_cnpj" value="CNPJ">
                <span>CNPJ</span> 
            </label>
        </div>
        
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

        <div class="row">
            <div class="col-xs-8">
                
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

<script>

    document.addEventListener("DOMContentLoaded", function (event) {
        // CPF ou CNPJ
        $("input[name=cpf_or_cnpj]").click(function(){
            if($(this).val() == 'CPF'){
                
            } else {
                
            }
        }); 
    });

</script>