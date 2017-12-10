<?php
if (!empty($_GET['auth_key'])){
$uid = $_GET['auth_key'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Estalecas - Seu dinheiro de volta!</title>
  <!-- CORE CSS-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css">
    <!-- jQuery Library -->
 <script src="js/jquery-3.2.1.min.js"></script>
  <!--materialize js-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/js/materialize.min.js"></script>
 <script src="js/jquery.mask.min.js"></script>
 <script src="js/sweetalert.min.js"></script>
 <script src="js/jquery.blockUI.js"></script>
 <link rel="stylesheet" href="css/sweetalert.css">

<!-- Include a polyfill for ES6 Promises (optional) for IE11 and Android browser -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
<style type="text/css">

html,
body {
    height: 100%;
}
html {
    display: table;
    margin: auto;
}
body {
    display: table-cell;
    vertical-align: middle;
}
.margin {
  margin: 0 !important;
}
.card-panel {
    transition: box-shadow .25s;
    padding: 20px;
    margin: 1px;
    border-radius: 2px;
    background-color: #fff;
}
img.responsive-img.valign.profile-image-login {
    padding: 26px;
}
.blue {
    background-color: #ffffff !important;
}
body {
    display: block;
    vertical-align: middle;
}

@media only screen and (min-width: 0){

html {
    font-size: 14px;
}
}
.row {
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 20px;
    height: 100%;
}
.row .col.s12 {
    width: 100%;
    margin-left: 0;
    height: 100%;
}

form .row.margin:first-child {
    padding-top: 21px;
}
.sweet-alert h2 {
    font-size: 21px !important;        
    margin: 2px !important;
}
.btn, .btn-large {
    background-color: #be0000 !important;

}
input[type=text].valid, input[type=text]:focus.valid, input[type=password].valid, input[type=password]:focus.valid, input[type=email].valid, input[type=email]:focus.valid, input[type=url].valid, input[type=url]:focus.valid, input[type=time].valid, input[type=time]:focus.valid, input[type=date].valid, input[type=date]:focus.valid, input[type=datetime-local].valid, input[type=datetime-local]:focus.valid, input[type=tel].valid, input[type=tel]:focus.valid, input[type=number].valid, input[type=number]:focus.valid, input[type=search].valid, input[type=search]:focus.valid, textarea.materialize-textarea.valid, textarea.materialize-textarea:focus.valid {
    border-bottom: 1px solid #03a8ec !important;
    box-shadow: 0 1px 0 0 #03a8ec !important;
}
.input-field .prefix.active {
    color: #039be5 !important;
}
input[type=text].valid+label:after, input[type=text]:focus.valid+label:after, input[type=password].valid+label:after, input[type=password]:focus.valid+label:after, input[type=email].valid+label:after, input[type=email]:focus.valid+label:after, input[type=url].valid+label:after, input[type=url]:focus.valid+label:after, input[type=time].valid+label:after, input[type=time]:focus.valid+label:after, input[type=date].valid+label:after, input[type=date]:focus.valid+label:after, input[type=datetime-local].valid+label:after, input[type=datetime-local]:focus.valid+label:after, input[type=tel].valid+label:after, input[type=tel]:focus.valid+label:after, input[type=number].valid+label:after, input[type=number]:focus.valid+label:after, input[type=search].valid+label:after, input[type=search]:focus.valid+label:after, textarea.materialize-textarea.valid+label:after, textarea.materialize-textarea:focus.valid+label:after {    
    color: #039be5 !important;
}
h3 {
    font-size: 15px;
    line-height: 110%;
    margin: 1.46rem 0 1.168rem 0;
    background: #fff9ef;
    padding: 21px;
    border: 1px solid;
    border-radius: 20px;
    margin: 18px;
    text-align: center;
}

</style>
  
</head>

<body class="blue">


  <div id="login-page" class="row">
  
   <div id="logo" style="width:100%;background: #be0000;text-align: center;">
            <img src="logo-estalecas.png" alt="" class="responsive-img valign profile-image-login">
           
			</div>
			<?php
if (empty($uid)){
	print_r("<h3>Um erro aconteceu. peça seu amigo que lhe envie o convite novamente.</h2>");
}else{
?>
    <div class="col s12 z-depth-6 card-panel">
      <form class="login-form">
     
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person-outline prefix"></i>
            <input id="fullname" type="text" class="validate">
            <label for="fullname" class="center-align">Nome Completo</label>
          </div>
        </div>
		 <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person-outline prefix"></i>
            <input required id="cpf" type="text" class="validate cpf">
            <label for="cpf" class="center-align">CPF</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-communication-email prefix"></i>
            <input required id="email" type="email" class="validate">
            <label for="email" class="center-align">E-mail</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input required id="password" type="password" class="validate">
			<input id="uid" value="<?=$uid?>" type="hidden">
            <label for="password">Senha</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <a href="register.html" id="signupForm" class="btn waves-effect waves-light col s12 ">CADASTRAR</a>
          </div>
          <div class="input-field col s12">
            <p class="margin center medium-small sign-up">Ao cadastrar cadastrar você estará aceitando os <a id="termosdeuso" href="#">termos de uso.</a></p>
          </div>
        </div>
      </form>
    </div>
<?php
}
?>
  </div>

  

  <!-- ================================================
    Scripts
    ================================================ -->


<script>
$(document).ready(function(){

$('.cpf').mask('000.000.000-00', {reverse: true});

$("#termosdeuso").click(function(){
	swal(
		  'Termos de uso.',
		  '...',
		  
		);
});

    $("#signupForm").click(function(event){
        event.preventDefault();
	var fullName = $('#fullname').val(),
	cpf = $('#cpf').val(),
	email = $('#email').val(),
	pass = $('#password').val();
		
	if (fullName == '' || cpf == '' || email == '' || pass == '')
	{			
		swal(
		  'Preencha todos os campos.',
		  'Todos os campos são obrigatorios.',
		  'error'
		);
	}else {
        
            var data = {
                  CB02_NOME: fullName,
                  CB02_CPF_CNPJ: cpf,
                  CB02_EMAIL: email,
                  password: pass
              };
                 var url = 'http://www.estalecas.com.br/api/frontend/web/index.php?r=api-empresa/login-create';
                 var url = 'http://localhost/apiestalecas/frontend/web/index.php?r=api-empresa/login-create';
                 $.blockUI({ message: '<img src="js/l.gif" />' });
                  $.ajax({
                     url:url,
                     type:"POST",
                     data: data,                     
                     dataType:"json",
                     success: function(data){ 
                        $.unblockUI();
                         if (!data.status) {
                            swal({
                              title: 'Erro',
                              type: 'error',
                              html: data.retorno,

                              showCloseButton: true,
                              showCancelButton: false,
                              focusConfirm: true

                            });
                            return;
                         }
                        swal({
                            title: 'Cadastro realizado com sucesso!',
                            text:
                                'Para continuar baixe o aplicativo e comece ' +
                                'a ganhar muito dinheiro de volta.',
                            type: 'info',
                            showCancelButton: false,                            
                            confirmButtonText: '<a href="my.special.scheme">Baixar O Aplicativo Agora.</a>'
                          }).then((result) => {
                            if (result.value) {
                               
                              swal(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                              )
                            }
                          })
                       
                    }
                });
          }
          
        return false;
    })

});

</script>
 
</body>

</html>
