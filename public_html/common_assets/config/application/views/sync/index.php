<div class="wrapper wrapper-content  animated fadeInRight">
	<?php if ( ! $this->config->item( 'sub_account' ) ) {
		$this->config->set_item( 'sub_account', false );
	} ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="nav-align-top nav-tabs-shadow mb-6" >
                <ul class="nav nav-tabs" role="tablist">
                    <?php if ( $this->config->item( 'sub_account' ) == false ): ?>
                        <li class="nav-item">
                            <button
                            type="button"
                            class="nav-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-1"
                            aria-controls="tab-1"
                            aria-selected="true">
                            API
                            </button>
                        </li>
                    <?php endif; ?>

                    <?php if ( $this->config->item( 'sub_account' ) == false ): ?>
                        <li class="nav-item">
                            <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-3"
                            aria-controls="tab-3"
                            aria-selected="true">
                            API Синхронизация цен
                            </button>
                      </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link <?php if ( $this->config->item( 'sub_account' ) == true ) echo 'active'?>"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#tab-2"
                          aria-controls="tab-2"
                          aria-selected="true">
                          API Отправка данных о проекте
                        </button>
                      </li>
                </ul>
                <div class="tab-content">
                    <?php if ( $this->config->item( 'sub_account' ) == false ): ?>
                        <div id="tab-1" class="tab-pane fade show active" role="tabpanel">
                            <div class="panel-body">
                                <div class="ibox-content">
                                    <div class="form-group mb-3">
                                        <a target="_blank" href="/docs/index.php">Документация</a>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Основной URL: </label>
                                        <input class="form-control" readonly type="text"
                                               value="<?php echo $this->config->item( 'const_path' ) ?>config/index.php">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="sync_key">Ключ для автоматической синхронизации: </label>
                                        <input class="form-control" readonly type="text" id="sync_key"
                                               value="<?php echo crypt( $this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56' ) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( $this->config->item( 'sub_account' ) == false ): ?>
                        <div id="tab-3" class="tab-pane fade" role="tabpanel">
                            <div class="panel-body">
                                <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <p>Автоматическая синхронизация цен работает только в режиме дилера.</p>
                                            <p>Для того, чтобы автоматически обновить цены на модули, необходимо загрузить данные, в формате
                                                JSON</p>
                                            <p>Данные должны быть массивом с объектами.</p>
                                            <p>У объекта должно быть 2 свойства:</p>
                                            <ul>
                                                <li>code - артикул</li>
                                                <li>price - цена</li>
                                            </ul>
                                            <br>
                                            <p>Пример данных: </p>
                                            <br>
                                            <code>
                                                [{"code":"КП-0014", "price":"3645.60"},{"code":"КП-0016",
                                                "price":"4800.42"},{"code":"НР-0018","price": "545.25"}]
                                            </code>
                                            <br>
                                            <br>
                                            <p>
                                                Вы можете загрузить данные вручную с помощью файла, либо автоматически отправив POST запрос на
                                                Url для автоматической синхронизации указанный ниже
                                            </p>
                                            <p>В POST запросе обязательно должны быть указаны следующие ключи:</p>
                                            <ul>
                                                <li>data - данные с ценами и артикулами в формате JSON</li>
                                                <li>sync_key - Ключ для автоматической синхронизации указанный ниже</li>
                                            </ul>
                                            <p>В ответ на POST запрос придет массив с артикулами, которые были найдены в базе конструктора</p>


                                            <p>Пример реализации запроса 1С: <a target="_blank" href="https://broskokitchenplanner.com/1c_example.html">Открыть</a> </p>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="json_data">Введите данные: </label>
                                            <textarea class="form-control mb-3" rows="5" id="json_data"></textarea>
                                            <button type="button" id="parse_data" class="btn btn-success btn-sm">Обработать</button>
                                        </div>
                                        <div class="form-group mb-3" style="margin-bottom: 30px;">
                                            <label style="font-weight: bold" for="xlsx_data"> Импорт из Excel (только xlsx) </label>
                                            <p>Пример файла xlsx: <a download="" href="/xlsx_example.xlsx">Скачать</a></p>
                                            <input class="form-control mb-3" type="file" id="xlsx_data" accept=".xlsx">
                                            <button type="button" id="parse_xlsx_data" class="btn btn-success btn-sm">Обработать</button>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="url_auto">Url для автоматической синхронизации: </label>
                                            <input class="form-control" readonly type="text" id="url_auto"
                                                   value="<?php echo $this->config->item( 'const_path' ) ?>config/index.php/sync/prices_input">
                                        </div>
                                    <div class="form-group mb-3">
                                        <label for="sync_key">Ключ для автоматической синхронизации: </label>
                                        <input class="form-control" readonly type="text" id="sync_key"
                                               value="<?php echo crypt( $this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56' ) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div id="tab-2" class="tab-pane fade <?php if ( $this->config->item( 'sub_account' ) == true ) echo 'show active'?>" role="tabpanel">
                        <div class="panel-body">
                                    <div class="alert alert-info" role="alert">
                                        <h4>Метод отправки данных:</h4>
                                        <p>Данные отправляются методом POST и в данный момент содержат следующее:</p>
                                        <ul>
                                            <li>name – ФИО</li>
                                            <li>email – email</li>
                                            <li>phone – телефон</li>
                                            <li>comments – комментарии</li>
                                            <li>price – цена (если включен любой из режимов расчета цен)</li>
                                            <li>screen – jpeg скриншот проекта в формате base64</li>
                                            <li>pdf – pdf отчет в формате base64</li>
                                            <li>save_data – данные сохранения проекта в формате json. Можно записать в файл .dbs для
                                                последующего открытия в конструкторе как через кнопку “Загрузить” так и через GET параметр
                                                file_url в url страницы с конструктором
                                            </li>
                                            <li>
                                                modules_list - данные о модулях в виде массива объектов:


                                                <textarea  style="width: 100%; min-height: 200px">
                            {
                                 "modules": [ //Список модулей
                                   {
                                     "id": 1, // id модуля
                                     "facade_material_id": 754, //внутренний id материала фасада
                                     "corpus_material_id": 1, //внутренний id материала корпуса
                                     "name": "Нижний модуль с 1 дверцей", //название модуля
                                     "code": "НП-1Д-450-1", //артикул модуля
                                     "sizes": "450x720x600", //размеры модуля
                                                                     "width": 450, //ширина модуля
                                     "height": 720, //высота модуля
                                     "depth": 600, //глубина модуля
                                     "price": "7318.20", //цена модуля
                                     "doors": [ //список дверей модуля
                                       {
                                         "width": 450, //ширина двери
                                         "height": 720, //высота двери
                                         "type": "rtl", //тип открывания - "ltr" - слева направо, "rtl" - справа на лево, "simple_top" - газлифт "double_top" - складной подъемник, "front_top" - паралелльный подъемник
                                         "style": "full",
                                         "material_id": 754, //внутренний id материала фасада
                                         "material_code": "RAL 3002" //материала материала фасада
                                       }
                                     ],
                                     "lockers": [], //список ящиков модуля
                                     "facade_material_code": "RAL 3002", //артикул материала фасада
                                     "corpus_material_code": "", //артикул материала корпуса
                                     "facade_material_category_code": "Нет", //артикул категории материала
                                     "facade_material_category_id": 4, //внутренний id категории материала
                                     "facade_type_id": 11, //внутренний id фрезеровки
                                     "count": 1, //количество
                                     "group": "bottom", //верхний или нижний модуль "top" - верхний, "bottom" - нижний
                                     "is_penal": false //пенал или нет false - не пенал, true - пенал
                                   }
                                 ],
                                 "accessories": [
                                   {
                                     "id": "1", // id
                                     "code": "701/40GPM", //артикул
                                     "name": "Сушка 2-уровневая в базу 400, без рамки, отделка серая", //название
                                     "category": "Сушки для посуды в в верхние шкафы", //категория
                                     "price": "1440", //цена
                                     "images": "https://broskokitchenplanner.com/wp-content/uploads/2019/02/2672.jpg, https://broskokitchenplanner.com/wp-content/uploads/2019/02/2670.jpg", //изображения
                                     "description": "", //описание
                                     "tags": "400mm", //теги
                                     "type": "common",
                                     "default": "",
                                     "count": 1 //количество
                                   }
                                 ],
                                 "tabletop": {
                                   "thickness": 40, //толщина столешницы
                                   "material": {
                                     "code": "Нет", //артикул материала столешницы
                                     "name": "A-401-Cloudy-Mount", // название материала столешницы
                                     "id": 278, //внутренний id материала столешницы
                                     "category": "ADVENTURE" //название категории столешницы
                                   }
                                 },
                                 "handles": {
                                   "code": "", //артикул ручки
                                   "axis_size": "192", //межосевое расстояние ручки
                                   "name": "Рейлинг 1", //название ручки
                                   "count": 4 //количество ручек
                                 },
                                 "project_id": 1582628595388, //уникальный идентификатор проекта
                            }
                        </textarea>
                                                <!--                        <ul>-->
                                                <!--                            <li>name - название модуля</li>-->
                                                <!--                            <li>code - артикул модуля</li>-->
                                                <!--                            <li>count - количество модуля</li>-->
                                                <!--                            <li>sizes - размеры модуля</li>-->
                                                <!--                            <li>price - цена модуля</li>-->
                                                <!--                            <li>facade_material_code - артикул материала фасадов</li>-->
                                                <!--                            <li>corpus_material_code - артикул материала корпуса</li>-->
                                                <!--                        </ul>-->
                                            </li>

                                        </ul>

                                    </div>


                                    <div class="form-group mb-3">
                                        <label for="custom_order_url">Свой контроллер для приема заявок</label>
                                        <input value="<?php echo $settings['custom_order_url'] ?>" type="text" class="form-control mb-3"
                                               id="custom_order_url" placeholder="https://example.ru/controller.php">
                                        <div id="send_test_order" class="btn btn-success btn-sm">Отправить тестовый запрос</div>
                                    </div>
                                    <button type="button" id="save_custom_order_url mt-3"
                                            class="btn btn-primary btn-sm"><?php echo $lang_arr['save'] ?></button>

                            <?php if ( $this->config->item( 'sub_account' ) == true ): ?>
                            <input type="hidden" id="sync_key"
                                   value="<?php echo crypt( $this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56' ) ?>">
                            <?php endif;?>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>



    <script src="/common_assets/libs/exceljs.min.js"></script>



    <script>

        $(document).ready(function () {

			<?php if ($this->config->item( 'sub_account' ) == false): ?>

            $('#parse_data').click(function () {
                try {

                    JSON.parse($('#json_data').val());

                    console.log($('#url_auto').val())

                    $.ajax({
                        type: 'post',
                        url: $('#url_auto').val(),
                        data: {
                            data: $('#json_data').val(),
                            sync_key: $('#sync_key').val()
                        },
                        success: function (msg) {
                            console.log(msg)
                            alert('Цены успешно синхронизированы')
                        }
                    })


                } catch (e) {
                    alert('<?php echo $lang_arr['json_syntax_error']?>')
                }
            });

            $('#parse_xlsx_data').click(function () {
                let files = $('#xlsx_data')[0].files
                if(files.length == 0){
                    alert('Выберите файл');
                    return;
                }

                let file = files[0];

                const wb = new ExcelJS.Workbook();
                const reader = new FileReader()

                reader.readAsArrayBuffer(file)
                reader.onload = () => {
                    const buffer = reader.result;
                    let result = [];

                    wb.xlsx.load(buffer).then(workbook => {
                        console.log(workbook, 'workbook instance')
                        workbook.eachSheet((sheet, id) => {
                            sheet.eachRow((row, rowIndex) => {
                                if(rowIndex != 1){
                                    result.push({
                                        code: row.values[2],
                                        price: parseFloat(row.values[3])
                                    })
                                }
                            })


                            $.ajax({
                                type: 'post',
                                url: $('#url_auto').val(),
                                data: {
                                    data: JSON.stringify(result),
                                    sync_key: $('#sync_key').val()
                                },
                                success: function (msg) {
                                    console.log(msg)
                                    alert('Цены успешно синхронизированы')
                                }
                            })


                        })
                    })
                }

            })

			<?php endif;?>


            $('#save_custom_order_url').click(function () {
                $.ajax({
                    type: 'post',
                    url: "<?php echo site_url( 'sync/save_custom_order_url' ) ?>",
                    data: {
                        custom_order_url: $('#custom_order_url').val(),
                        sync_key: $('#sync_key').val()
                    },
                    success: function (msg) {
                        alert(msg)
                    }
                })
            });

            $('#send_test_order').click(function () {

                if ($('#custom_order_url').val() !== '') {
                    var data = {
                        url: $('#custom_order_url').val(),
                        name: 'ФИО клиента',
                        email: 'client@email.ru',
                        phone: '8980000000',
                        comments: 'Тестовые комментарии к заказу',
                        price: '177 000р.',
                        screen: 'data:image/png;base64,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',
                        save_data: '{"version":"3.5","project_settings":{"is_kitchen_model":0,"door_offset":2,"shelve_offset":10,"corpus_thickness":16,"tabletop_thickness":40,"bottom_modules_height":720,"bottom_as_top_facade_models":true,"bottom_as_top_facade_materials":true,"bottom_as_top_corpus_materials":true,"cokol_as_corpus":true,"cokol_height":120,"models":{"top":1,"bottom":1},"materials":{"top":{"facades":[1,11],"corpus":["17","18"]},"bottom":{"facades":[1,11],"corpus":["17","18"]},"cokol":["17","18"],"tabletop":["22","26"],"walls":["1"],"floor":["28"],"wall_panel":["22","26","27"],"pat":[1,11]},"selected_materials":{"top":{"facades":737,"corpus":1},"bottom":{"facades":737,"corpus":1},"cokol":1,"tabletop":278,"wall_panel":676,"walls":736,"floor":708,"pat":731},"handle":{"orientation":"vertical","lockers_position":"top","selected_model":1,"preferable_size":2,"model":{"id":1,"category":2,"name":"Рейлинг 1","icon":"models/handles/1/icon_1.jpg","model":"models/handles/1/model_1.fbx","size_index":2,"material":{"params":{"color":"#dadada","roughness":"0.3","metalness":"0"},"add_params":{},"type":"Standart"},"sizes":[{"width":"175","height":"14","depth":"35","code":"","axis_size":"128","price":""},{"width":"207","height":"14","depth":"35","code":"","axis_size":"160","price":""},{"width":"239","height":"14","depth":"35","code":"","axis_size":"192","price":""},{"width":"367","height":"14","depth":"35","code":"","axis_size":"320","price":""},{"width":"495","height":"14","depth":"35","code":"","axis_size":"448","price":""},{"width":"527","height":"14","depth":"35","code":"","axis_size":"480","price":""},{"width":"623","height":"14","depth":"35","code":"","axis_size":"576","price":""},{"width":"815","height":"14","depth":"35","code":"","axis_size":"768","price":""},{"width":"911","height":"14","depth":"35","code":"","axis_size":"864","price":""}]},"no_handle":false},"hinges":{"id":0,"name":"С доводчиком","icon":"/common_assets/images/with_dovod.jpg","door_hinges_price":1000,"locker_hinges_price":1000,"dovodchik":true},"wall_panel":{"active":false,"height":550},"is_cokol_active":true,"cornice_active":false,"cornice_available":"1"},"camera":{"position":{"x":52.50005584166115,"y":208.00822921625374,"z":408.4644308685684},"target":{"x":238,"y":64,"z":17}},"objects":[{"cabinet_group":"bottom","cabinet_type":"common","selected_variant":2,"name":"","code":"","price":1620,"comments":"","cabinet":{"width":450,"height":720,"depth":600,"thickness":16,"thickness_back":3,"back_wall_offset":0,"orientation":"right","material":1,"is_penal":false,"type":"common"},"tabletop":{"active":true,"height":40,"offset":{"left":0,"right":0,"front":35,"back":35},"material":278},"cokol":{"height":120,"thickness":16,"material":1,"visible":{"left":false,"right":false,"back":false,"front":true}},"cornice":{"active":true,"force":false,"model":"/common_assets/models/cornice.fbx","corner_model":"/common_assets/models/cornice_corner.fbx","corner_model_45":"/common_assets/models/cornice_corner_45.fbx","radius_model":"/common_assets/models/cornice_radius.fbx","visible":{"left":false,"right":false,"back":false,"front":true,"front_corner_left":false,"front_corner_right":false,"back_corner_left":false,"back_corner_right":false},"material":1},"doors":[{"active":true,"width":"100%","height":"100%","type":"rtl","style":["full","full"],"handle_model":{"id":1,"category":2,"name":"Рейлинг 1","icon":"models/handles/1/icon_1.jpg","model":"models/handles/1/model_1.fbx","size_index":2,"material":{"params":{"color":"#dadada","roughness":"0.3","metalness":"0"},"add_params":{},"type":"Standart"},"sizes":[{"width":"175","height":"14","depth":"35","code":"","axis_size":"128","price":""},{"width":"207","height":"14","depth":"35","code":"","axis_size":"160","price":""},{"width":"239","height":"14","depth":"35","code":"","axis_size":"192","price":""},{"width":"367","height":"14","depth":"35","code":"","axis_size":"320","price":""},{"width":"495","height":"14","depth":"35","code":"","axis_size":"448","price":""},{"width":"527","height":"14","depth":"35","code":"","axis_size":"480","price":""},{"width":"623","height":"14","depth":"35","code":"","axis_size":"576","price":""},{"width":"815","height":"14","depth":"35","code":"","axis_size":"768","price":""},{"width":"911","height":"14","depth":"35","code":"","axis_size":"864","price":""}]},"handle_size_index":2,"handle_orientation":"vertical","handle_position":"top","starting_point_x":"0%","starting_point_y":"0%","direction_x":"right","direction_y":"top","offset_top":0,"offset_bottom":0,"available_opens":["ltr","rtl"],"group":"bottom","pX":0,"pY":12,"pZ":26.5,"rX":0,"rY":0,"rZ":0}],"lockers":[],"shelves":[{"starting_point_y":"50%"}],"decorations":[],"oven":{"active":false,"model":2000000},"hob":{"active":false,"model":3000000},"sink":{"active":false,"model":4000000,"available":true},"variants":[{"width":"300","height":"720","depth":"600","name":"","code":"","default":"0"},{"width":"400","height":"720","depth":"600","name":"","code":"","default":"0"},{"width":"450","height":"720","depth":"600","name":"","code":"","default":"1"},{"width":"500","height":"720","depth":"600","name":"","code":"","default":"0"},{"width":"600","height":"720","depth":"600","name":"","code":"","default":"0"}],"id":1,"wall_panel":true,"change_opens":true,"position":{"x":83.4,"y":0,"z":30},"rotation":{"_x":0,"_y":0,"_z":0,"_order":"XYZ"},"type":"Cabinet"}],"room":{"width":3200,"height":2500,"depth":4000,"wall_material":736,"floor_material":708}}',
                        modules_list: [
                            {id: 234, count: 2}, {id: 1212, count: 4}, {id: 241, count: 1}
                        ]
                    };

                    $.ajax({
                        url: '<?php echo $this->config->item('const_path') . "config/index.php/clients_orders/test_order"?>',
                        // url: 'http://91.232.135.213:8091/PublicAPI/REST/EleWise.ELMA.SDK.Web/TestWeb/Test',
                        type: 'POST',
                        data: data,
                        cache: false,
                        success: function (msg) {
                            console.log(msg);
                            alert('Запрос успешно отправлен на указанный url. Результат и ответ на запрос можно посмотреть в консоли браузера')
                        }
                    });
                } else {
                    alert('Не указан url')
                }


            })


        });


    </script>
</div>