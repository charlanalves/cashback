<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<style>
    .height-30{
        height: 30px;
    }
    .width-100{
        width: 100%;
    }
    
    .float-left{
        float: left;
    }
    .width-42{
        width: 42%;
    }
     .width-33{
        width: 33%;
    }
    
    .width-15{
        width: 15%;
    }
    .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
        border: 0px solid #ddd;
    }
    .table-bordered, .table-bordered>tbody>tr{
        border: 1px solid #ddd;
    }
    
    /* tabela chashback do produto */
    .table-cashback-produto{
        margin-bottom: 5px;
    }
    .table-cashback-produto>thead>tr>th {
        text-align: center!important;
        padding-left: 1px;
        padding-right: 1px;
        padding-top: 1px;
        padding-bottom: 5px;
    }
    .div-cashback-produto span {
        padding: 1px 5px;
        border-radius: 5px;
    }
    .border-top{
        border-top: dashed 2px #EEE;
    }
    .btn-compartilhar {
        background-color: white;
        color: silver;
    }
    .btn-like {
        background-color: white;
        color: silver;
    }
    .btn-like-active {
        background-color: white;
        color: red;
        font-size: 15px;
    }
    .cb {
         width: 66%;
        float: left;
        font-weight: normal;
        font-size: 13px;
    }
    .cb-valores{
        width: 34%;
         float: left;
         font-weight: normal;
         font-size: 13px;
         margin-top: 8px;
         font-weight: bold;
    }
    .desc-promo {
    margin-top: 3px;
}
    .titulo-promo{
      margin: 0px;
      font-weight: bold;
      margin-top: 7px;
      font-size: 16px;
      color: #636363;
    }
    .cb-valores .preco {
        color: #bf1347;
    }
    .cb-valores .cashbackm {
        color: #079100;
    }
    .cashbackp {
        color: #7d7d7d;
    }
    .margin-top3{
        margin-top: 3px;
    }
    label.ui-btn.ui-corner-all.ui-btn-inherit.ui-btn-icon-left.ui-radio-off {
        background-color: white;
    }
    label.ui-btn.ui-corner-all.ui-btn-inherit.ui-btn-icon-left.ui-radio-on {
        background: #fdfdfd;
    }
    #footer-comprar {
    position: fixed;
    bottom: 0;
    width: 100%;
    background: #a90329;
    line-height: 2;
    text-align: center;
    box-shadow: 0 0 15px #00214B;
    z-index: 9999;
      
}
.height-30.width-100.pague-text {
         text-shadow: none;
    color: white;
    margin: 0;
    padding-left: 12px;
    text-align: left;
    padding-bottom: 20px;
    font-size: 12px;
    position: relative;
    top: 11px;
}
.width-15.currency {
    text-shadow: none;
    font-size: 33px;
    color: white;
    font-weight: bold;
    padding-left: 14px;
}
.float-left.width-100.preco-bar {
    text-shadow: none;
    color: white;
    font-weight: bold;
    line-height: 39px;
    position: relative;
    top: -9px;
    padding-left: 4px;
}
span.currency{
        font-size: 22px;
}
span.preco-bar-currency{
    font-size: 37px;
    padding-left: 7px;
    position: relative;
    top: 6px;
}
.height-30.width-100.receba-text {
    text-shadow: none;
    color: white;
    font-size: 11px;
    position: relative;
    top: 18px;
    padding-left: 3px;
    
}
.float-left.receba {   
    width: 29%;
    color: white;
    text-shadow: none;
    text-align: left;
    position: relative;
    top: 7px;
}
.btn-comprar{
    width: 29%;
}
.padding-left-33{
    padding-left: 33px;
}
button.btn-comprar.btn.btn-primary.ui-btn.ui-shadow.ui-corner-all{
    width: 88%;
    color: white;
    text-shadow: none;
    text-transform: uppercase;
    background-color: #09285f;
    -webkit-box-shadow: 10px 10px 5px 0px rgba(255, 249, 249, 0.75);
    -moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
    box-shadow: 1px -3px 5px 0px rgba(173, 173, 173, 0.75);
}
.float-left.width-42.pague {
    margin-left: 14%;
}

@media screen and (max-width: 400px) {
.cb-valores {
    width: 34%;
    float: left;
    font-weight: normal;
    font-size: 11px;
    margin-top: 8px;
    font-weight: bold;
}
.titulo-promo {
    margin: 0px;
    font-weight: bold;
    margin-top: 7px;
    font-size: 13px;
    color: #636363;
}
span.preco-bar-currency {
    font-size: 30px;
}
}

.col-sm-12.padding-10 {
    padding: 0px !important;
}
.ui-btn, label.ui-btn {
    font-weight: 700;
    border-width: 1px;
    border-style: solid;
    border-top: none;
    border-left: none;
    border-right: none;
    border-radius: 0px !important;
}
p.font-sm.text-justify {
    padding: 10px;
    width: 80%;
    text-shadow: none;
}

.page-footer {
    background: #ffffff;
}
.col-sm-9.tab-endereco-empresa {
    padding-left: 12px;
}

.text-body-info {
    color: #6b6b6b;
    padding-left: 9px;
    font-size: 12px;
    text-shadow: none;
    line-height: 18px;
    margin-top: 5px;
}
p.tab-info-importante.no-margin {
    padding-left: 8px;
}
span.font-md.text-info-importante {
    /* padding-left: 15px; */
    text-align: justify;
    padding-right: 16px;
    font-size: 13px;
    color: #252525;
    font-size: 16px !important;
}
.tab-content.margin-top-10 {
    margin-bottom: 76px;
    min-height: 340px;
}
ul#demo-pill-nav {
    background-color: #fbfbfb;
}
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {    
    background-color: #fbfbfb;
}

.nav-tabs>li>a {
    color: #c3c3c3;
}
.tab-content.margin-top-10 {
        margin-top: 20px!important;
}
div#tab-promocoes {
    position: relative;
    top: -19px;
}
.ui-checkbox, .ui-radio {
    margin: 0;
    position: relative;
}
</style>

<div class="row">

    <div class="col-sm-12 col-md-12 col-lg-12 col-no-padding">

        <div class="well well-light well-sm no-margin no-padding">

            <div class="row">

               

                <div class="col-sm-12">


                    <div class="row">
                        <div class="col-sm-12 padding-10">

                            <div role="content">

                                <!-- widget content -->
                                <div class="widget-body">
                                    
                                    <?php
                                    
                                    function cashBackTable($cashBack) {
                                    
                                        $cashback_table = $cashback_unico = '';
                                        $cashback_dia = [];
                                        
                                        if (!empty($cashBack)) {
                                            foreach ($cashBack as $cashback) {
                                                if (!is_numeric($cashback['CB07_DIA_SEMANA'])) {
                                                    $cashback_dia = [];
                                                    $cashback_unico = (int) $cashback['CB07_PERCENTUAL'];
                                                    break;
                                                } else {
                                                    $cashback_dia[$cashback['CB07_DIA_SEMANA']] = (int) $cashback['CB07_PERCENTUAL'];
                                                }
                                            }

                                            $diaSemana = [
                                                1 => 'SEG',
                                                2 => 'TER',
                                                3 => 'QUA',
                                                4 => 'QUI',
                                                5 => 'SEXT',
                                                6 => 'SÁB',
                                                0 => 'DOM'
                                            ];
                                            
                                            // se o cashback for por dia
//                                            if ($cashback_dia) {
                                                $cashback_dia_tr = '';
                                                foreach ($diaSemana as $d => $cbDia) {
                                                    $vlDia = ($cashback_unico)? : (array_key_exists($d, $cashback_dia) ? $cashback_dia[$d] : 0);
                                                    $cashback_dia_tr .= '<th><div class="div-cashback-produto">' . $cbDia . '<br><span class="btn-' . (($vlDia ? 'success">' . $vlDia : 'danger">0')) . '%</span></div></th>';
                                                }
                                                $cashback_table = '
                                                    <table class="table table-bordered table-striped table-cashback-produto">
                                                        <thead>
                                                            <tr>' . $cashback_dia_tr . '</tr>
                                                        </thead>
                                                    </table>';

                                            // se cashback por produto
//                                            } else if ($cashback_unico) {
//                                                $cashback_table = '<div class="div-cashback-produto margin-bottom-10"><span class="btn-success">' . $cashback_unico . '%</span></div>';
//                                            }
                                        }
                                        return $cashback_table;
                                    }
                                    
                                    function itensTable($item) {
                                        $html = '';
                                        if (!empty($item)) {
                                            $html .= '<p class="margin-bottom-10 font-sm text-justify">';
                                            foreach ($item as $i) {
                                                $html .= ' &nbsp; <span class="fa fa-check"></span> ' . $i['CB11_DESCRICAO'] . " &nbsp; ";
                                            }
                                            $html .= '</p>';
                                        }
                                        return $html;
                                    }
                                    
                                    $i = 1;
                                    $tabs = $tabs_content = '';
                                    foreach ($empresa['produto'] as $produto) {

                                        // create tabs --------------------------------------
                                        $tabs .= '<li class="' . (($i == 1) ? 'active' : '') . '">' . "\n";
                                        $tabs .= '<a href="#tab-produto' . $i . '" data-toggle="tab">';
                                        $tabs .= (!empty($produto['MAX_CASHBACK'])) ? '<span class="badge bg-color-red txt-color-white">' . $produto['MAX_CASHBACK'] . '%</span>' : '';
                                        $tabs .= $produto['CB05_NOME_CURTO'];
                                        $tabs .= '</a>';
                                        $tabs .= '</li>' . "\n";


                                        // create contents ------------------------------------
                                        $tabs_content .= '<div class="tab-pane ' . (($i == 1) ? 'active' : '') . '" id="tab-produto' . $i . '">' . "\n";
                                      //  $tabs_content .= '<p class="font-md">' . $produto['CB05_TITULO'] . '</p>' . "\n";


                                        // imagens do produto ---------------------------------
                                        if (!empty($produto['IMG'])) {
                                            $ii = 1;
                                            $carousel_atalho = $carousel_img = '';

                                            foreach ($produto['IMG'] as $img) {
                                                //$carousel_atalho .= '<li data-target="#carouselProduto' . $i . '" data-slide-to="' . $ii . '" class="' . (($ii == 1) ? 'active' : '') . '"></li>' . "\n";
                                                $carousel_img .= '<div class="item ' . (($ii == 1) ? 'active' : '') . '"><img src="' . $img['CB14_URL'] . '" alt=""></div>' . "\n";
                                                $ii++;
                                            }

                                            $tabs_content .= '
                                                <div id="carouselProduto' . $i . '" class="carousel fade">
                                                    <ol class="carousel-indicators">
                                                        ' . $carousel_atalho . '
                                                    </ol>
                                                    <div class="carousel-inner">
                                                        ' . $carousel_img . '
                                                    </div>
                                                    <a class="left carousel-control" href="#carouselProduto' . $i . '" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a>
                                                    <a class="right carousel-control" href="#carouselProduto' . $i . '" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a>
                                                </div>' . "\n";
                                        }

                                        // descrição do produto --------------------------------
                                        $tabs_content .= '<p class="font-sm text-justify">' . $produto['CB05_DESCRICAO'] . '</p>' . "\n";
                                        
                                        // itens do produto -------------------------------
                                        $itens_produto = itensTable($produto['ITEM']);
                                      
                                        
                                        // cashback do produto ---------------------------------
                                        $cashback_produto = cashBackTable($produto['CASHBACK']);
                                        $tabs_content .= (!$cashback_produto) ? '' : '<p class="credito font-md no-margin border-top"><span class="fa fa-money"></span> &nbsp;Crédito</p>' . $cashback_produto;

                                        // variação do produto ---------------------------------
                                        if (!empty($produto['VARIACAO'])) {

                                            $tabela_variacao = '';
                                            $promocoes = '';
                                                
                                            $cont = 0;
                                            foreach ($produto['CASHBACK_VARIACAO'] as $key => $variacao) {
                                                
                                                // linha do cashback se o mesmo existir
//                                                $cashback_variacao = '';
//                                                if (!empty($produto['CASHBACK_VARIACAO'][$variacao['CB06_ID']])) {
//                                                    
//                                                    $cashback_variacao = cashBackTable($produto['CASHBACK_VARIACAO'][$variacao['CB06_ID']]);
////                                                    if (($cashback_variacao = cashBackTable($produto['CASHBACK_VARIACAO'][$variacao['CB06_ID']]))) {
////                                                        $tabela_variacao .= '<tr><td colspan="2">' . $cashback_variacao . '</td></tr>' . "\n";
////                                                    }
//                                                }
//                                                
//                                                // linha com descricao + valor ---------------------------
//                                                $tabela_variacao .= '<tr><td>' . $cashback_variacao . '<span class="float-left">'.$variacao['CB06_DESCRICAO'].'</span><span class="float-right"><b>R$ ' . $variacao['CB06_PRECO'] . '</b></span></td></tr>' . "\n";
          
                                                if (!empty($variacao)) {
                                                    
                                                    $percentualCB = $variacao['CB07_PERCENTUAL'];
                                                    $valorCB = $variacao['VALOR_CB'];

                                                    if (!$cont) {
                                                        $checked = 'checked';
                                                        $barraComprarPreco = number_format($variacao['CB06_PRECO'], 2, ',', '.');
                                                        $barraComprarValorCB = number_format($valorCB, 2, ',', '.');
                                                        $barraComprarPercentCB = round ($percentualCB);
                                                    }
                                                    
                                                    $promocoes .=  '<input type="radio" name="radio-promo" id="variacao-'. $produto["CB05_ID"] .'-'. $variacao['CB06_ID']  . '" value="choice-1" '. $checked .'>
                                                        <label for="variacao-'. $produto["CB05_ID"] .'-'. $variacao['CB06_ID']  . '">   
                                                            <div class="cb">
                                                                 <div class="titulo-promo">
                                                                    '. $variacao['CB06_TITULO'] .'
                                                                 </div>
                                                                 <div class="desc-promo">
                                                                       '. $variacao['CB06_DESCRICAO'] .'
                                                                  </div>                                            
                                                            </div>
                                                            <div class="cb-valores text-align-right">
                                                                <div class="margin-top3 preco">Pague R$ <span class="radio-text-pague">'. number_format($variacao['CB06_PRECO'], 2, ',', '.').'</span></div>
                                                                 <div class="margin-top3 cashbackm">Receba R$ <span class="radio-text-valorcb">'. number_format($valorCB, 2, ',', '.') .'</span></div>
                                                                 <div class="margin-top3 cashbackp"><span class="radio-text-percentcb">'.round ($percentualCB).'</span>% DE VOLTA</div>
                                                            </div>
                                                        </label>';
                                                }
                                               $cont++; 
                                               $checked = '';  
                                            }
                              // Campo importante do produto -------------------------------
                                            $informacoes = (!$produto['CB05_IMPORTANTE']) ? '' : '
                                                        <tr>
                                                            <td colspan="2">
                                                                <p class="tab-info-importante no-margin">
                                                                    <span class="glyphicon glyphicon-info-sign"></span>&nbsp; <span class="font-md text-info-importante">Informações Importantes:</span>
                                                                    <div class="text-body-info">
                                                                    ' . nl2br($produto['CB05_IMPORTANTE']) . '
                                                                    </div>    
                                                                </p>
                                                            </td>
                                                        </tr>';
                                            $informacoes .= '
                                                    </tbody>
                                                </table>';


                                            $tabs_content .= $tabela_variacao;
                                        }
                                        
                                        $tabs_content .= '</div>' . "\n";

                                        $i++;
                                    }
                                    ?>

                                 
                                        
                              
                                        
                                     <?= $tabs_content ?>
                                    <div class="tabs-pull-right">
                                        <ul class="nav nav-tabs tabs-left text-align-right" id="demo-pill-nav">
                                            <li class="active">
                                                <a href="#tab-promocoes" data-toggle="tab">
                                                    PROMOÇÕES
                                                <span class="badge bg-color-red txt-color-white maxcb"></span>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#tab-info" data-toggle="tab">
                                                    INFORMAÇÕES
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#tab-regras" data-toggle="tab">
                                                    REGRAS
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#tab-local" data-toggle="tab">
                                                    LOCAL
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content margin-top-10">
                                            
                                            <div class="tab-pane active" id="tab-promocoes">
                                                
                                                      <?= $promocoes ?>
                                                
                                            </div>
                                              <div class="tab-pane" id="tab-info">
                                                <p>
                                                      <?= $informacoes ?>
                                                </p>
                                            </div>
                                              <div class="tab-pane" id="tab-regras">
                                                <p>
                                                      regras
                                                </p>
                                            </div>
                                              <div class="tab-pane" id="tab-local">
                                                <p>
                                                      <div class="row">
                                                        

                                                        <div class="col-sm-9 tab-endereco-empresa">
                                                         
                                                            <h1>
                                                                <span class="semi-bold"><?= $empresa['empresa']['CB04_END_LOGRADOURO'] ?></span>
                                                            </h1>
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <p class="text-muted font-sm">
                                                                        <i class="fa fa-map-marker"></i>&nbsp;&nbsp;<span class="txt-color-darken"><?= $empresa['empresa']['CB04_END_NUMERO'] . " - " . $empresa['empresa']['CB04_END_BAIRRO'] . ", " . $empresa['empresa']['CB04_END_CIDADE'] . " - " . $empresa['empresa']['CB04_END_UF'] ?></span>
                                                                    </p>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                </p>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                                <!-- end widget content -->
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
<div id="footer-comprar">
    <div class="float-left width-42 pague">
        <div class="height-30 width-100 pague-text">Pague</div>
        <div class="float-left width-100 preco-bar">
            <span class="currency">R$</span> 
            <span class="preco-bar-currency"></span>
        </div>
    </div>
    <div class="float-left width-33 receba">
        <div class="height-30 width-100 receba-text">Receba</div>
         <div class="float-left width-100 cashback-bar">
            <span class="receba-percentcb"></span>% de volta
        </div>
    </div>
    <p class="padding-left-33"><button type="button" class="btn-comprar btn btn-primary" id="btnComprar">Comprar</button></p>
</div>


<script>
    
    var like = <?= (int)$empresa['like'] ?>,
        estabelecimento = <?= (int)$empresa['empresa']['CB04_ID'] ?>,
        eventLike = {};
    
    document.addEventListener("DOMContentLoaded", function (event) {
        
        $(document).ready(function(){
                var preco = $('.tab-pane.active input[type=radio]:checked').prev('label').find('.radio-text-pague').text(),
                    percentcb = $('.tab-pane.active input[type=radio]:checked').prev('label').find('.radio-text-percentcb').text();

                $('span.preco-bar-currency').text(preco);
                $('span.receba-percentcb').text(percentcb);
                $('span.maxcb').text(percentcb + '%');
                



        });
            
        $('input[name="radio-promo"]').on('change',function () {

            var preco = $(this).prev('label').find('.radio-text-pague').text(),
                valorcb = $(this).prev('label').find('.radio-text-valorcb').text(),
                percentcb = $(this).prev('label').find('.radio-text-percentcb').text();

            $('span.preco-bar-currency').text(preco);
            $('span.receba-percentcb').text(percentcb);

        });

//   $(document).scroll(function(){
//        var threshold = 300; // number of pixels before bottom of page that you want to start fading
//        var op = (($(document).height() - $(window).height()) - $(window).scrollTop()) / threshold;
//              if( op <= 0 ){
//                      $("#tab-produto1").hide();
//              } else {
//                      $("#tab-produto1").show();
//              }
//              $("div#tab-produto1").css("opacity", op ); 
//      });
      
      
      
        $('.carousel.fade').carousel({
            interval: 3000,
            cycle: true
        });
        
        eventLike = function (obj, flg) {
            if (flg) {
                $(obj).removeClass("btn-like").addClass("btn-like-active");
            } else {
                $(obj).removeClass("btn-like-active").addClass("btn-like");
            }
            $(obj).blur();
        }
        
        $('a#btn-like').on('click', function(){
            var r = $.ajax({
                url: 'index.php?r=empresa/like',
                type: 'GET',
                data: {'estabelecimento': estabelecimento},
                dataType: "jsonp"
            });
            r.always(function (data) {
                if (data.responseText) {
                    like = like ? 0 : 1;
                    if (like) {
                        message = 'Estabelecimento adicionado aos Favoritos';
                        ico = 'fa-smile-o';
                        efeito = 'bounce';
                        cor = '#3276B1';
                    } else {
                        message = 'Estabelecimento removido dos Favoritos';
                        ico = 'fa-meh-o';
                        efeito = '';
                        cor = '#C46A69';
                    }
                    
                    eventLike('a#btn-like', like);

                    $.smallBox({
                        title : message,
                        content : "",
                        color : cor,
                        iconSmall : "fa " + ico + " " + efeito + " animated",
                        timeout : 3000
                    });
                }
            });
            
            return false;
        });
        
        eventLike('a#btn-like', like);
        
    });
    
</script>

