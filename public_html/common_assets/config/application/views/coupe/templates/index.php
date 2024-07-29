<div class="col-sm-12 col-md-12" id="content">
	<h3>Список проектов</h3>


	<div class="row">
		<div class="col-xs-12">
			<a class="btn btn-default" href="<?php echo site_url('templates/add_coupe/') ?>" role="button">Добавить
				проект</a>
		</div>
	</div>

	<table class="table table-hover items_table">
		<thead>
		<tr>
			<th><?php echo $lang_arr['icon']?></th>
			<th><?php echo $lang_arr['name']?></th>
			<th><?php echo $lang_arr['is_visible']?></th>
			<th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
		</tr>
		</thead>
		<tbody>

		<?php foreach ($templates as $item):?>
			<tr>
				<td>
					<div class="item_image">
						<?php if($item['icon'] != null): ?>
							<img src="<?php echo $this->config->item('const_path').$item['icon']?>?<?php echo md5(date('m-d-Y-His A e'));?>" alt="">
							<!--                            <div style="background: url('')"></div>-->
						<?php endif;?>
					</div>
				</td>
				<td>
					<?php echo $item['name']?>
				</td>

				<td>
					<div class="actions_list">
						<a class="is_visible <?php if($item['active'] ==0) echo 'disabled' ?>"><span class="glyphicon glyphicon-eye-open"></a>
					</div>
				</td>
				<td>
					<div class="actions_list">
						<a href="<?php echo site_url('templates/edit_coupe/' . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
						<a class="delete_button" href="<?php echo site_url('templates/remove_coupe/'. $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
					</div>
				</td>
			</tr>

		<?php endforeach; ?>
		</tbody>
	</table>


</div>

<script>
    $(document).ready(function () {
        $('.delete_button').click(function (e) {
            e.preventDefault();
            if (confirm('<?php echo $lang_arr['delete_confirm_message']?>')) {
                window.location.href = $(this).attr('href');
            } else {

            }
        })


    })
</script>

<style>
	.table.items_table>tbody>tr>td {
		vertical-align: middle;
	}

	.actions_list a{
		margin-right: 10px;
		margin-top: 12px;
		display: inline-block;
		font-size: 16px;
	}

	.actions_list a.delete_button{
		color: #ff0000;
	}

	.actions_list a.delete_button.disabled{
		color: #9d9d9d;
		pointer-events: none;
	}

	.actions_list a.is_visible{
		color: #0e7b0d;
		pointer-events: none;
	}

	.actions_list a.is_visible.disabled{
		color: #9d9d9d;
	}

	.item_image{
		width: 100px;
		/*height: 50px;*/
		border: 1px solid;
	}

	.item_image > img{
		width: 100%;
		height: auto;
	}

	.option.top_cat{
		font-weight: bold;
	}

	.option.sub_cat{
		padding-left: 40px;
	}

	.sp-input{
		background: #ffffff;
	}
</style>