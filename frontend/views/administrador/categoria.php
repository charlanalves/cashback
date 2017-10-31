<style>
    .not_m_line{
        white-space:nowrap; overflow:hidden;
    }
</style>
<script type="text/javascript">

    var currentCategoria = false;

    document.addEventListener("DOMContentLoaded", function (event) {

        function fix_height() {
            var h = $("#tray").height();
            $("#preview").attr("height", (($(window).height()) - h) + "px");
        }
        $(window).resize(function () {
            fix_height();
        }).resize();

        C7.init();
        
        C7.callbackLoadGridDeliveryMain = function () {};

        C7.load('Grid', 'CategoriaMain', 'gridCategoria');

        //C7.exportGridToCSV('CategoriaMain');
        
        C7.grid.CategoriaMain.enableCopyMMS();


    });

    // alterar status - Modal
    var alterarStatusDelivery = function (pedido) {
        $('#remoteModalSetStatus')
            .modal('show')
            .find('.modal-body button#btn-salvar')
            .attr('onclick', 'setStatusDelivery(' + pedido + ')');
    
    }

    // alterar status - Action
    setStatusDelivery = function (pedido) {
        var new_status = $('select#statusDelivery').val();
        if(!new_status){
            Util.smallBox('Selecione um status para a entrega.', '', 'danger');
        } else {
            $('#remoteModalSetStatus').modal('hide');
            $.blockUI();
            Util.ajaxPost('index.php?r=administrador/global-crud&action=setStatusDelivery', {pedido: pedido, new_status: new_status}, callbackSetStatusDelivery);
        }
    }

    // alterar status - Callback
    callbackSetStatusDelivery = function (data) {            
        $.unblockUI();
        if (data.responseText) {
            Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
            $('#remoteModalSetStatus').modal('show');
        } else {
            C7.reloadGrid();
        }
    }

    // modal Categoria
    var modalCategoria = function () {
        // limpar campo
        $('#remoteModalCategoria input[name=CB10_NOME]').val('');
        $('#remoteModalCategoria')
            .modal('show')
            .find('#btn-salvar')
            .on('click', function () {
                var new_name = $('#remoteModalCategoria input[name=CB10_NOME]').val();
                if(!new_name){
                    Util.smallBox('Preencha o nome da categoria.', '', 'danger');
                } else {
                    $('#remoteModalCategoria').modal('hide');
                    $.blockUI();
                    Util.ajaxPost('index.php?r=administrador/global-crud&action=createCategoria', {nome: new_name}, function (data) {
                        $.unblockUI();
                        if (data.responseText) {
                            Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                            $('#remoteModalCategoria').modal('show');
                        } else {
                            C7.reloadGrid();
                        }
                    });
                }
            });
    };

    // Excluir Categoria
    var excluirCategoria = function (id) {
        $.SmartMessageBox({
            title: "Deseja excluir?",
            //content : "",
            buttons: '[Não][Sim]'
        }, function (ButtonPressed) {
            if (ButtonPressed === "Sim") {
                Util.ajaxPost('index.php?r=administrador/global-crud&action=categoriaDisable', {id}, function (data) {
                    $.unblockUI();
                    if (data.responseText) {
                        Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                    } else {
                        C7.reloadGrid();
                    }
                });
            }
        });
    };

    // modal Itens da Categoria
    var modalItensCategoria = function (cat) {
        currentCategoria = cat;
        var objItem = $('#remoteModalItensCategoria input[name=CB11_DESCRICAO]');
        objItem.val('');
        $('#gridItensCategoria').html('');
        $('#remoteModalItensCategoria')
            .modal('show')
            .find('#btn-salvar')
            .on('click', function () {
                var new_item = objItem.val();
                if(!new_item){
                    Util.smallBox('Preencha o nome do item.', '', 'danger');
                } else {
                    $.blockUI();
                    Util.ajaxPost('index.php?r=administrador/global-crud&action=createItemCategoria', {cat: currentCategoria, item: new_item}, function (data) {
                        $.unblockUI();
                        if (data.responseText) {
                            Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                        } else {
                            C7.actionReloadGridItensCategoriaMain({'cat': currentCategoria});
                        }
                        objItem.val('').focus();
                    });
                }
            });

        setTimeout(function(){ 
            C7.load('Grid', 'ItensCategoriaMain', 'gridItensCategoria', {'cat': currentCategoria});    
        }, 1000);

    };


    // Excluir Item da Categoria
    var excluirItemCategoria = function (id) {
        $.SmartMessageBox({
            title: "Deseja excluir?",
            //content : "",
            buttons: '[Não][Sim]'
        }, function (ButtonPressed) {
            if (ButtonPressed === "Sim") {
                Util.ajaxPost('index.php?r=administrador/global-crud&action=itemCategoriaDisable', {id}, function (data) {
                    $.unblockUI();
                    if (data.responseText) {
                        Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                    } else {
                        C7.actionReloadGridItensCategoriaMain({'cat': currentCategoria});
                    }
                });
            }
        });
    };


    // modal Itens da Avaliação
    var modalItensAvaliacao = function (cat) {
        currentCategoria = cat;
        var objItem = $('#remoteModalItensAvaliacao input[name=CB23_DESCRICAO]');
        objItem.val('');
        var objIcone = $('#remoteModalItensAvaliacao input[name=CB23_ICONE]');
        objIcone.keyup(function () {
            $('i#icone-avaliacao').attr('class', 'fa fa-lg fa-' + $(this).val());
        });
        $('#gridItensAvaliacao').html('');
        $('#remoteModalItensAvaliacao')
            .modal('show')
            .find('#btn-salvar')
            .on('click', function () {
                var new_item = objItem.val();
                var icone = (objIcone.val() || 'start');
                if(!new_item){
                    Util.smallBox('Preencha o nome do item.', '', 'danger');
                } else {
                    $.blockUI();
                    Util.ajaxPost('index.php?r=administrador/global-crud&action=createItemAvaliacao', {id: currentCategoria, item: new_item, ico: icone}, function (data) {
                        $.unblockUI();
                        if (data.responseText) {
                            Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                        } else {
                            C7.actionReloadGridItensAvaliacaoMain({'cat': currentCategoria});
                        }
                        objItem.val('').focus();
                        objIcone.val('star');
                        $('i#icone-avaliacao').attr('class', 'fa fa-lg fa-star');
                    });
                }
            });

        setTimeout(function(){ 
            C7.load('Grid', 'ItensAvaliacaoMain', 'gridItensAvaliacao', {'cat': currentCategoria});    
        }, 1000);

    };

    // Excluir Item da Avaliação
    var excluirItemAvaliacao = function (id) {
        $.SmartMessageBox({
            title: "Deseja excluir?",
            //content : "",
            buttons: '[Não][Sim]'
        }, function (ButtonPressed) {
            if (ButtonPressed === "Sim") {
                Util.ajaxPost('index.php?r=administrador/global-crud&action=itemAvaliacaoDisable', {id}, function (data) {
                    $.unblockUI();
                    if (data.responseText) {
                        Util.smallBox('Opss, tente novamente...', '', 'danger', 'close');
                    } else {
                        C7.actionReloadGridItensAvaliacaoMain({'cat': currentCategoria});
                    }
                });
            }
        });
    };


</script>


<!-- MODAL CATEGORIA -->
<div class="modal fade" id="remoteModalCategoria" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 350px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Cadastrar categoria</h4>
            </div>
            <div class="modal-body no-padding">
                <form action="" class="smart-form" novalidate="novalidate">
                    <fieldset>
                        <section class="">
                            <label class="">Nome da categoria</label>
                            <label class="input">
                                <input type="text" name="CB10_NOME" />
                            </label>
                        </section>
                    </fieldset>
                    <footer style="padding: 10px;">
                        <button id="btn-salvar" type="button" class="btn btn-success" style="margin:0px 4px" onclick="">
                            Salvar
                        </button>
                        <button id="btn-cancelar" type="button" class="btn btn-danger" data-dismiss="modal" style="margin:0px 4px">
                            Cancelar
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->


<!-- MODAL ITENS CATEGORIA -->
<div class="modal fade" id="remoteModalItensCategoria" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 450px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Itens da categoria</h4>
            </div>
            <div class="modal-body no-padding">
                <form action="" class="smart-form" novalidate="novalidate">
                    <fieldset>
                        <section class="">
                            <label class="">Novo item</label>
                            <label class="input">
                                <input type="text" name="CB11_DESCRICAO" />
                            </label>
                        </section>
                    </fieldset>

                    <footer style="padding: 10px;">
                        <button id="btn-salvar" type="button" class="btn btn-success" style="margin:0px 4px" onclick="">
                            Salvar
                        </button>
                        <button id="btn-cancelar" type="button" class="btn btn-danger" data-dismiss="modal" style="margin:0px 4px">
                            Cancelar
                        </button>
                    </footer>
                </form>
                <div class="widget-body dx-grid" id="gridItensCategoria" style="height: 400px"></div>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->


<!-- MODAL ITENS AVALIAÇÃO -->
<div class="modal fade" id="remoteModalItensAvaliacao" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 450px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Itens da avaliação</h4>
            </div>
            <div class="modal-body no-padding">
                <form action="" class="smart-form" novalidate="novalidate">
                    <fieldset>
                        <div class="row">
                            <section class="col col-6">
                                <label class="">Novo item</label>
                                <label class="input">
                                    <input type="text" name="CB23_DESCRICAO" />
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="">Icone: &nbsp;<i id="icone-avaliacao" class="fa fa-lg fa-star"></i></label>
                                <label class="input">
                                    <input type="text" name="CB23_ICONE" value="star" />
                                </label>
                                <div class="note"><a href="http://fontawesome.io/icons/#new" target="_blank">fontawesome</a></div>
                            </section>
                        </div>
                    </fieldset>

                    <footer style="padding: 10px;">
                        <button id="btn-salvar" type="button" class="btn btn-success" style="margin:0px 4px" onclick="">
                            Salvar
                        </button>
                        <button id="btn-cancelar" type="button" class="btn btn-danger" data-dismiss="modal" style="margin:0px 4px">
                            Cancelar
                        </button>
                    </footer>
                </form>
                <div class="widget-body dx-grid" id="gridItensAvaliacao" style="height: 400px"></div>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Button trigger modal categoria -->
        <a href="javascript:void(0)" onclick="modalCategoria()" class="btn btn-success pull-right">
            <i class="fa fa-circle-arrow-up fa-lg"></i> 
            Cadastrar categoria &nbsp;<i class="fa fa-plus-circle"></i>
        </a>
        <h1 class="page-title txt-color-blueDark">
            <i class="fa-fw fa fa-flag"></i> 
            Categorias <span></span>
        </h1>
    </div>
</div>

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 no-padding">
        <div role="content">
            <div class="widget-body dx-grid" id="gridCategoria"></div>
        </div>
    </article>
</div>