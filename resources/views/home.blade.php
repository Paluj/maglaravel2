@extends('layouts.app')

@section('content')

<script type="text/javascript">
	$(function(){
		// --- Initialize sample trees
		$("#tree").fancytree({
			source: [
				{!! $str_category_list !!}
			],
			autoActivate: false, // we use scheduleAction()
			autoCollapse: true,
//			autoFocus: true,
			autoScroll: true,
			clickFolderMode: 3, // expand with single click
			minExpandLevel: 2,
			tabbable: false, // we don't want the focus frame
			// scrollParent: null, // use $container
			focus: function(event, data) {
				var node = data.node;
				// Auto-activate focused node after 1 second
				if(node.data.href){
					node.scheduleAction("activate", 1000);
				}
			},
			blur: function(event, data) {
				data.node.scheduleAction("cancel");
			},
			activate: function(event, data){
				var node = data.node,
					orgEvent = data.originalEvent;

				if(node.data.href){
					window.open(node.data.href, (orgEvent.ctrlKey || orgEvent.metaKey) ? "_blank" : node.data.target);
				}
				//console.log(node.data.id_category);
			},
			click: function(event, data){ // allow re-loads
				var node = data.node,
					orgEvent = data.originalEvent;

				if(node.isActive() && node.data.href){
					// data.tree.reactivate();
					window.open(node.data.href, (orgEvent.ctrlKey || orgEvent.metaKey) ? "_blank" : node.data.target);
				}
				{{ $is_get_goods }}
			}
		});
	});
	
</script>
{{ csrf_field() }}
<div>
	<div style="float: left;">
	<div id="tree">
	</div>
	@if (!Auth::guest() && Auth::user()->admin)
		<form id="form_add_good" action="/public/mag" method="POST" enctype="multipart/form-data" style="display: none;">
			{{ csrf_field() }}
			<input name="add_new_good" value="ok" /hidden>
			<input name="id_parent_category" value="" style="display: none;">
			<p><input name="new_name_good" type="text" placeholder="Имя нового товара"></p>
			<p><input name="new_description_good" type="text" placeholder="Описание товара"></p>
			<p><input name="new_img_good[]" type="file" accept="image/*" multiple></p>
			<div class="div_list_parent_propery_good"></div>
			<div class="div_list_propery_good"></div>
			<input type="button" value="Добавить свойство" onclick="fn_add_new_property_good()">
			<p><input type="submit" value="Создать товар"></p>
		</form>
		<div>
			<input type="button" id="btn_add_good" value="Добавить товар" onclick="fn_add_goods()" style="display: none;"></br></br></br>
			
			<form id="btn_del_cat" onsubmit="return fn_del_category()" action="/public/mag" method="POST" style="display: none;">
				{{ csrf_field() }}
				<input name="del_id_category" value="" /hidden>
				<input type="submit" value="УДАЛИТЬ КАТЕГОРИЮ">
			</form>
			<form id="btn_del_good" onsubmit="return fn_del_good()" action="/public/mag" method="POST" style="display: none;">
				{{ csrf_field() }}
				<input name="del_id_good" value="" /hidden>
				<input type="submit" value="УДАЛИТЬ ТОВАР">
			</form>			
		</div>
	@endif	
	</div>
	<div id="goods_place" style="float: left;">
		<h1 id="description_category">
		</h1>
		<div id="goods_lists">
		</div>
	</div>


</div>


@endsection
