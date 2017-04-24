//v.3.0 build 110713

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/
dhtmlXGridObject.prototype.filterBy=function(a,b,c){if(this.isTreeGrid())return this.filterTreeBy(a,b,c);if(this._f_rowsBuffer){if(!c&&(this.rowsBuffer=dhtmlxArray([].concat(this._f_rowsBuffer)),this._fake))this._fake.rowsBuffer=this.rowsBuffer}else this._f_rowsBuffer=[].concat(this.rowsBuffer);if(this.rowsBuffer.length){var d=!0;this.dma(!0);if(typeof a=="object")for(var e=0;e<b.length;e++)this._filterA(a[e],b[e]);else this._filterA(a,b);this.dma(!1);this.pagingOn&&this.rowsBuffer.length/this.rowsBufferOutSize<
this.currentPage-1&&this.changePage(0);this._reset_view();this.callEvent("onGridReconstructed",[])}};dhtmlXGridObject.prototype._filterA=function(a,b){if(b!=""){var c=!0;typeof b=="function"?c=!1:b=(b||"").toString().toLowerCase();if(this.rowsBuffer.length)for(var d=this.rowsBuffer.length-1;d>=0;d--)(c?this._get_cell_value(this.rowsBuffer[d],a).toString().toLowerCase().indexOf(b)==-1:!b.call(this,this._get_cell_value(this.rowsBuffer[d],a),this.rowsBuffer[d].idd))&&this.rowsBuffer.splice(d,1)}};
dhtmlXGridObject.prototype.getFilterElement=function(a){if(this.filters){for(var b=0;b<this.filters.length;b++)if(this.filters[b][1]==a)return this.filters[b][0].combo||this.filters[b][0];return null}};
dhtmlXGridObject.prototype.collectValues=function(a){var b=this.callEvent("onCollectValues",[a]);if(b!==!0)return b;if(this.isTreeGrid())return this.collectTreeValues(a);this.dma(!0);this._build_m_order();for(var a=this._m_order?this._m_order[a]:a,c={},d=[],e=this._f_rowsBuffer||this.rowsBuffer,f=0;f<e.length;f++){var g=this._get_cell_value(e[f],a);if(g&&(!e[f]._childIndexes||e[f]._childIndexes[a]!=e[f]._childIndexes[a-1]))c[g]=!0}this.dma(!1);var h=this.combos[a],i;for(i in c)c[i]===!0&&d.push(h?
h.get(i)||i:i);return d.sort()};dhtmlXGridObject.prototype._build_m_order=function(){if(this._c_order){this._m_order=[];for(var a=0;a<this._c_order.length;a++)this._m_order[this._c_order[a]]=a}};
dhtmlXGridObject.prototype.filterByAll=function(){var a=[],b=[];this._build_m_order();for(var c=0;c<this.filters.length;c++)if(!(d>=this._cCount)){var d=this._m_order?this._m_order[this.filters[c][1]]:this.filters[c][1];b.push(d);var e=this.filters[c][0]._filter?this.filters[c][0]._filter():this.filters[c][0].value,f;if(typeof e!="function"&&(f=this.combos[d]))d=f.values._dhx_find(e),e=d==-1?e:f.keys[d];a.push(e)}if(this.callEvent("onFilterStart",[b,a])&&(this.filterBy(b,a),this._cssEven&&this._fixAlterCss(),
this.callEvent("onFilterEnd",[this.filters]),this._f_rowsBuffer&&this.rowsBuffer.length==this._f_rowsBuffer.length))this._f_rowsBuffer=null};
dhtmlXGridObject.prototype.makeFilter=function(a,b){if(!this.filters)this.filters=[];typeof a!="object"&&(a=document.getElementById(a));if(a){var c=this;if(!a.style.width)a.style.width="90%";if(a.tagName=="SELECT"){this.filters.push([a,b]);this._loadSelectOptins(a,b);a.onchange=function(){c.filterByAll()};if(_isIE)a.style.marginTop="1px";this.attachEvent("onEditCell",function(c,d,g){this._build_m_order();c==2&&this.filters&&(this._m_order?g==this._m_order[b]:g==b)&&this._loadSelectOptins(a,b);return!0})}else if(a.tagName==
"INPUT")this.filters.push([a,b]),a.value="",a.onkeydown=function(){this._timer&&window.clearTimeout(this._timer);this._timer=window.setTimeout(function(){if(a.value!=a.old_value)c.filterByAll(),a.old_value=a.value},500)};else if(a.tagName=="DIV"&&a.className=="combo"){this.filters.push([a,b]);a.style.padding="0px";a.style.margin="0px";if(!window.dhx_globalImgPath)window.dhx_globalImgPath=this.imgURL;var d=new dhtmlXCombo(a,"_filter","90%");d.filterSelfA=d.filterSelf;d.filterSelf=function(){this.getSelectedIndex()==
0&&this.setComboText("");this.filterSelfA.apply(this,arguments);this.optionsArr[0].hide(!1)};d.enableFilteringMode(!0);a.combo=d;a.value="";this._loadComboOptins(a,b);d.attachEvent("onChange",function(){a.value=d.getSelectedValue();if(a.value===null)a.value="";c.filterByAll()})}a.parentNode&&(a.parentNode.className+=" filter");this._filters_ready()}};
dhtmlXGridObject.prototype.findCell=function(a,b,c){var d=[],a=a.toString().toLowerCase();typeof c!="number"&&(c=c?1:0);if(!this.rowsBuffer.length)return d;for(var e=b||0;e<this._cCount;e++){if(this._h2)this._h2.forEachChild(0,function(b){if(c&&d.length==c)return d;this._get_cell_value(b.buff,e).toString().toLowerCase().indexOf(a)!=-1&&d.push([b.id,e])},this);else for(var f=0;f<this.rowsBuffer.length;f++)if(this._get_cell_value(this.rowsBuffer[f],e).toString().toLowerCase().indexOf(a)!=-1&&(d.push([this.rowsBuffer[f].idd,
e]),c&&d.length==c))return d;if(typeof b!="undefined")break}return d};dhtmlXGridObject.prototype.makeSearch=function(a,b){typeof a!="object"&&(a=document.getElementById(a));if(a){var c=this;if(a.tagName=="INPUT")a.onkeypress=function(){this._timer&&window.clearTimeout(this._timer);this._timer=window.setTimeout(function(){if(a.value!=""){var d=c.findCell(a.value,b,!0);d.length&&(c._h2&&c.openItem(d[0][0]),c.selectCell(c.getRowIndex(d[0][0]),b||0))}},500)};a.parentNode&&(a.parentNode.className+=" filter")}};
dhtmlXGridObject.prototype._loadSelectOptins=function(a,b){var c=this.collectValues(b),d=a.value;a.innerHTML="";a.options[0]=new Option("","");for(var e=this._filter_tr?this._filter_tr[b]:null,f=0;f<c.length;f++)a.options[a.options.length]=new Option(e?e(c[f]):c[f],c[f]);a.value=d};dhtmlXGridObject.prototype.setSelectFilterLabel=function(a,b){if(!this._filter_tr)this._filter_tr=[];this._filter_tr[a]=b};
dhtmlXGridObject.prototype._loadComboOptins=function(a,b){var c=this.collectValues(b);a.combo.clearAll();a.combo.render(!1);for(var d=[["","&nbsp;"]],e=0;e<c.length;e++)d.push([c[e],c[e]]);a.combo.addOption(d);a.combo.render(!0)};
dhtmlXGridObject.prototype.refreshFilters=function(){if(this.filters)for(var a=0;a<this.filters.length;a++)switch(this.filters[a][0].tagName.toLowerCase()){case "select":this._loadSelectOptins.apply(this,this.filters[a]);break;case "div":this._loadComboOptins.apply(this,this.filters[a])}};
dhtmlXGridObject.prototype._filters_ready=function(){this.attachEvent("onXLE",this.refreshFilters);this.attachEvent("onRowCreated",function(a,b){if(this._f_rowsBuffer)for(var c=0;c<this._f_rowsBuffer.length;c++)if(this._f_rowsBuffer[c].idd==a)return this._f_rowsBuffer[c]=b});this.attachEvent("onClearAll",function(){this._f_rowsBuffer=null;if(!this.hdr.rows.length)this.filters=[]});window.dhtmlXCombo&&this.attachEvent("onScroll",dhtmlXCombo.prototype.closeAll);this._filters_ready=function(){}};
dhtmlXGridObject.prototype._in_header_text_filter=function(a,b){a.innerHTML="<input type='text' style='width:90%; font-size:8pt; font-family:Tahoma; -moz-user-select:text; '>";a.onclick=a.onmousedown=function(a){return(a||event).cancelBubble=!0};a.onselectstart=function(){return event.cancelBubble=!0};this.makeFilter(a.firstChild,b)};
dhtmlXGridObject.prototype._in_header_text_filter_inc=function(a,b){a.innerHTML="<input type='text' style='width:90%; font-size:8pt; font-family:Tahoma; -moz-user-select:text; '>";a.onclick=a.onmousedown=function(a){return(a||event).cancelBubble=!0};a.onselectstart=function(){return event.cancelBubble=!0};this.makeFilter(a.firstChild,b);a.firstChild._filter=function(){return a.firstChild.value==""?"":function(b){return b.toString().toLowerCase().indexOf(a.firstChild.value.toLowerCase())==0}};this._filters_ready()};
dhtmlXGridObject.prototype._in_header_select_filter=function(a,b){a.innerHTML="<select style='width:90%; font-size:8pt; font-family:Tahoma;'></select>";a.onclick=function(a){(a||event).cancelBubble=!0;return!1};this.makeFilter(a.firstChild,b)};
dhtmlXGridObject.prototype._in_header_select_filter_strict=function(a,b){a.innerHTML="<select style='width:90%; font-size:8pt; font-family:Tahoma;'></select>";a.onclick=function(a){(a||event).cancelBubble=!0;return!1};this.makeFilter(a.firstChild,b);a.firstChild._filter=function(){return!a.firstChild.value?"":function(b){return a.firstChild.value.toLowerCase()==""?!0:b.toString().toLowerCase()==a.firstChild.value.toLowerCase()}};this._filters_ready()};
dhtmlXGridObject.prototype._in_header_combo_filter=function(a,b){a.innerHTML="<div style='width:100%; padding-left:2px; overflow:hidden; font-size:8pt; font-family:Tahoma; -moz-user-select:text;' class='combo'></div>";a.onselectstart=function(){return event.cancelBubble=!0};a.onclick=a.onmousedown=function(a){return(a||event).cancelBubble=!0};this.makeFilter(a.firstChild,b)};
dhtmlXGridObject.prototype._in_header_text_search=function(a,b){a.innerHTML="<input type='text' style='width:90%; font-size:8pt; font-family:Tahoma; -moz-user-select:text;'>";a.onclick=a.onmousedown=function(a){return(a||event).cancelBubble=!0};a.onselectstart=function(){return event.cancelBubble=!0};this.makeSearch(a.firstChild,b)};
dhtmlXGridObject.prototype._in_header_numeric_filter=function(a,b){this._in_header_text_filter.call(this,a,b);a.firstChild._filter=function(){var a=this.value,b,e="==",f=parseFloat(a.replace("=","")),g=null;if(a){if(a.indexOf("..")!=-1)return a=a.split(".."),f=parseFloat(a[0]),g=parseFloat(a[1]),function(a){return a>=f&&a<=g?!0:!1};if(b=a.match(/>=|<=|>|</))e=b[0],f=parseFloat(a.replace(e,""));return Function("v"," if (v "+e+" "+f+" ) return true; return false;")}return""}};
dhtmlXGridObject.prototype._in_header_master_checkbox=function(a,b,c){a.innerHTML=c[0]+"<input type='checkbox' />"+c[1];var d=this;a.getElementsByTagName("input")[0].onclick=function(a){d._build_m_order();var c=d._m_order?d._m_order[b]:b,g=this.checked?1:0;d.forEachRowA(function(a){var b=this.cells(a,c);if(b.isCheckbox())b.setValue(g),b.cell.wasChanged=!0;this.callEvent("onEditCell",[1,a,c,g])});(a||event).cancelBubble=!0}};
dhtmlXGridObject.prototype._in_header_stat_total=function(a,b,c){var d=function(){var a=0;this._build_m_order();for(var c=this._m_order?this._m_order[b]:b,d=0;d<this.rowsBuffer.length;d++){var h=parseFloat(this._get_cell_value(this.rowsBuffer[d],c));a+=isNaN(h)?0:h}return this._maskArr[c]?this._aplNF(a,c):Math.round(a*100)/100};this._stat_in_header(a,d,b,c,c)};
dhtmlXGridObject.prototype._in_header_stat_multi_total=function(a,b,c){var d=c[1].split(":");c[1]="";for(var e=0;e<d.length;e++)d[e]=parseInt(d[e]);for(var f=function(){for(var a=0,c=0;c<this.rowsBuffer.length;c++){for(var e=1,f=0;f<d.length;f++)e*=parseFloat(this._get_cell_value(this.rowsBuffer[c],d[f]));a+=isNaN(e)?0:e}return this._maskArr[b]?this._aplNF(a,b):Math.round(a*100)/100},g=[],b=0;b<d.length;b++)g[d[b]]=!0;this._stat_in_header(a,f,g,c,c)};
dhtmlXGridObject.prototype._in_header_stat_max=function(a,b,c){var d=function(){var a=-999999999;if(this.getRowsNum()==0)return"&nbsp;";for(var c=0;c<this.rowsBuffer.length;c++)a=Math.max(a,parseFloat(this._get_cell_value(this.rowsBuffer[c],b)));return this._maskArr[b]?this._aplNF(a,b):a};this._stat_in_header(a,d,b,c)};
dhtmlXGridObject.prototype._in_header_stat_min=function(a,b,c){var d=function(){var a=999999999;if(this.getRowsNum()==0)return"&nbsp;";for(var c=0;c<this.rowsBuffer.length;c++)a=Math.min(a,parseFloat(this._get_cell_value(this.rowsBuffer[c],b)));return this._maskArr[b]?this._aplNF(a,b):a};this._stat_in_header(a,d,b,c)};
dhtmlXGridObject.prototype._in_header_stat_average=function(a,b,c){var d=function(){var a=0,c=0;if(this.getRowsNum()==0)return"&nbsp;";for(var d=0;d<this.rowsBuffer.length;d++){var h=parseFloat(this._get_cell_value(this.rowsBuffer[d],b));a+=isNaN(h)?0:h;c++}return this._maskArr[b]?this._aplNF(a/c,b):Math.round(a/c*100)/100};this._stat_in_header(a,d,b,c)};dhtmlXGridObject.prototype._in_header_stat_count=function(a,b,c){var d=function(){return this.getRowsNum()};this._stat_in_header(a,d,b,c)};
dhtmlXGridObject.prototype._stat_in_header=function(a,b,c,d){var e=this,f=function(){this.dma(!0);a.innerHTML=(d[0]?d[0]:"")+b.call(this)+(d[1]?d[1]:"");this.dma(!1);this.callEvent("onStatReady",[])};if(!this._stat_events)this._stat_events=[],this.attachEvent("onClearAll",function(){if(!this.hdr.rows[1]){for(var a=0;a<this._stat_events.length;a++)for(var b=0;b<4;b++)this.detachEvent(this._stat_events[a][b]);this._stat_events=[]}});this._stat_events.push([this.attachEvent("onGridReconstructed",f),
this.attachEvent("onXLE",f),this.attachEvent("onFilterEnd",f),this.attachEvent("onEditCell",function(a,b,d){a==2&&(d==c||c&&c[d])&&f.call(this);return!0})]);a.innerHTML=""};

//v.3.0 build 110713

/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
You allowed to use this component or parts of it under GPL terms
To use it on other terms or get Professional edition of the component please contact us at sales@dhtmlx.com
*/