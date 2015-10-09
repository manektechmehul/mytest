var mapcode1 = document.getElementsByName('floorplan_mapping')[0].value;
var myimgmap = new imgmap({mode : "editor",custom_callbacks : {'onStatusMessage' : function(str) {gui_statusMessage(str);}, 'onHtmlChanged'   : function(str) {gui_htmlChanged(str);},	'onModeChanged'   : function(mode) {gui_modeChanged(mode);}, 'onAddArea'       : function(id)  {gui_addArea(id);}, 'onRemoveArea'    : function(id)  {gui_removeArea(id);}, 'onAreaChanged'   : function(obj) {gui_areaChanged(obj);}, 'onSelectArea'    : function(obj) {gui_selectArea(obj);}}, pic_container: document.getElementById('pic_container'),bounding_box : false});
var val=$(".source_accept").attr("value");
var imgurl  = $(".source_accept").attr("value");
gui_loadImage(imgurl);
myimgmap.setMapHTML(mapcode1);