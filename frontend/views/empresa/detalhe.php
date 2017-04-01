<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<style>
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
</style>

<div class="row">

    <div class="col-sm-12 col-md-12 col-lg-12 col-no-padding">

        <div class="well well-light well-sm no-margin no-padding">

            <div class="row">

                <div class="col-sm-12">
                    <div id="myCarousel" class="carousel slide profile-carousel">
                        <div class="air air-bottom-right padding-5">
                            <a href="whatsapp://send?text=<?= $empresa['categoria']['CB10_NOME'] ?>" class="btn txt-color-white bg-color-teal btn-circle">
                                <i class="fa fa-share-alt"></i>
                            </a>
                        </div>
                        <div class="air air-top-left padding-5">
                            <span class="fa fa-lg fa-mail-reply btn btn-default btn-circle" title="voltar" onclick="history.back()"></span>
                        </div>
                        <ol class="carousel-indicators">
                            <?php
                            $qtd = count($empresa['img_empresa']);
                            for ($i = 0; $i < $qtd; $i++) {
                                echo '<li data-target="#myCarousel" data-slide-to="' . $i . '" class="' . (($i == 0) ? 'active' : '') . '"></li>';
                            }
                            ?>
                        </ol>
                        <div class="carousel-inner">
                            <?php
                            foreach ($empresa['img_empresa'] as $img) {
                                echo '<div class="item ' . (($img['CB13_CAMPA']) ? 'active' : '') . '">';
                                echo '<img src="' . $img['CB13_URL'] . '" alt="">';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">

                    <div class="row">
                        <div class="col-sm-3 col-md-3 col-lg-3  profile-pic padding-10" style="padding-left: 0px!important;">
                            <img class="" src="<?= $empresa['empresa']['CB04_URL_LOGOMARCA'] ?>">
                        </div>

                        <div class="col-sm-9">
                            <div class="padding-5 air air-top-right">
                                <span class="label bg-color-red"><?= $empresa['categoria']['CB10_NOME'] ?></span>
                            </div>
                            <h1>
                                <span class="semi-bold"><?= $empresa['empresa']['CB04_NOME'] ?></span>
                            </h1>
                            <ul class="list-unstyled">
                                <li>
                                    <p class="text-muted font-sm">
                                        <i class="fa fa-map-marker"></i>&nbsp;&nbsp;<span class="txt-color-darken"><?= $empresa['empresa']['CB04_END_LOGRADOURO'] . ", " . $empresa['empresa']['CB04_END_NUMERO'] . " - " . $empresa['empresa']['CB04_END_BAIRRO'] . ", " . $empresa['empresa']['CB04_END_CIDADE'] . " - " . $empresa['empresa']['CB04_END_UF'] ?></span>
                                    </p>
                                </li>
                            </ul>

                        </div>
                    </div>

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
                                        $tabs_content .= '<p class="font-md">' . $produto['CB05_TITULO'] . '</p>' . "\n";


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
                                        $tabs_content .= (!$itens_produto) ? '' : '<p class="font-md no-margin border-top"><span class="fa fa-check-square-o"></span> &nbsp;Itens</p>' . $itens_produto;
                                        
                                        // cashback do produto ---------------------------------
                                        $cashback_produto = cashBackTable($produto['CASHBACK']);
                                        $tabs_content .= (!$cashback_produto) ? '' : '<p class="font-md no-margin border-top"><span class="fa fa-money"></span> &nbsp;Crédito</p>' . $cashback_produto;

                                        // variação do produto ---------------------------------
                                        if (!empty($produto['VARIACAO'])) {

                                            $tabela_variacao = '
                                                <p class="font-md no-margin border-top"><span class="fa fa-tags"></span> &nbsp;Promoções</p>
                                                <table class="table table-bordered table-striped table-hover">
                                                    <tbody>';

                                            foreach ($produto['VARIACAO'] as $variacao) {
                                                
                                                // linha do cashback se o mesmo existir
                                                $cashback_variacao = '';
                                                if (!empty($produto['CASHBACK_VARIACAO'][$variacao['CB06_ID']])) {
                                                    
                                                    $cashback_variacao = cashBackTable($produto['CASHBACK_VARIACAO'][$variacao['CB06_ID']]);
//                                                    if (($cashback_variacao = cashBackTable($produto['CASHBACK_VARIACAO'][$variacao['CB06_ID']]))) {
//                                                        $tabela_variacao .= '<tr><td colspan="2">' . $cashback_variacao . '</td></tr>' . "\n";
//                                                    }
                                                }
                                                
                                                // linha com descricao + valor ---------------------------
                                                $tabela_variacao .= '<tr><td>' . $cashback_variacao . '<span class="float-left">'.$variacao['CB06_DESCRICAO'].'</span><span class="float-right"><b>R$ ' . $variacao['CB06_PRECO'] . '</b></span></td></tr>' . "\n";
                                            }

                                            // Campo importante do produto -------------------------------
                                            $tabela_variacao .= (!$produto['CB05_IMPORTANTE']) ? '' : '
                                                        <tr>
                                                            <td colspan="2">	
                                                                <p class="alert alert-warning no-margin">
                                                                    <span class="glyphicon glyphicon-info-sign"></span>&nbsp; <span class="font-md">Informações Importantes:</span><br/>
                                                                    ' . nl2br($produto['CB05_IMPORTANTE']) . '
                                                                </p>
                                                            </td>
                                                        </tr>';
                                            $tabela_variacao .= '
                                                    </tbody>
                                                </table>';


                                            $tabs_content .= $tabela_variacao;
                                        }
                                        
                                        $tabs_content .= '</div>' . "\n";

                                        $i++;
                                    }
                                    ?>

                                    <div class="tabs-pull-right">
                                        <ul class="nav nav-tabs tabs-left text-align-right" id="demo-pill-nav">
                                            <?= $tabs ?>
                                        </ul>
                                        <div class="tab-content margin-top-10">
                                            <?= $tabs_content ?>
                                        </div>
                                    </div>

                                </div>
                                <!-- end widget content -->

                                <p class="font-md no-margin border-top margin-top-10">
                                    <span class="fa fa-clock-o"></span> &nbsp;Funcionamento
                                </p>
                                <p class="font-light text-justify">
                                    <?= nl2br($empresa['empresa']['CB04_FUNCIONAMENTO']) ?>
                                </p>
                                <p class="font-light text-justify">
                                    <?= nl2br($empresa['empresa']['CB04_OBSERVACAO']) ?>
                                </p>
                                <p class="font-md no-margin border-top">
                                    <span class="fa fa-credit-card"></span> &nbsp;Pagamentos
                                </p>
                                <p class="font-light text-justify">
                                    <?php
                                    $fp = [];
                                    foreach ($empresa['forma_pagamento'] as $value) {
                                        $fp[] = $value['CB08_URL_IMG'] . ' ' . $value['CB08_NOME'];
                                    }
                                    echo implode(' | ', $fp);
                                    ?>
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function (event) {
        $('.carousel.fade').carousel({
            interval: 3000,
            cycle: true
        });
    });
</script>
<?php 
//var_dump($empresa); 
?>