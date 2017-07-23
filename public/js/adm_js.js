function adm_get_goods(n_data) {
	$("#form_add_good").hide();
	$("#form_add_category").hide();
	$("#btn_del_good").hide();
	var parent_data = n_data;
	window.arr_id_category = [];
	while (parent_data.parent.data.id_category) {
		window.arr_id_category.push(parent_data.parent.data.id_category);
		parent_data = parent_data.parent;
	}	
	if (n_data.data.id_category == "new_category") {
		$("#btn_del_cat").hide();
		$("#btn_add_good").hide();
		$("#goods_lists").empty();
		$("#description_category").text('Новая категория в "'+n_data.parent.title+'"');	
		if (n_data.parent.data.id_category) $("#description_category").attr("id_category", n_data.parent.data.id_category);
		else $("#description_category").attr("id_category", 0);
		
		if (window.arr_id_category.length != 0) {
			$.ajaxSetup({
				url: '',
				async: false,
				type: 'PATCH',
				dataType: 'json'

			});	
			$.ajax({
				data: {"get_arr_property": "ok", '_token': $('[name="_token"]').val(),'arr_id_category[]':window.arr_id_category}, success: function(data){parse_parent_propertys(data);}
			});			
		}	
		else parse_parent_propertys();

	} else {
		$("#btn_del_cat").show();
		$("#btn_add_good").show();
		get_goods(n_data.data);
	}	
	
}

function parse_parent_propertys(data) {
	if (data) {
		var str="";
		data.forEach(function(item, i, data)  {
			str+=item.name_property+", ";
		});			
		$("#goods_lists").append('<div class="parent_propertys">Свойства внешних категорий : '+str+'</div>');
	}
	$('#form_add_category [name="id_parent_category"]').attr("value", $('#description_category').attr("id_category"));
	$("#form_add_category").show();
}
	
function fn_add_new_property() {
	$(".div_list_propery").append('<p><input class="list_property" name="list_property_['+$(".list_property").length+']" type="text" placeholder="Название свойства"></p>');
}	
	
function fn_add_goods() {
	$("#btn_del_cat").hide();
	$("#btn_add_good").hide()
	$("#btn_del_good").hide();
	$(".div_list_parent_propery_good").empty();
	$(".div_list_propery_good").empty();
	$("#goods_lists").empty();
	window.arr_id_category.push($("#description_category").attr("id_category"));
	$.ajaxSetup({
		url: '',
		async: false,
		type: 'PATCH',
		dataType: 'json'

	});	
	$.ajax({
		data: {"get_arr_property": "ok", '_token': $('[name="_token"]').val(),'arr_id_category[]':window.arr_id_category}, success: function(data){parse_parent_propertys_good(data);}
	});	
	$('#form_add_good [name="id_parent_category"]').attr("value", $("#description_category").attr("id_category"));
}	

function parse_parent_propertys_good(data){
	data.forEach(function(item, i, data)  {
			fn_add_new_property_good(item.id,item.name_property)
		});		
	$("#form_add_good").show();
}

function fn_add_new_property_good(id,name){
	if (id) {
		$(".div_list_parent_propery_good").append('<div class="list_new_parent_property_goods"><p>'+name+' : <input class="list_property_good" name="list_parent_property_value_id_'+id+'" type="text" placeholder="Знечение свойства"></p></div>');
	} else {
		$(".div_list_propery_good").append('<div class="list_new_property_goods"><p><input class="list_property_good" name="list_property_name_value_['+$(".list_new_property_goods").length+'][0]" type="text" placeholder="Название свойства"><input class="list_property_good" name="list_property_name_value_['+$(".list_new_property_goods").length+'][1]" type="text" placeholder="Знечение свойства"></p></div>');
	}
}

function fn_del_category() {
	var tree = $("#tree").fancytree("getTree");
	$('[name="del_id_category"]').attr('value', tree.activeNode.data.id_category);
 	var resultActionUser = confirm("Удалить категорию "+tree.activeNode.title+" вместе со всем содержимым?");
	return resultActionUser;
}	

function fn_del_good() {
	$('[name="del_id_good"]').attr('value', $('#goods_lists').attr('id_goods'));
 	var resultActionUser = confirm("Удалить товар "+$('#description_category').text()+" ?");
	return resultActionUser;
}	

function get_goods(n_data) {
	$("#description_category").text(n_data.description_category);
	$("#description_category").attr("id_category", n_data.id_category);
	$("#goods_lists").empty();
	$.ajaxSetup({
		url: '',
		async: false,
		type: 'POST',
		dataType: 'json'

	});	
	$.ajax({
		data: {id_category: n_data.id_category, '_token': $('[name="_token"]').val()},
		success: function(data){parse_goods_data(data);}
	});	
	//$.post("/public/admin",{id_category: n_data.id_category, '_token': $('[name="_token"]').val()}, function (data){parse_goods_data(data)}, "json");
}

function parse_goods_data(data) {
	if (data.length > 0) {
		data.forEach(function(item, i, data) {
			$("#goods_lists").append('<div class="goods_list" id="goods_'+item.id+'"><img src="img/'+item.value.substr(8)+'"/><p>'+item.name_goods+'</p></div>');
		});
		$(".goods_list").click(function() {
			get_goods_once(this.id);
		});
	}
}

function get_goods_once(id) {
	$("#btn_del_cat").hide();
	$("#btn_del_good").show()
	$("#description_category").text($("#"+id).children("p").text());
	$("#goods_lists").empty();
	$.ajaxSetup({
		url: '',
		async: false,
		type: 'PATCH',
		dataType: 'json'

	});	
	$.ajax({
		data: {id_goods: id.substr(6), '_token': $('[name="_token"]').val()},
		success: function(data){parse_goods_once_data(data, id.substr(6));}
	});	
	//$.post("/public/admin",{id_goods: id.substr(6), '_token': $('[name="_token"]').val()}, function (data){parse_goods_once_data(data, id.substr(6))}, "json");
}

function parse_goods_once_data(data, id) {
	$("#goods_lists").append('<div class="img_goods"></div>');
	$("#goods_lists").append('<div class="description_goods"></div>');
	$("#goods_lists").attr("id_goods", id);
	data.forEach(function(item, i, data)  {
		if (item.name_property == "описание") {
			$(".description_goods").text(item.value);
		} else {
			if (item.value.substr(0,8)=="img_url:") $(".img_goods").append('<img src="img/'+item.value.substr(8)+'"/>');
			else $("#goods_lists").append('<div class="property_goods">'+item.name_property+' : '+item.value+'</div>');
		} 	 
	});	
}