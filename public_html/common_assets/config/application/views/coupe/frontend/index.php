



<div id="interface">

	<div id="top_panel">
		<div class="b_logo">
		
		</div>

		<div class="top_panel_group buttons_top_panel">



			<div title="Новый проект" id="clear_project" class="slide_button slide_right">
				<i class="glyphicon glyphicon-file"></i>
			</div>
			<div title="Загрузить проект" id="load_button" class="slide_button slide_right">
				<i class="glyphicon glyphicon-folder-open"></i>
			</div>
			<div title="Сохранить проект" id="save_button" class="slide_button slide_right">
				<i class="glyphicon glyphicon-floppy-save"></i>
			</div>

            <div class="slide_button slide_right">
                <i class="glyphicon glyphicon-print"></i>
                <div class="top_submenu">
                    <ul>

                        <li title="Открыть PDF для печати (в новой вкладке)" id="print_button" class="submenu_button">
                            <i class="glyphicon glyphicon-print"></i> <span>Открыть PDF для печати <br> (в новой вкладке)</span>
                        </li>
                        <li title="Скачать PDF" id="save_pdf_button" class="submenu_button">
                            <i class="glyphicon glyphicon-save"></i> <span>Скачать PDF</span>
                        </li>

                    </ul>
                </div>
            </div>
			<div title="Сохранить скриншот" id="screen_save" class="slide_button slide_right">
				<i class="glyphicon glyphicon-picture"></i>
			</div>
			            <div title="Контуры"  class="slide_button slide_right">
			                <i class="glyphicon glyphicon-pencil"></i>
			                <div class="top_submenu">
			                    <ul>
			                        <li id="mode_button" class="submenu_button">
			                            <i class="glyphicon glyphicon-pencil"></i> <span>Режим контуров</span>
			                        </li>
			                        <li id="transparent_edges_mode" class="submenu_button">
			                            <i class="glyphicon glyphicon-pencil"></i> <span>Прозрачные контуры</span>
			                        </li>
			                    </ul>
			                </div>
			            </div>
			<!--            <div class="slide_button slide_right">-->
			<!--                <i title="Проекции" class="glyphicon glyphicon-facetime-video"></i>-->
			<!--                <div class="top_submenu">-->
			<!--                    <ul>-->
			<!--                        <li id="camera_3d_mode" class="submenu_button clicked">-->
			<!--                            <i class="mdi mdi-cube-outline"></i> <span>3D Режим</span>-->
			<!--                        </li>-->
			<!--                        <li id="camera_projection_top" class="submenu_button">-->
			<!--                            <i></i> <span>Проекция сверху</span>-->
			<!--                        </li>-->
			<!--                        <li id="camera_projection_front" class="submenu_button">-->
			<!--                            <i></i> <span>Проекция передней стены</span>-->
			<!--                        </li>-->
			<!--                        <li id="camera_projection_back" class="submenu_button">-->
			<!--                            <i></i> <span>Проекция задней стены</span>-->
			<!--                        </li>-->
			<!--                        <li id="camera_projection_left" class="submenu_button">-->
			<!--                            <i></i> <span>Проекция левой стены</span>-->
			<!--                        </li>-->
			<!--                        <li id="camera_projection_right" class="submenu_button">-->
			<!--                            <i></i> <span>Проекция правой стены</span>-->
			<!--                        </li>-->
			<!--                    </ul>-->
			<!--                </div>-->
			<!--            </div>-->
			<div class="slide_button slide_right">
				<i title="Показывать размеры" class="glyphicon glyphicon-resize-horizontal"></i>
				<div class="top_submenu">
					<ul>
						<li id="no_sizes_button" class="submenu_button">
							<i class="glyphicon glyphicon-resize-horizontal"></i> <span>Без размеров</span>
						</li>

						<li id="all_sizes_button" class="submenu_button clicked">
							<i class="glyphicon glyphicon-resize-horizontal"></i> <span>Все размеры</span>
						</li>

					</ul>
				</div>
			</div>

			<div title="На весь экран" id="full_screen_button" class="slide_button slide_right">
				<i class="glyphicon glyphicon-fullscreen"></i>
			</div>
<!--			<div title="Экспортировать в obj" id="obj_export_button" class="slide_button slide_right">-->
<!--				<i class="glyphicon glyphicon glyphicon-compressed"></i>-->
<!--			</div>-->
<!--			<div title="Помощь" id="help_button" class="slide_button slide_right">-->
<!--				<i class="glyphicon glyphicon-question-sign"></i>-->
<!--			</div>-->


		</div>

		<div class="top_panel_group">

			<button type="button" id="show_order_modal" class="btn btn-sm">
				Отправить на расчет
			</button>





		</div>
        <div class="top_panel_group">
            <p style="padding: 8px 6px;" class="k_sum hidden">Стоимость: <span>0 р.</span> </p>
        </div>
        <i class="sumarr glyphicon glyphicon-triangle-bottom"></i>

        <div class="top_panel_group client_data">
            <?php echo $address_line?>
        </div>

	</div>

	<div id="left_panel">

		<div class="accordion_block">
			<div class="accordion_heading cabinet_block">
				<i class="mdi mdi-view-dashboard-variant" data-icon="mdi-view-dashboard-variant"></i><span>Шкаф</span>
			</div>
			<div class="accordion_body accordion_visible">
				<button id="cabinet_size" class="ltab btn-block btn btn-default btn-sm" data-target="cabinet_size_panel">
					<?php echo $lang_arr['sizes']?>
				</button>
				<button id="cabinet_materials"class="ltab btn-block btn btn-default btn-sm" data-target="cabinet_material_panel">
					Материал
				</button>
				<button id="cabinet_configuration" class="ltab btn-block btn btn-default btn-sm"
				        data-target="cabinet_configuration_panel">
					Наполнение
				</button>
                <button id="cabinet_additions" class="ltab btn-block btn btn-default btn-sm"
                        data-target="cabinet_additions_panel">
                    Доп. секции
                </button>
			</div>
		</div>

		<div class="accordion_block">
			<div class="accordion_heading doors_block">
				<i class="mdi mdi-view-column" data-icon="mdi-view-column"></i><span>Двери</span>
			</div>
			<div class="accordion_body">
				<button id="door_configuration" class="ltab btn-block btn btn-default btn-sm"
				        data-target="door_configuration_panel">
					Конфигурация
				</button>
				<button class="ltab btn-block btn btn-default btn-sm door_profile_material" data-target="door_profile_panel">
					Тип профиля
				</button>
                <button class="ltab btn-block btn btn-default btn-sm door_profile_material" data-target="door_profile_materials_panel">
                    Декоры профиля
                </button>
				<button id="door_material" class="ltab btn-block btn btn-default btn-sm" data-target="door_materials_panel">
					Наполнение
				</button>
			</div>
		</div>

        <div class="accordion_block not_working ltab hidden templates_button" data-target="templates_panel">
            <div class="accordion_heading">
                <i class="mdi mdi-auto-fix"></i><span>Проекты</span>
            </div>


        </div>

		<!--        <div class="accordion_block">-->
		<!--            <div class="accordion_heading room_block">-->
		<!--                <i class="mdi mdi-floor-plan" data-icon="mdi-floor-plan"></i><span>Помещение</span>-->
		<!--            </div>-->
		<!--            <div class="accordion_body">-->
		<!--                <button class="lta btn-block btn btn-default btn-smb" data-target="">-->
		<!--                    Размеры-->
		<!--                </button>-->
		<!--                <button class="ltab btn-block btn btn-default btn-sm" data-target="">-->
		<!--                    Коммуникации-->
		<!--                </button>-->
		<!--                <button class="ltab btn-block btn btn-default btn-sm" data-target="">-->
		<!--                    Конструкции-->
		<!--                </button>-->
		<!--                <button class="ltab btn-block btn btn-default btn-sm" data-target="">-->
		<!--                    Стены-->
		<!--                </button>-->
		<!--                <button class="ltab btn-block btn btn-default btn-sm" data-target="">-->
		<!--                    Пол-->
		<!--                </button>-->
		<!--            </div>-->
		<!--        </div>-->


	</div>

    <div class="left_subpanel w200 templates_panel" data-panel="templates_panel">
        <div class="mask">
            <div class="left_subpanel_heading">
                <p>Готовые проекты</p>
                <div class="panel_close"><i class="mdi mdi-close"></i></div>
            </div>

            <div class="left_subpanel_content">
                <div class="left_subpanel_content_scrollable_part" style="height: 100%"></div>
            </div>
        </div>
    </div>


	<div class="left_subpanel w200" data-panel="cabinet_size_panel">
		<div class="mask">
			<div class="left_subpanel_heading">
				<p>Размеры шкафа</p>
				<div class="panel_close"><i class="mdi mdi-close"></i></div>
			</div>

			<div class="left_subpanel_content">
				<div class="left_subpanel_content_fixed_part">
					<div class="cabinet_sizes_block">
						<div class="input_block">
							<p>Ширина шкафа,<?php echo $lang_arr['units']?></p>
							<input value="1500" id="cabinet_width" type="text">
						</div>
						<div class="input_block">
							<p>Высота шкафа, <?php echo $lang_arr['units']?></p>
							<input value="2300" id="cabinet_height" type="text">
						</div>
						<div class="input_block">
							<p>Глубина шкафа, <?php echo $lang_arr['units']?></p>
							<input value="500" id="cabinet_depth" type="text">
						</div>

                        <div class="input_block">
                            <p>Высота цоколя, <?php echo $lang_arr['units']?></p>
                            <input value="100" id="cabinet_cokol_height" type="text">
                        </div>

						<div class="input_block">
							<input checked type="checkbox" data-wall="back_wall" class="wall_visible"
							       id="cabinet_back_wall_visible">
							<label for="cabinet_back_wall_visible">Задняя стенка</label>
						</div>

						<div class="input_block">
							<input checked type="checkbox" data-wall="top_wall" class="wall_visible"
							       id="cabinet_top_wall_visible">
							<label for="cabinet_top_wall_visible">Верхняя стенка</label>
						</div>

						<div class="input_block">
							<input checked type="checkbox" data-wall="bottom_wall" class="wall_visible"
							       id="cabinet_bottom_wall_visible">
							<label for="cabinet_bottom_wall_visible">Нижняя стенка</label>
						</div>

						<div class="input_block">
							<input checked type="checkbox" data-wall="left_wall" class="wall_visible"
							       id="cabinet_left_wall_visible">
							<label for="cabinet_left_wall_visible">Левая стенка</label>
						</div>

						<div class="input_block">
							<input checked type="checkbox" data-wall="right_wall" class="wall_visible"
							       id="cabinet_right_wall_visible">
							<label for="cabinet_right_wall_visible">Правая стенка</label>
						</div>

					</div>
				</div>
				<div class="left_subpanel_content_scrollable_part"></div>
			</div>
		</div>
	</div>

	<div class="left_subpanel w200" data-panel="cabinet_configuration_panel">
		<div class="mask">
			<div class="left_subpanel_heading">
				<p>Наполнение шкафа</p>
				<div class="panel_close"><i class="mdi mdi-close"></i></div>
			</div>

			<div class="left_subpanel_content">
				<div class="left_subpanel_content_fixed_part"></div>
				<div class="left_subpanel_content_scrollable_part">
					<div class="shelve_horizontal" style="padding: 5px; float: left; width: 50%; "><img
							style="max-width:100%" src="/common_assets_kupe/images/polka.png"></div>
					<div class="shelve_vertical" style="padding: 5px;float: left; width: 50%; "><img
							style="max-width:100%" src="/common_assets_kupe/images/peregor.png"></div>
					<div class="rail_horizontal" style="padding: 5px;float: left; width: 50%;  "><img
							style="max-width:100%" src="/common_assets_kupe/images/truba_hor%5D.png"></div>
					<div class="rail_vertical" style="padding: 5px;float: left; width: 50%;  "><img
							style="max-width:100%" src="/common_assets_kupe/images/truba_vert.png"></div>
                    <div class="locker" style="padding: 5px; float: left; width: 50%; ">
                        <img style="max-width:100%" src="/common_assets_kupe/images/yash.jpg">
                    </div>

                    <div class="locker_common" style="padding: 5px; float: left; width: 50%; ">
                        <img style="max-width:100%" src="/common_assets_kupe/images/yash2.jpg">
                    </div>




				</div>
			</div>
		</div>
	</div>

	<div class="left_subpanel w200 cabinet_material_panel" data-panel="cabinet_material_panel">
		<div class="mask">
			<div class="left_subpanel_heading">
				<p>Материал шкафа</p>
				<div class="panel_close"><i class="mdi mdi-close"></i></div>
			</div>

			<div class="left_subpanel_content">
				<div class="left_subpanel_content_fixed_part"></div>
				<div class="left_subpanel_content_scrollable_part">
					<div class="cabinet_material" data-id="1"
					     style="width: 50px; height: 50px; background: #554444"></div>
					<div class="cabinet_material" data-id="2"
					     style="width: 50px; height: 50px; background: #b7af3f"></div>
					<div class="cabinet_material" data-id="4"
					     style="width: 50px; height: 50px; background: #888888"></div>
				</div>
			</div>
		</div>
	</div>

    <div class="left_subpanel w200" data-panel="cabinet_additions_panel">
        <div class="mask">
            <div class="left_subpanel_heading">
                <p>Доп. секции</p>
                <div class="panel_close"><i class="mdi mdi-close"></i></div>
            </div>

            <div class="left_subpanel_content">
                <div class="left_subpanel_content_fixed_part">
                    <div class="add_left">
                        <div class="input_block">
                            <input type="checkbox" data-add="left" class="addition_visible_left"
                                   id="cabinet_additions_left_active">
                            <label for="cabinet_additions_left_active">Секция слева</label>

                            <div class="add_left_inputs pt-2 hidden">
                                <div class="input_block">
                                    <p>Ширина, <?php echo $lang_arr['units']?></p>
                                    <input value="300" id="add_left_width" type="text">
                                </div>

                                <div class="input_block">
                                    <p>Количество полок</p>
                                    <input value="6" id="add_left_shelves" type="text">
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="add_right">
                        <div class="input_block">
                            <input type="checkbox" data-add="right" class="addition_visible_right"
                                   id="cabinet_additions_right_active">
                            <label for="cabinet_additions_right_active">Секция справа</label>
                        </div>

                        <div class="add_right_inputs pt-2 hidden">
                            <div class="input_block">
                                <p>Ширина, <?php echo $lang_arr['units']?></p>
                                <input value="300" id="add_right_width" type="text">
                            </div>

                            <div class="input_block">
                                <p>Количество полок</p>
                                <input value="6" id="add_right_shelves" type="text">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="left_subpanel_content_scrollable_part"></div>
            </div>
        </div>
    </div>


    <div class="left_subpanel w200" data-panel="door_configuration_panel">
		<div class="mask">
			<div class="left_subpanel_heading">
				<p>Конфигурация дверей</p>
				<div class="panel_close"><i class="mdi mdi-close"></i></div>
			</div>

			<div class="left_subpanel_content">
				<div class="left_subpanel_content_fixed_part">
					<div class="input_block">
						<p>Количество дверей</p>
						<input value="2" id="doors_count" type="text">
					</div>
				</div>
				<div class="left_subpanel_content_scrollable_part">
					<div class="profile_horizontal" style="padding: 5px; float: left; width: 50%; "><img
							style="max-width:100%" src="/common_assets_kupe/images/polka.png"></div>
					<div class="profile_vertical" style="padding: 5px;float: left; width: 50%; "><img
							style="max-width:100%" src="/common_assets_kupe/images/peregor.png"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="left_subpanel w200 profile_panel" data-panel="door_profile_panel">
		<div class="mask">
			<div class="left_subpanel_heading">
				<p>Профиль</p>
				<div class="panel_close"><i class="mdi mdi-close"></i></div>
			</div>

			<div class="left_subpanel_content">
				<div class="left_subpanel_content_fixed_part"></div>
				<div class="left_subpanel_content_scrollable_part">

				</div>
			</div>
		</div>
	</div>

    <div class="left_subpanel w200 profile_materials_panel" data-panel="door_profile_materials_panel">
        <div class="mask">
            <div class="left_subpanel_heading">
                <p>Профиль</p>
                <div class="panel_close"><i class="mdi mdi-close"></i></div>
            </div>

            <div class="left_subpanel_content">
                <div class="left_subpanel_content_fixed_part"></div>
                <div class="left_subpanel_content_scrollable_part">

                </div>
            </div>
        </div>
    </div>

	<div class="left_subpanel w200 door_material_panel" data-panel="door_materials_panel">
		<div class="mask">
			<div class="left_subpanel_heading">
				<p>Материал дверей</p>
				<div class="panel_close"><i class="mdi mdi-close"></i></div>
			</div>

			<div class="left_subpanel_content">
				<div class="left_subpanel_content_fixed_part"></div>
				<div class="left_subpanel_content_scrollable_part">
					<div class="door_material" data-id="1" style="width: 50px; height: 50px; background: #554444"></div>
					<div class="door_material" data-id="2" style="width: 50px; height: 50px; background: #b7af3f"></div>
					<div class="door_material" data-id="4" style="width: 50px; height: 50px; background: #888888"></div>
				</div>
			</div>
		</div>
	</div>


	<div class="trash_icon mdi mdi-delete"></div>
	<div class="trash_icon_open mdi mdi-delete-empty"></div>

</div>

<div id="viewport">

</div>

<div class="start_modal modal_wrapper">
	<div class="modal_content">
		<div class="row">
			<div class="col-xs-12">
				<h2>Укажите параметры шкафа</h2>
			</div>
		</div>
		<div class="row start_project_params_row">
			<div class="col-xs-2">
				<p>Ширина, <?php echo $lang_arr['units']?></p>
				<input id="start_project_width" value="1500" type="number">
			</div>
			<div class="col-xs-2">
				<p>Высота, <?php echo $lang_arr['units']?></p>
				<input id="start_project_height" value="2300" type="number">
			</div>
			<div class="col-xs-2">
				<p>Глубина, <?php echo $lang_arr['units']?></p>
				<input id="start_project_depth" value="500" type="number">
			</div>
            <div class="col-xs-3">
                <p>Высота цоколя, <?php echo $lang_arr['units']?></p>
                <input id="start_project_cokol_height" value="100" type="number">
            </div>
			<div class="col-xs-3">
				<p>Кол-во дверей</p>
				<input id="start_project_doors_count" value="2" type="number">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-3"></div>
			<div class="col-xs-6">
				<button type="button" id="start_project" class="btn btn-primary btn-block">Начать проектирование
				</button>
			</div>
			<div class="col-xs-3"></div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<h2>Или загрузите проект</h2>
			</div>
			<div class="col-xs-4"></div>
			<div class="col-xs-4">
				<button type="button" id="load_project_start" class="btn btn-primary btn-block">Загрузить</button>
			</div>
			<div class="col-xs-4"></div>
		</div>

        <div id="projects_carousel" class="projects_carousel">

        </div>


	</div>
</div>

<div class="preloader">
	<div class="loader"></div>
	<p><?php echo $lang_arr['loading']?></p>
</div>

<div class="hidden">
	<input type="file" id="load_input" accept=".dbs">
	<a id="screen_save_download"></a>
    <div id="base_url" data-value="<?php echo $base_url?>"></div>
    <div id="parent" data-value="<?php echo $parent?>"></div>
</div>


<div class="client_logo">
    <img src="<?php echo $logo ?>" alt="">
</div>