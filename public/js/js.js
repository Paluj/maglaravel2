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
	//$.post("",{id_category: n_data.id_category, '_token': $('[name="_token"]').val()}, function (data){parse_goods_data(data)},async: false, "json");
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
	
	//$.post("",{id_goods: id.substr(6), '_token': $('[name="_token"]').val()}, function (data){parse_goods_once_data(data, id.substr(6))}, "json");
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




