@extends('layouts.template_mag')

@section('content_tree')

<script type="text/javascript">
	$(function(){
		$("#tree").fancytree({
			source: [
				{!! $str_category_list !!}
			],
			autoActivate: false,
			autoCollapse: true,
			autoScroll: true,
			clickFolderMode: 3,
			minExpandLevel: 2,
			tabbable: false, 
			focus: function(event, data) {
				var node = data.node;
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
			},
			click: function(event, data){ 
				var node = data.node,
					orgEvent = data.originalEvent;

				if(node.isActive() && node.data.href){
					window.open(node.data.href, (orgEvent.ctrlKey || orgEvent.metaKey) ? "_blank" : node.data.target);
				}
				@if (!Auth::guest() && Auth::user()->admin)
					adm_get_goods(node);
				@else
					get_goods(node.data);
				@endif

			}
		});
	});
	
</script>

<div>
	<div style="float: left;">
	<div id="tree">
	</div>
	@if (!Auth::guest() && Auth::user()->admin)
		<div>
			<input type="button" id="btn_add_good" value="Добавить товар" onclick="fn_add_goods()" style="display: none;"></br></br></br>
			
			<form id="btn_del_cat" onsubmit="return fn_del_category()" action="" method="POST" style="display: none;">
				<input type="hidden" name="_method" value="DELETE">
				{{ csrf_field() }}
				<input name="del_id_category" value="" /hidden>
				<input type="submit" value="УДАЛИТЬ КАТЕГОРИЮ">
			</form>
			<form id="btn_del_good" onsubmit="return fn_del_good()" action="" method="POST" style="display: none;">
				<input type="hidden" name="_method" value="DELETE">
				{{ csrf_field() }}
				<input name="del_id_good" value="" /hidden>
				<input type="submit" value="УДАЛИТЬ ТОВАР">
			</form>			
		</div>
	@endif	
	</div>

</div>
@endsection

@section('goods_place')
	<div id="goods_place" style="float: left;">
		<h1 id="description_category"></h1>
		@if (!Auth::guest() && Auth::user()->admin)
			<form id="form_add_good" action="" method="POST" enctype="multipart/form-data" style="display: none;">
				<input type="hidden" name="_method" value="PUT">
				{{ csrf_field() }}
				<input name="add_new_good" value="ok" /hidden>
				<input name="id_parent_category" value="" style="display: none;">
				<p><input name="new_name_good" type="text" placeholder="Имя нового товара" required></p>
				<p><input name="new_description_good" type="text" placeholder="Описание товара" required></p>
				<p><input name="new_img_good[]" type="file" accept="image/*" multiple></p>
				<div class="div_list_parent_propery_good"></div>
				<div class="div_list_propery_good"></div>
				<input type="button" value="Добавить свойство" onclick="fn_add_new_property_good()">
				<p><input type="submit" value="Создать товар"></p>
			</form>	
			<form id="form_add_category" action="" method="post" style="display: none;">
				<input type="hidden" name="_method" value="PUT">
				{{ csrf_field() }}			
				<input name="add_new_category" value="ok" /hidden>
				<input name="id_parent_category" value="" /hidden>
				<p><input name="new_name_category" type="text" placeholder="Имя новой категории" required></p>
				<p><input name="new_description_category" type="text" placeholder="Описание категории" required></p>
				<div class="div_list_propery">
					<p><input class="list_property" name="list_property_[0]" type="text" placeholder="Название свойства"></p>
				</div>
				<input type="button" value="Добавить свойство" onclick="fn_add_new_property()">
				<p><input type="submit" value="Создать категорию"></p>
			</form>			
		@endif
		<div id="goods_lists"></div>
	</div>
@endsection