var SystemOut;

(function(){

	var System = {
		Main: {
			Layout : {},
			Tabbar : {}
		}
	}

	// Configs de Skin e path
	dhtmlx.image_path = "../assets/dhtmlx/terrace/imgs/";
	dhtmlx.skin = "dhx_terrace";


	//loadLayout(System);
	loadTabbar(System);
	openTabbar(System);

	// retirado conforme solicitação do Nilberto em 11/10/2016
	// retorno para controle de sessao em 07/03/2017
    alertsRefresh(System);


	$(".sidebar-toggle").on("click",function(){
		setTimeout(function(){
			System.Main.Tabbar._setSizes();
		}, 500);

	});

	$(window).on("resize",function(){
		setTimeout(function(){
			System.Main.Tabbar._setSizes();
		}, 500);
	});

	$("#pesquisar").on("keyup",function(e){

		search($("#query").val());

	});

	$("#pesquisar").on("submit",function(e){
		e.preventDefault();
	});

	SystemOut = System;

}());


/*
Pesquisa menu lateral MMS
*/

//1.0 Alteração - 16/12/2016 - Tiago Francisco
//1.1 Alteração - 15/02/2017 - Carlos Couto
//1.2 Alteração - 28/03/2017 - Vitor

function search(filter) {

	if (filter == 'bing') { // procura no bing (microsoft)
		openNav('http://www.bing.com.br');
	} else if (filter == 'kamehameha') { // easter egg
		var local = window.location.pathname;
		local = local+'/../../images/egg.gif';
		openNotificacao('Easter Egg',"<img src='"+local+"' border=0>");
	} else if (filter.indexOf("google") > 0) { // procura no google --> necessita digitar google no final da expressao que deseja procurar
		var procura = filter.substring(0,filter.indexOf("google"));
		var url = "http://www.google.com/#q="+procura;
		window.open(url,'_blank');
	}

	if (filter == ""){
		resetMenu();
	} else{
	    $(".sidebar-menu > li").each(function(){
	        if ($(this).text().search(new RegExp(filter, "i")) < 0) {
	            $(this).hide();
				li_hideTreeview();
				remove_padding();
	        } else {
	            $(this).show();
	            $(this).children('ul').addClass("active");
	            $(this).children('ul').addClass("menu-open");
	            $(this).children('ul').css('display','block');
				a_hide();
	        }
	    });

		function li_hideTreeview(){
			$(".sidebar-menu .treeview-menu > li").each(function(){
				if ($(this).text().search(new RegExp(filter, "i")) < 0) {
					$(this).hide();
				} else {
					$(this).show();
					$(this).children('ul').css('display','block');
				}
			});
		}

		function a_hide(){
			$(".skin-red .sidebar-menu > li > a, .skin-red .treeview-menu > li > a").each(function(){
				$(this).hide();
			});
		}

		function remove_padding(){
			$(".sidebar-menu .treeview-menu .treeview-menu").each(function(){
				$(this).css('padding-left','0');
				$(this).css('display','block');
			});
		}
	}
}

//Restaura estado do menu
function resetMenu(){
	$(".sidebar-menu > li > ul").each(function(){
		$(this).removeClass("active");
		$(this).removeClass("menu-open");
		$(this).css('display','none');
		$(this).parent('li').show();
	});

	$(".sidebar-menu .treeview-menu > li").each(function(){
		$(this).removeClass("active");
		$(this).removeClass("menu-open");
		$(this).css('display','block');
		$(this).parent('li').hide();
    });

	$(".skin-red .sidebar-menu > li > a, .skin-red .treeview-menu > li > a").each(function(){
		$(this).show();
	});

	$(".sidebar-menu .treeview-menu .treeview-menu").each(function(){
		$(this).css('padding-left','20');
		$(this).css('display','none');
	});
}


function loadLayout(System){

	System.Main.Layout = new dhtmlXLayoutObject("layoutArea", "1C");

}

function loadTabbar(System){

	System.Main.Tabbar = new dhtmlXTabBar("layoutArea");
	System.Main.Tabbar.setHrefMode("iframes");
	System.Main.Tabbar.enableAutoReSize(true);
	System.Main.Tabbar.enableTabCloseButton(true);

}


// retirado conforme solicitação do Nilberto em 11/10/2016
// retorno para controle de sessao em 07/03/2017
function alertsRefresh(System){

    setInterval(function(){
         $.ajax({
            url:'index.php?r=default/jsonalertas',
            success: function(resposta){
                if(resposta.logout === true){
                    window.location.href = "index.php?r=Seguranca/login/logout";
                } else {
					if(resposta.refreshMenu === true) {
                   		menuRefresh(System);
					}
					if (resposta.msgAlerta != '' && resposta.msgAlerta != undefined) {
						mostrarAlerta('info', resposta.msgAlerta);
					}
					if (resposta.qtdeMensagens >= 0) {
						$("#qtdeMensagensNovas").html(resposta.qtdeMensagens);
					}
					// referente às tarefas do sistema
					if (resposta.qtdeTarefas >= 0) {
						$("#qtdeTarefasPendentes").html(resposta.qtdeTarefas);
					}
                }
            }
        });
	//}, 600000); // 10 minutos = 600.000 segundos // tempo de verificação para refresh de menu e logout -> 1000 = 1 segundo
	}, 20000); // 20 segundos // tempo de verificação para refresh de menu e logout -> 1000 = 1 segundo

}


function menuRefresh(System){

    $.ajax({
        url:'index.php?r=default/refreshmenu',
        success: function(resposta){
            $("#menu").html('<li class="header"> </li>' + resposta);
            openTabbar(System);
        }
    });

}

function openTabbar(System){
	$(".sidebar-menu div").on("click",function(e){
		e.preventDefault();
		var url = $(this).attr("data-url");
		var name = $(this).text();
		var allTabs = System.Main.Tabbar.getAllTabs();
		if( url !== "#" ){
			for(var i = 0; allTabs.length >= i; i++){
				if(url == allTabs[i]){
					System.Main.Tabbar.setTabActive(url);
					return;
				}
			}
            var tamanho_tab = name.length * 8 + 30;
            tamanho_tab = tamanho_tab+'px';
			System.Main.Tabbar.addTab(url,name,tamanho_tab);
			System.Main.Tabbar.setTabActive(url);

			System.Main.Tabbar.setContentHref(url,url);
		}
	});

	$(".enviaTab div, .enviaTab a").on("click",function(e){
		e.preventDefault();
		var url = $(this).attr("data-url");
		var name = '';

		if (typeof $(this).attr("data-name") != 'undefined') {
			name = $(this).attr("data-name");
		} else {
			name = $(this).text();
		}

		var allTabs = System.Main.Tabbar.getAllTabs();
		if( url !== "#" ){
			for(var i = 0; allTabs.length >= i; i++){
				if(url == allTabs[i]){
					System.Main.Tabbar.setTabActive(url);
					return;
				}
			}
            var tamanho_tab = name.length * 8 + 30;
            tamanho_tab = tamanho_tab+'px';
			System.Main.Tabbar.addTab(url,name,tamanho_tab);
			System.Main.Tabbar.setTabActive(url);

			System.Main.Tabbar.setContentHref(url,url);
		}
	});
}

function abrirTela(url,name){
	url =  "../../cebh/"+url;

	var allTabs = SystemOut.Main.Tabbar.getAllTabs();
	if( url !== "#" ){
		for(var i = 0; allTabs.length >= i; i++){
			if(url == allTabs[i]){
				SystemOut.Main.Tabbar.setTabActive(url);
				return;

			}
		}
        var tamanho_tab = name.length * 8 + 30;
        tamanho_tab = tamanho_tab+'px';
		SystemOut.Main.Tabbar.addTab(url,name,tamanho_tab);
		SystemOut.Main.Tabbar.setTabActive(url);

		SystemOut.Main.Tabbar.setContentHref(url,url);
	}
}

function abrirTelaWave2(url,name)
{
    var allTabs = SystemOut.Main.Tabbar.getAllTabs();
    if( url !== "#" ){
        for(var i = 0; allTabs.length >= i; i++){
            if(url == allTabs[i]){
                SystemOut.Main.Tabbar.setTabActive(url);
                return;
            }
        }
        var tamanho_tab = name.length * 8 + 30;
        tamanho_tab = tamanho_tab+'px';
        SystemOut.Main.Tabbar.addTab(url,name,tamanho_tab);
        SystemOut.Main.Tabbar.setTabActive(url);

        SystemOut.Main.Tabbar.setContentHref(url,url);
	}
}

function fecharTabAtual()
{
    var tabAtual = SystemOut.Main.Tabbar.getActiveTab();
	SystemOut.Main.Tabbar.removeTab(tabAtual,true);
}
