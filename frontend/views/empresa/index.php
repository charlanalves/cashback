<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


<div class="row">

    <div class="col-sm-12 col-md-12 col-lg-12 col-no-padding">

        <div class="well" style="padding: 0px; margin-bottom: 10px;">

            <table class="table table-striped table-forum lista-estabelecimentos">
<!--                <thead>
                    <tr>
                        <th colspan="2">Introduction</th>
                        <th class="text-center hidden-xs hidden-sm" style="width: 100px;">Topics</th>
                        <th class="text-center hidden-xs hidden-sm" style="width: 100px;">Posts</th>
                        <th class="hidden-xs hidden-sm" style="width: 200px;">Last Post</th>
                    </tr>
                </thead>-->
                <tbody>

                    <?php foreach ($empresas as $v) { ?>
                    
                    <tr>
                        <td class="text-center" style="width: 30px;"><i class="fa fa-globe fa-2x text-muted"></i></td>
                        <td>
                            <h4>
                                <a href="#"><?= $v['CB04_NOME']?></a>
                                <small class="endereco-empresa ">
                                    <span class="hidden-mobile">
                                        <?= $v['CB04_END_LOGRADOURO'] . ", " . $v['CB04_END_NUMERO'] . " - " ?>    
                                    </span>
                                    <span class=" hidden-lg">                                    
                                        <?= $v['CB04_END_BAIRRO'] . ", " . $v['CB04_END_CIDADE'] . " - " . $v['CB04_END_UF']?>
                                    </span>
                                </small>
                            </h4>
                        </td>
                        <td class="text-center">
                            <span class="vlr-cashback-2">
                                <span><?= $v['CASHBACK']?>%</span>
                            </span>
                        </td>
                    </tr>
                    
                    <?php } ?>
                    
                </tbody>
            </table>


        </div>



    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function (event) {

    });
</script>