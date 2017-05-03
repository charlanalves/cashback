<?php 
    $dadosJson = (!empty($dados)) ? json_encode($dados, true) : '{}';
    $this->title = '';
    $token = '';
    //$token = \Yii::$app->pagamentoComponent->pagseguroCreateSession();
?>

<!-- #GOOGLE FONT -->
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

<!-- #APP SCREEN / ICONS -->
<!-- Specifying a Webpage Icon for Web Clip 
         Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
<link rel="apple-touch-icon" href="img/splash/sptouch-icon-iphone.png">
<link rel="apple-touch-icon" sizes="76x76" href="img/splash/touch-icon-ipad.png">
<link rel="apple-touch-icon" sizes="120x120" href="img/splash/touch-icon-iphone-retina.png">
<link rel="apple-touch-icon" sizes="152x152" href="img/splash/touch-icon-ipad-retina.png">

<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<!-- Startup image for web apps -->
<link rel="apple-touch-startup-image" href="img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
<link rel="apple-touch-startup-image" href="img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
<link rel="apple-touch-startup-image" href="img/splash/iphone.png" media="screen and (max-device-width: 320px)">

<!--<form action="" id="checkout-form" name="checkout-form" onsubmit="return false" class="smart-form" novalidate="novalidate" style="background-color: #FFF;padding: 10px">-->
<form action="" id="checkout-form" name="checkout-form" onsubmit="return false" class="smart-form" novalidate="novalidate" style="background-color: #FFF;padding: 10px">
    <div id="content" style="padding: 10px;">
        
        <h3 id="nome_loja" style="display: none"></h3>
        <div class="product-content product-wrap clearfix" style="margin:0;border:0;">
            <div class="row" style="margin: 0px;">
                <div class="col-md-5 col-sm-12 col-xs-12 col-xs-custom-50 col-no-padding" style="padding:0">

                    <div class="product-image" style="min-height: auto; border:0; padding: 0px 10px; margin: 0px">
                        <img src="img/demo/e-comm/3.png" alt="194x228" class="img-responsive" id="item_img" /> 
                    </div>
<!--                    
                    <div id="myCarousel" class="carousel slide">
                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" data-slide-to="0" class=""></li>
                            <li data-target="#myCarousel" data-slide-to="1" class="active"></li>
                            <li data-target="#myCarousel" data-slide-to="2" class=""></li>
                        </ol>
                        <div class="carousel-inner">
                             Slide 1 
                            <div class="item active">
                                <img src="img/demo/e-comm/detail-1.png" alt="">
                            </div>
                             Slide 2 
                            <div class="item">
                                <img src="img/demo/e-comm/detail-2.png" alt="">
                            </div>
                             Slide 3 
                            <div class="item">
                                <img src="img/demo/e-comm/detail-3.png" alt="">
                            </div>
                        </div>
                        <a class="left carousel-control" href="#myCarousel" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a>
                        <a class="right carousel-control" href="#myCarousel" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a>
                    </div>  -->


                </div>
                <div class="col-md-7 col-sm-12 col-xs-12 col-xs-custom-50 col-no-padding" style="padding:0">
                    <div class="product-deatil" style="border:0; padding: 5px; margin: 0px">
                        <h2 id="item_desc" class="name"style="margin:0;margin-bottom: 5px;border:0;width: 100%;"></h2>
                        <p class="price-container">
                            <span id="item_vlr"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <fieldset>
            <legend>Meus dados</legend>

            <div class="row">
                <section class="col col-6 form-padding-left">
                    <label class="input"> <i class="icon-prepend fa fa-info-circle"></i>
                        <input type="text" name="comprador_cpf" placeholder="CPF" data-mask="999.999.999-99">
                    </label>
                </section>
                <section class="col col-6 form-padding-right">
                    <label class="input"> <i class="icon-prepend fa fa-user"></i>
                        <input type="text" name="comprador_nome" placeholder="Nome completo" required="">
                    </label>
                </section>
            </div>

            <div class="row">
                <section class="col col-6 form-padding-right">
                    <label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
                        <input type="email" name="comprador_email" placeholder="E-mail">
                    </label>
                </section>
                <section class="col col-6 form-padding-left">
                    <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                        <input type="tel" name="comprador_tel" placeholder="Telefone" data-mask="(99) 999999999">
                    </label>
                </section>
            </div>
        </fieldset>

        <fieldset>
            <legend>Forma de pagamento</legend>
            
            <div class="row">

                <section class="col col-4 form-padding-right">
                    <label class="radio radio-inline">
                        <input type="radio" name="forma_pag" class="checkout-forma-pag" value="CreditCard" />
                        <i></i>Cartão de Crédito
                    </label>
                </section>
                
            </div>

            <div id="complemento-pagamento"></div>

        </fieldset>

    </div>
    
    <div class="modal-footer padding-top-15">
        <button type="submit" class="btn btn-primary btn-sm" id="btnComprar">
            <i class="fa fa-shopping-cart"></i>
            Comprar
        </button>
    </div>

</form>

<div id="complemento-pag-cartao-credito" style="display: none">
    <div>
        <div class="row">

            <section class="col col-4">
                <label class="input"><i class="icon-prepend fa fa-credit-card"></i>
                    <input type="text" name="cartao_numero" placeholder="Número do cartão" onchange="ps.getConfigCartao()" maxlength="16" value="">
                </label>
            </section>

            <section class="col col-2 form-padding-left">
                <label class="input"> <i class="icon-append fa fa-question-circle"></i>
                    <input type="text" name="cartao_cvv" placeholder="CVV" value="">
                    <b class="tooltip tooltip-top-right">
                        <i class="fa fa-warning txt-color-teal"></i> 
                        Digite o código de segurança
                    </b>
                </label>
            </section>

            <section class="col col-6">
                <label class="input">
                    <input type="text" name="cartao_nome" placeholder="Nome impresso no cartão" value="">
                </label>
            </section>
            
        </div>

        <div class="row">

            <section class="col col-4 form-padding-right form-padding-left">
                <label class="select">
                    <select name="cartao_mes">
                        <option value="0" selected="" disabled="">Mês</option>
                        <option value="01">Janeiro</option>
                        <option value="01">Fevereiro</option>
                        <option value="03">Março</option>
                        <option value="04">Abril</option>
                        <option value="05">Maio</option>
                        <option value="06">Junho</option>
                        <option value="07">Julho</option>
                        <option value="08">Agosto</option>
                        <option value="09">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                    </select> <i></i> 
                </label>
            </section>

            <section class="col col-2 form-padding-right form-padding-left">
                <label class="input">
                    <input type="text" name="cartao_ano" placeholder="Ano" value="">
                </label>
            </section>

            <section class="col col-6 form-padding-left">
                <label class="select">
                    <select name="cartao_parcela">
                        <option value="">selecione o numero de parcelas...</option>
                    </select>
                </label>
            </section>

        </div>
    </div>
</div>


<div id="complemento-pag-boleto" style="display: none">
    <div></div>
</div>
    

<!-- Your GOOGLE ANALYTICS CODE Below -->
<script type="text/javascript">

//            var _gaq = _gaq || [];
//            _gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
//            _gaq.push(['_trackPageview']);
//
//            (function () {
//                var ga = document.createElement('script');
//                ga.type = 'text/javascript';
//                ga.async = true;
//                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
//                var s = document.getElementsByTagName('script')[0];
//                s.parentNode.insertBefore(ga, s);
//            })();

</script>

<style>
    section {
        padding: 0px 15px!important;
        margin: 5px 0px!important;
    }
    fieldset{
        margin: 0;
        padding: 15px 0px!important;
    }
    fieldset legend{
        padding-top: 0px!important;
        margin: 0;
    }
    .form-padding-left{
        /*padding-left: 5px!important
        padding-left: 2px!important*/
    }
    .form-padding-right{
        /*padding-right: 5px!important
        padding-right: 2px!important*/
    }
    .page-footer{display: none}
</style>


<script type="text/javascript">
    
    var dados = JSON.parse('<?= $dadosJson?>');
    
    
    checkoutForm = new Form('checkout-form');
    checkoutForm.setFormData(dados);
    
    $('input[name=forma_pag]')[0].checked = true
    complementoCartao = $('div#complemento-pag-cartao-credito div').prop('outerHTML');
    $('div#complemento-pagamento').html(complementoCartao);

    
    
    
    
    var gateway = dados['gateway'];
    var hashSecundario = dados['hash_recebedor_secundario'];
    var item = dados['item'];
    var endereco_pais = dados['endereco_pais'];
    var nome_loja = dados['nome_loja'];
    var logo_loja = dados['logo_loja'];
    var telefone_loja = dados['telefone_loja'];
    var email_loja = dados['email_loja'];
    var variacao_grupo = dados['variacao_grupo'];
    var variacao_descricao = dados['variacao_descricao'];
    var forma_pag = dados['forma_pag'];
    var valor_frete = parseFloat(dados['valor_frete']);
    var valor_produto = parseFloat(dados['valor_total']);
    var valor_total = parseFloat(valor_produto + valor_frete);
    
    var ps = {};
    var sendFormData = {};
    var configCartao = {};
    var idSession = '<?= $token?>';
    var cartaoBandeira = '';
    var parcelaFixa = false;
    var hash_recebedor_primario;
    
    var $checkoutForm = {};
    
    // set dados do checkout
    $('h3#nome_loja').text(nome_loja);
    $('h2#item_desc').text(item[0].item_desc);
    $('label#variacao_grupo').text(variacao_grupo + ": ");
    $('label#variacao_descricao').text(variacao_descricao);
    $('label#item_qtd').text("Qtd.: " + item[0].item_qtd);
    $('p span#item_vlr').text("R$ " + Util.formatNumber(valor_produto, 2));
    $('p span#frete_vlr span').text("Frete: R$ " + valor_frete);
    $('img#item_img').attr('src',(item[0].item_img || ''));
    
//    var ultimoCEP = '';
//    function buscaCEP(v){
//	v = v.replace('X','');
//        if(v.length === 9 && ultimoCEP != v){
//            ultimoCEP = v;
//            globalGetEnderecoByCEP(v, preencheEndereco);
//        }
//    }
//    
//    function preencheEndereco(data){
//        if(data.erro){
//            $.smallBox({
//                title: "Busca de CEP",
//                content: "<i class='fa fa-clock-o'></i> <i>O CEP digitado não foi encontrado, verifique o CEP ou preencha os dados do endereço...</i>",
//                color: "#C46A69",
//                iconSmall: "fa fa-times fa-2x fadeInRight animated",
//                timeout: 8000
//            });
//            $('form#checkout-form input[name=endereco_logradouro]').val('');
//            $('form#checkout-form input[name=endereco_bairro]').val('');
//            $('form#checkout-form input[name=endereco_cidade]').val('');
//            $('form#checkout-form input[name=endereco_uf]').val('');
//            $('form#checkout-form input[name=endereco_complemento]').val('');
//            
//        }else{
//            console.log(data);
//            $('form#checkout-form input[name=endereco_logradouro]').val(data.logradouro);
//            $('form#checkout-form input[name=endereco_bairro]').val(data.bairro);
//            $('form#checkout-form input[name=endereco_cidade]').val(data.localidade);
//            $('form#checkout-form input[name=endereco_uf]').val(data.uf);
//            $('form#checkout-form input[name=endereco_complemento]').val(data.complemento);
//        }
//    }
//    
    pageSetUp();

//    document.addEventListener("DOMContentLoaded", function(event) {
        
        var pagefunction = function () {

            var errorClass = 'invalid';
            var errorElement = 'em';

            $checkoutForm = $('#checkout-form').validate({
                errorClass: errorClass,
                errorElement: errorElement,
                highlight: function (element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                // Rules for form validation
                rules: {
                    comprador_nome: {
                        required: true
                    },
                    comprador_cpf: {
                        required: true
                    },
                    comprador_email: {
                        required: true,
                        email: true
                    },
                    comprador_tel: {
                        required: true
                    },
                    endereco_cep: {
                        required: true
                    },
                    endereco_logradouro: {
                        required: true
                    },
                    endereco_bairro: {
                        required: true
                    },
                    endereco_cidade: {
                        required: true
                    },
                    endereco_uf: {
                        required: true
                    },
                    forma_pag: {
                        required: true
                    },
                    cartao_numero: {
                        required: true,
                        creditcard: true
                    },
                    cartao_cvv: {
                        required: true,
                        digits: true
                    },
                    cartao_nome: {
                        required: true
                    },
                    cartao_mes: {
                        required: true
                    },
                    cartao_ano: {
                        required: true,
                        digits: true
                    },
                    cartao_parcela: {
                        required: true
                    }
                },
                // Messages for form validation
                messages: {
                    comprador_nome: {
                        required: 'Informe seu nome completo'
                    },
                    comprador_cpf: {
                        required: 'Informe seu CPF',
                        digits: 'Digite um CPF valido'
                    },
                    comprador_email: {
                        required: 'Informe seu email',
                        email: 'Informe um email VALIDO'
                    },
                    comprador_tel: {
                        required: 'Informe seu telefone'
                    },
                    endereco_cep: {
                        required: 'Informe seu CEP',
                        digits: 'Apenas números'
                    },
                    endereco_logradouro: {
                        required: 'Informe o logradouro (Rua, Avenida, Rodovia, Sítio, Chácara...)',
                    },
                    endereco_bairro: {
                        required: 'Informe seu bairro'
                    },
                    endereco_cidade: {
                        required: 'Informe sua cidade'
                    },
                    endereco_uf: {
                        required: 'Informe o UF'
                    },
                    forma_pag: {
                        required: 'Selecione uma forma de pagamento'
                    },
                    cartao_numero: {
                        required: 'Informe o número do cartão',
                        creditcard: 'Informe um número de cartão valido'
                    },
                    cartao_cvv: {
                        required: 'Informe o código de segurança do cartão',
                        digits: 'Apenas números'
                    },
                    cartao_nome: {
                        required: 'Informe o nome impresso no cartão',
                    },
                    cartao_mes: {
                        required: 'Selecione o mês de vencimento do cartão'
                    },
                    cartao_ano: {
                        required: 'Informe o ano de vencimento do cartão',
                        digits: 'Apenas números'
                    },
                    cartao_parcela: {
                        required: 'Selecione uma opção de pagamento'
                    }
                },
                // Do not change code below
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });

        };

        // Load form valisation dependency 
        loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);

//    });
//
//    PagSeguroDirectPayment.setSessionId(idSession);
//
//    // hash do cliente
//    ps.getHash = function() {
//        hash_recebedor_primario = PagSeguroDirectPayment.getSenderHash();
//    }
//
//    // get formas de pagamento
//    ps.getFormaPagamento = function(){
//        PagSeguroDirectPayment.getPaymentMethods({
//            success: function(a){},
//            error: function(a){
//                console.log('erro: ');
//                console.log(a);
//            },
//            complete: function(a){}
//        });
//    }
//
//    // get sobre o cartao
//    ps.getConfigCartao = function(){
//        cartao = $('form#checkout-form input[name=cartao_numero]').val();
//        
//        if(cartao.length >= 6){
//            var bin = parseInt(cartao.substring(0,6));
//            PagSeguroDirectPayment.getBrand({
//                cardBin: bin,
//                success: function(a){},
//                error: function(a){
//                    console.log('erro: ');
//                    console.log(a);
//                },
//                complete: function(a){
//                    if(a.error === true){
//                        cartaoBandeira = null;
//                        $('form#checkout-form select[name=cartao_parcela]').empty();
//
//                    } else {
//                        configCartao = a.brand;
//                        cartaoBandeira = configCartao.name;
//                        ps.getParcelamentoCartao();
//                        
//                    }
//                }
//            });
//        }
//        
//    }
//    if($('form#checkout-form input[name=cartao_numero]').val()){
//        ps.getConfigCartao();
//    }
//
//    // get token do cartao
//    ps.getTokenCartao = function(callback){
//        param = {
//            cardNumber: $("form#checkout-form input[name=cartao_numero]").val(),
//            brand: cartaoBandeira,
//            cvv: $("form#checkout-form input[name=cartao_cvv]").val(),
//            expirationMonth: $("form#checkout-form select[name=cartao_mes]").val(),
//            expirationYear: $("form#checkout-form input[name=cartao_ano]").val(),
//            success: function(a){},
//            error: function(a){
//                console.log('erro: ');
//                console.log(a);
//            },
//            complete: function(a){
//                configCartao.tk = a.card.token;
//                callback();
//            }
//        };
//
//        PagSeguroDirectPayment.createCardToken(param);
//
//    }
//
//    // get parcelamentos
//    ps.getParcelamentoCartao = function(){
//        if(!parcelaFixa && cartaoBandeira){
//            PagSeguroDirectPayment.getInstallments({
//                amount: valor_total,
//                brand: cartaoBandeira,
//                maxInstallmentNoInterest: 4,
//                success: function(a){},
//                error: function(a){
//                    console.log('erro: ');
//                    console.log(a);
//                },
//                complete: function(a){
//                    $('form#checkout-form select[name=cartao_parcela]').empty();
//                    for(var i in a.installments[cartaoBandeira]){
//                        $('form#checkout-form select[name=cartao_parcela]').append($('<option>', {
//                            value: a.installments[cartaoBandeira][i].quantity + '-' + a.installments[cartaoBandeira][i].installmentAmount,
//                            text: a.installments[cartaoBandeira][i].quantity + 'x de R$' + a.installments[cartaoBandeira][i].installmentAmount
//                        }));
//                    }
//                }
//            });
//        }
//    }
//
//    // preenche form com os parametros recebidos
//    for(var i in dados){
//        // retira telefone e parcelas
//        if(i != 'comprador_tel_ddd' && i != 'comprador_tel_numero' && i != 'cartao_num_parcela' && i != 'cartao_vlr_parcela' && i!= 'forma_pag') {
//            $('form#checkout-form input[name=\'' + i + '\']').val(dados[i]);            
//            $('form#checkout-form input[name=\'' + i + '\']').prop("readonly", true);
//        }
//    };
//
//    // telefone
//    if(dados['comprador_tel_ddd'] && dados['comprador_tel_numero']){
//        $('form#checkout-form input[name=comprador_tel]').val('(' + dados['comprador_tel_ddd'] + ') ' + dados['comprador_tel_numero']);
//        $('form#checkout-form input[name=comprador_tel]').prop("readonly", true);
//    }
//
//    // Formas de pagamento
//    //CreditCard
//    if(dados['cartao_num_parcela'] && dados['cartao_vlr_parcela'] && forma_pag == 'CreditCard'){
//        $('form#checkout-form select[name=cartao_parcela]').append($('<option>', {
//            value: dados['cartao_num_parcela'] + '-' + dados['cartao_vlr_parcela'],
//            text:  dados['cartao_num_parcela'] + 'x de R$' + dados['cartao_vlr_parcela']
//        }));
//        $('form#checkout-form select[name=cartao_parcela]').prop("readonly", true);
//        $('form#checkout-form input[name=forma_pag][value=CreditCard]').prop("checked", true);
//        $('form#checkout-form input[name=forma_pag]').prop("disabled", true);
//        parcelaFixa = true;
//        
//    // Boleto
//    } else if(forma_pag == 'Boleto'){
//        $('form#checkout-form input[name=forma_pag][value=Boleto]').prop("checked", true);
//        $('form#checkout-form input[name=forma_pag]').prop("disabled", true);
//    }
//    
//    // compra
//    $('form#checkout-form button#btnComprar').on('click', function(){
////        console.log($checkoutForm.form());
//        
//        if($checkoutForm.form() === true){
//            if(forma_pag === 'CreditCard'){
//                if(!cartaoBandeira)
//                    ps.getConfigCartao();
//                ps.getTokenCartao(sendFormData);
//
//            } else if(forma_pag === 'Boleto'){
//                ps.getHash();
//                sendFormData();
//
//            }
//        }
//    });
//    
    // seleciona forma de pagamento
    $("form#checkout-form input.checkout-forma-pag").on("click", function() {
        forma_pag = $("form#checkout-form input.checkout-forma-pag:checked").val();
        
        if(forma_pag === 'CreditCard'){
            complementoCartao = $('div#complemento-pag-cartao-credito div').prop('outerHTML');
            $('div#complemento-pagamento').html(complementoCartao);
            
        } else if(forma_pag === 'Boleto'){
            complementoBoleto = $('div#complemento-pag-boleto div').prop('outerHTML');
            $('div#complemento-pagamento').html(complementoBoleto);
            
        }
            
    });
//    
//    sendFormData = function(){
//        
//        data = $('form#checkout-form').serializeArray();
//        data.push({name:'valor_total', value:valor_total});
//        data.push({name:'gateway', value:gateway});
//        data.push({name:'hash_recebedor_primario', value:hash_recebedor_primario});
//        data.push({name:'hash_recebedor_secundario', value:hashSecundario});
//        data.push({name:'cartao_token', value:configCartao.tk});
//        data.push({name:'item', value:item});
//        data.push({name:'endereco_pais', value:endereco_pais});
//        data.push({name:'forma_pag', value:forma_pag});
//
//        var r = $.ajax({
//            url: 'index.php?r=pagamento/pagamento/gateway',
//            type: 'POST',
//            data: {'dados': data},
//            dataType: "jsonp"
//        });
//        
//        r.always(function(data) {
//            $('#retornoo').html(data.responseText);
//        });
//        
//    }
    
    
    
</script>
<div id="retornoo"></div>