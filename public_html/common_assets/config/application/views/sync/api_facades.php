<div class="panel-body ">
    <div class="row">
        <div class="col flex-fixed-width-item">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Содержание
                </div>
                <div class="panel-body">
                    <ul class="contents">
                        <li>
                            <a href="#tab-api_facades_add">Добавление фрезеровки</a>
                            <ul>
                                <li><a href="#">Описание</a></li>
                                <li><a href="#">Формат данных</a></li>
                                <li><a href="#api_facades_add_example">Пример данных</a></li>
                                <li><a href="#">Ответ</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#tab-api_facades_categories_get">Получение категорий</a>
                            <ul>
                                <li><a href="#">Описание</a></li>
                                <li><a href="#">Пример запроса</a></li>
                                <li><a href="#">Ответ</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Добавление категории</a>
                            <ul>
                                <li><a href="#">Описание</a></li>
                                <li><a href="#">Формат данных</a></li>
                                <li><a href="#">Пример данных</a></li>
                                <li><a href="#">Ответ</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>




        </div>
        <div class="col">
            <div class="tab-content">
            <div id="tab-api_facades_add" class="tab-pane active">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Базовое описание
                    </div>
                    <div class="panel-body">
                        <p>Данные передаются по протоколу http методом POST:</p>
                        <p>URL: <?php echo $this->config->item('const_path') ?>config/index.php/api/add_items/facades</p>
                        <p>Тело запроса:<br/>data - данные с материалами в формате JSON<br/>sync_key - Ключ для автоматической синхронизации <?php echo crypt($this->config->item('username'), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56') ?></p>
                        <p>Данные состоят из массива объектов</p>
                    </div>

                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Формат данных
                    </div>
                    <div class="panel-body">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Формат данных одного объекта (одной фрезеровки)
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Параметр</th>
                                        <th>Тип</th>
                                        <th>Описание</th>
                                        <th>Комментарии</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr style="font-style: italic; color: #000">
                                        <td>name</td>
                                        <td>string</td>
                                        <td>название фрезеровки</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>code</td>
                                        <td>string</td>
                                        <td>артикул</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>order</td>
                                        <td>number</td>
                                        <td>порядок отображения</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr style="font-style: italic; color: #000">
                                        <td>category</td>
                                        <td>number</td>
                                        <td>id категории</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr style="font-style: italic; color: #000">
                                        <td>materials</td>
                                        <td>array</td>
                                        <td>массив доступных id категорий материалов</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>icon</td>
                                        <td>string</td>
                                        <td>ссылка на иконку фрезеровки</td>
                                        <td>максимальное разрешение 156 х 250</td>
                                    </tr>
                                    <tr>
                                        <td>handle</td>
                                        <td>number</td>
                                        <td>устанапливать ли ручку на фрезеровку</td>
                                        <td>1 или 0</td>
                                    </tr>
                                    <tr>
                                        <td>handle_offset</td>
                                        <td>number</td>
                                        <td>отступ ручки от края фасада, мм</td>
                                        <td>значение по умолчанию 30</td>
                                    </tr>
                                    <tr>
                                        <td>double_offset</td>
                                        <td>number</td>
                                        <td>расстояние, на которое фасад типа "двойка" выступает за корпус, мм</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>types</td>
                                        <td>object</td>
                                        <td>объект с доступными типами фасадов для фрезеровки</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>types_double</td>
                                        <td>object</td>
                                        <td>объект с доступными типами фасадов типа "двойка" для фрезеровки</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>types_triple</td>
                                        <td>object</td>
                                        <td>объект с доступными типами фасадов типа "тройка" для фрезеровки</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>additional_materials</td>
                                        <td>object</td>
                                        <td>объект с описанием дополнительных материалов, используемых в фрезеровке</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>triple_decor_model</td>
                                        <td>object</td>
                                        <td>объект с описанием модели для фасадов типа "тройка"</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>compatibility_types</td>
                                        <td>object</td>
                                        <td>объект с типами совместимости со старыми фасадами</td>
                                        <td>подробное описание ниже в данном руководстве</td>
                                    </tr>
                                    <tr>
                                        <td>prices</td>
                                        <td>object</td>
                                        <td>объект с ценами материалов для всех типов фасадов</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p style="color: #E03E2D"><i>Внимание! Обязательными являются парметры name, materials, category и types</i></p>
                                <p style="color: #E03E2D"><i>
                                        Все остальные параметры присылать не нужно, если они не используются в данной модели фрезеровки.
                                        Например, если в модели нет "двоек" и "троек" то параметры types_double, types_triple, triple_decor_model присылать не нужно.
                                    </i></p>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Формат объекта types
                            </div>
                            <div class="panel-body">
                                <p>Объект types состоит из объектов с числовыми ключами. Каждому ключу соответствует свой тип фасада.</p>
                                <table class="table table-hover table-bordered">
                                    <tbody>
                                    <tr>
                                        <td>Параметр</td>
                                        <td>Тип</td>
                                        <td>Описание</td>
                                        <td>Комментарии</td>
                                    </tr>
                                    <tr>
                                        <td>name</td>
                                        <td>string</td>
                                        <td>название типа фасада</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>icon</td>
                                        <td>string</td>
                                        <td>ссылка на иконку типа фасада</td>
                                        <td>максимальное разрешение 156 х 250</td>
                                    </tr>
                                    <tr>
                                        <td>items</td>
                                        <td>array</td>
                                        <td>массив с моделями и размерами</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>radius</td>
                                        <td>number</td>
                                        <td>является ли данный тип фасада радиусным</td>
                                        <td>1 или 0</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <p>Массив items состоит из объектов</p>
                                <p style="color: #E03E2D"><i>Внимание! Массив items должен быть отсортирован по высоте и по ширине (именно в таком порядке) для правильной работы модели фрезеровки.</i></p>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Формат объекта из массива items
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Параметр</th>
                                                <th>Тип</th>
                                                <th>Описание</th>
                                                <th>Комментарии</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>min_width</td>
                                                <td>number</td>
                                                <td>минимальная ширина фасада, от которой будет использована модель, мм</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>min_height</td>
                                                <td>number</td>
                                                <td>минимальная высота фасада, от которой будет использована модель, мм</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>model</td>
                                                <td>string</td>
                                                <td>ссылка на модель в формате fbx для указанных размеров в фасада&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>group</td>
                                                <td>string</td>
                                                <td>группа модулей, на которую возможна установка модели</td>
                                                <td>
                                                    <p>возможные значения:</p>
                                                    <p>all - верхние и нижние модули</p>
                                                    <p>top - только верхние модули</p>
                                                    <p>bottom - только нижние модули</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <p>Форматы объектов types_double и types_triple идентичны формату объекта types</p>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Формат объекта triple_decor_model
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Параметр</th>
                                        <th>Тип</th>
                                        <th>Описание</th>
                                        <th>Комментарии</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>height</td>
                                        <td>number</td>
                                        <td>высота модели, мм</td>
                                        <td>по bounding box</td>
                                    </tr>
                                    <tr>
                                        <td>thickness</td>
                                        <td>number</td>
                                        <td>толщина модели, мм</td>
                                        <td>по bounding box</td>
                                    </tr>
                                    <tr>
                                        <td>offset</td>
                                        <td>number</td>
                                        <td>отступ моделей всех типов "тройки" от верхнего края модуля, мм</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>model</td>
                                        <td>string</td>
                                        <td>ссылка на модель в формате fbx для указанных размеров в фасада&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Формат объекта additional_materials
                            </div>
                            <div class="panel-body">
                                <p>Объект additional_materials состоит из объектов с числовыми ключами. Каждому ключу соответствует свой декор или категории декоров.</p>
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Параметр</th>
                                        <th>Тип</th>
                                        <th>Описание</th>
                                        <th>Комментарии</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>name</td>
                                        <td>string</td>
                                        <td>название материала</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>key</td>
                                        <td>string</td>
                                        <td>уникальный ключ материала</td>
                                        <td>должен соответствовать ключу, указанному в fbx модели</td>
                                    </tr>
                                    <tr>
                                        <td>fixed</td>
                                        <td>number</td>
                                        <td>можно ли выбирать декор</td>
                                        <td>
                                            <p>0 - можно выбирать декор</p>
                                            <p>1 - нельзя выбирать декор</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>materials</td>
                                        <td>array</td>
                                        <td>массив с id категорий декоров</td>
                                        <td>
                                            <p>должен быть указан даже если декор нельзя выбирать</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>selected</td>
                                        <td>number</td>
                                        <td>id выбранного декора</td>
                                        <td>
                                            <p>если декор можно выбирать - id декора по умолчанию</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Объект compatibility_types
                            </div>
                            <div class="panel-body">
                                <p>Данный объект описывает какой тип фасада должен использовать конструктор при замене фрезеровки на модулях. Значения должны соответствовать ключам в объекте types</p>
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Параметр</th>
                                        <th>Тип</th>
                                        <th>Описание</th>
                                        <th>Комментарии</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <td>full</td>
                                        <td>number</td>
                                        <td>ключ для глухого фасада</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>window</td>
                                        <td>number</td>
                                        <td>ключ для витрины</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>frame</td>
                                        <td>number</td>
                                        <td>ключ для решетки</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>radius</td>
                                        <td>number</td>
                                        <td>ключ для глухого радиусного фасада</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>radius_window</td>
                                        <td>number</td>
                                        <td>ключ для радиусной витрины</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Формат объекта prices
                            </div>
                            <div class="panel-body">
                                <p>Объект prices состоит из объектов с цифровыми ключами соответствующими объекту types. Каждый объект в объекте prices также состоит из объектов, ключами в котором являются id доступных категорий декоров, в этом объекте 2 параметра:</p>
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Параметр</th>
                                        <th>Тип</th>
                                        <th>Описание</th>
                                        <th>Комментарии</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <td>parent</td>
                                        <td>number</td>
                                        <td>id родительской категории</td>
                                        <td>0 если родительской категории нет</td>
                                    </tr>
                                    <tr>
                                        <td>price</td>
                                        <td>number</td>
                                        <td>цена за кв.м фасада</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>




                    </div>
                </div>

                <div id="api_facades_add_example" class="panel panel-default">
                    <div class="panel-heading">
                        Пример данных
                    </div>
                    <div class="panel-body">
                        <div id="json"></div>
                    </div>
                </div>

            </div>

            <div id="tab-api_facades_categories_get" class="tab-pane">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Базовое описание
                    </div>
                    <div class="panel-body">
                        Категории
                    </div>

                </div>
            </div>
            </div>
        </div>



    </div>


</div>

<style>
    .contents{
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .contents ul{
        list-style: none;
        padding-left: 15px;
        margin-bottom: 8px;
    }

    .contents ul li{
        list-style: none;
        margin: 3px;
    }
    .flex-fixed-width-item {
        flex: 0 0 250px;
    }


</style>

<link rel="stylesheet" href="/common_assets/libs/json/viewer/jsonTree.css">
<script src="/common_assets/libs/json/viewer/jsonTree.js"></script>

<script>
    var wrapper = document.getElementById("json");
    var dataStr = '[{"order":100000,"name":"Сицилия (с патиной)","code":"","compatibility_types":{"full":0,"window":1,"frame":3,"radius":6,"radius_window":7},"category":"12","materials":["34"],"prices":{"0":{"34":{"price":0,"parent":0}},"1":{"34":{"price":0,"parent":0}},"2":{"34":{"price":0,"parent":0}},"3":{"34":{"price":0,"parent":0}},"4":{"34":{"price":0,"parent":0}},"5":{"34":{"price":0,"parent":0}},"6":{"34":{"price":0,"parent":0}},"7":{"34":{"price":0,"parent":0}}},"icon":"http://someurl/some_folder//27/icon_sicilia_facade.jpg","handle":1,"handle_offset":30,"types":{"0":{"name":"Глухой","icon":"http://someurl/some_folder//27/icon_sicilia_full.jpg","items":[{"min_width":0,"min_height":0,"model":"http://someurl/some_folder//27/1_sicilia_full_w137_h137.fbx","group":"all"},{"min_width":"245","min_height":"245","model":"http://someurl/some_folder//27/2_sicilia_full_w245_h245.fbx","group":"all"}]},"1":{"name":"Витрина","icon":"http://someurl/some_folder//27/icon_sicilia_window.jpg","items":[{"min_width":"245","min_height":"245","model":"http://someurl/some_folder//27/3_sicilia_window_w245_h245.fbx","group":"all"}]},"2":{"name":"Полувитрина","icon":"http://someurl/some_folder//27/icon_sicilia_halfwindow.jpg","items":[{"min_width":"245","min_height":"500","model":"http://someurl/some_folder//27/4_sicilia_halfwindow_w245_h560.fbx","group":"all"}]},"3":{"name":"Решетка","icon":"http://someurl/some_folder//27/icon_sicilia_frame.jpg","items":[{"min_width":"245","min_height":"245","model":"http://someurl/some_folder//27/5_sicilia_frame_w245_h245.fbx","group":"all"},{"min_width":"245","min_height":"560","model":"http://someurl/some_folder//27/6_sicilia_frame_w245_h560.fbx","group":"all"},{"min_width":"245","min_height":"916","model":"http://someurl/some_folder//27/7_sicilia_frame_w245_h916.fbx","group":"all"},{"min_width":"245","min_height":"1116","model":"http://someurl/some_folder//27/8_sicilia_frame_w245_h1116.fbx","group":"all"}]},"4":{"name":"1-Решетка","icon":"http://someurl/some_folder//27/icon_sicilia_frame1.jpg","items":[{"min_width":"245","min_height":"703","model":"http://someurl/some_folder//27/9_sicilia_frame1_w245_h703.fbx","group":"all"}]},"5":{"name":"2-Решетка","icon":"http://someurl/some_folder//27/icon_sicilia_frame2.jpg","items":[{"min_width":"245","min_height":"446","model":"http://someurl/some_folder//27/10_sicilia_frame2_w245_h446.fbx","group":"all"},{"min_width":"446","min_height":"446","model":"http://someurl/some_folder//27/11_sicilia_frame2_w446_h446.fbx","group":"all"}]},"6":{"name":"Радиус глухой","icon":"http://someurl/some_folder//27/icon_sicilia_radius_full.jpg","items":[{"min_width":0,"min_height":0,"model":"http://someurl/some_folder//27/12_sicilia_radius_full_w0_h0.fbx","group":"all"}],"radius":1},"7":{"name":"Радиус витрина","icon":"http://someurl/some_folder//27/icon_sicilia_radius_window.jpg","items":[{"min_width":0,"min_height":0,"model":"http://someurl/some_folder//27/13_sicilia_radius_window_w0_h0.fbx","group":"all"}],"radius":1}},"types_double":{"0":{"name":"Глухой","icon":"http://someurl/some_folder//27/icon_sicilia_double_full.jpg","items":[{"min_width":"296","min_height":"700","model":"http://someurl/some_folder//27/14_sicilia_double_full_w296_h700.fbx"}]},"1":{"name":"Витрина","icon":"http://someurl/some_folder//27/icon_sicilia_double_window.jpg","items":[{"min_width":"296","min_height":"700","model":"http://someurl/some_folder//27/15_sicilia_double_window_w296_h700.fbx"}]},"2":{"name":"Полувитрина","icon":"http://someurl/some_folder//27/icon_sicilia_double_halfwindow.jpg","items":[{"min_width":"296","min_height":"700","model":"http://someurl/some_folder//27/16_sicilia_double_halfwindow_w296_h700.fbx"},{"min_width":"296","min_height":"916","model":"http://someurl/some_folder//27/17_sicilia_double_halfwindow_w296_h916.fbx"},{"min_width":"296","min_height":"956","model":"http://someurl/some_folder//27/18_sicilia_double_halfwindow_w296_h956.fbx"}]},"3":{"name":"Глухой В","icon":"http://someurl/some_folder//27/icon_sicilia_doublev_full.jpg","items":[{"min_width":"296","min_height":"700","model":"http://someurl/some_folder//27/19_sicilia_double-v_full_w296_h700.fbx"}]},"4":{"name":"Витрина В","icon":"http://someurl/some_folder//27/icon_sicilia_doublev_window.jpg","items":[{"min_width":"296","min_height":"700","model":"http://someurl/some_folder//27/20_sicilia_double-v_window_w296_h700.fbx"}]},"5":{"name":"Полувитрина В","icon":"http://someurl/some_folder//27/icon_sicilia_doublev_halfwindow.jpg","items":[{"min_width":"296","min_height":"700","model":"http://someurl/some_folder//27/21_sicilia_double-v_halfwindow_w296_h700.fbx"},{"min_width":"296","min_height":"916","model":"http://someurl/some_folder//27/22_sicilia_double-v_halfwindow_w296_h916.fbx"},{"min_width":"296","min_height":"956","model":"http://someurl/some_folder//27/23_sicilia_double-v_halfwindow_w296_h956.fbx"}]}},"types_triple":{"0":{"name":"Глухой","icon":"http://someurl/some_folder//27/icon_sicilia_triple_full.jpg","items":[{"min_width":"296","min_height":"562","model":"http://someurl/some_folder//27/24_sicilia_triple_full_w296_h562.fbx"}]},"1":{"name":"Витрина","icon":"http://someurl/some_folder//27/icon_sicilia_triple_window.jpg","items":[{"min_width":"296","min_height":"562","model":"http://someurl/some_folder//27/25_sicilia_triple_window_w296_h562.fbx"}]},"2":{"name":"Полувитрина","icon":"http://someurl/some_folder//27/icon_sicilia_triple_halfwindow.jpg","items":[{"min_width":"296","min_height":"562","model":"http://someurl/some_folder//27/26_sicilia_triple_halfwindow_w296_h562.fbx"},{"min_width":"296","min_height":"800","model":"http://someurl/some_folder//27/27_sicilia_triple_halfwindow_w296_h800.fbx"},{"min_width":"296","min_height":"850","model":"http://someurl/some_folder//27/28_sicilia_triple_halfwindow_w296_h850.fbx"}]}},"double_offset":"240","triple_decor_model":{"model":"http://someurl/some_folder//27/999_triple_decor.fbx","model_file":{},"height":"140","thickness":"18","offset":"86","current_model":"http://someurl/some_folder//27/999_triple_decor.fbx"},"additional_materials":{"0":{"key":"pat","name":"Цвет патины","fixed":0,"materials":["44"],"selected":"1032"}}},{"order":100000,"name":"Гладкая","compatibility_types":{"full":0,"window":1,"frame":2,"radius":3,"radius_window":4},"category":"2","materials":["1","11"],"prices":{"0":{"1":{"price":0,"parent":0},"2":{"parent":"1","price":0},"3":{"parent":"1","price":0},"4":{"parent":"1","price":0},"5":{"parent":"1","price":0},"6":{"parent":"1","price":0},"7":{"parent":"1","price":0},"8":{"parent":"1","price":0},"9":{"parent":"1","price":0},"10":{"parent":"1","price":0},"11":{"price":0,"parent":0},"19":{"parent":"11","price":0},"20":{"parent":"11","price":0},"21":{"parent":"11","price":0}},"1":{"1":{"price":0,"parent":0},"2":{"parent":"1","price":0},"3":{"parent":"1","price":0},"4":{"parent":"1","price":0},"5":{"parent":"1","price":0},"6":{"parent":"1","price":0},"7":{"parent":"1","price":0},"8":{"parent":"1","price":0},"9":{"parent":"1","price":0},"10":{"parent":"1","price":0},"11":{"price":0,"parent":0},"19":{"parent":"11","price":0},"20":{"parent":"11","price":0},"21":{"parent":"11","price":0}},"2":{"1":{"price":0,"parent":0},"2":{"parent":"1","price":0},"3":{"parent":"1","price":0},"4":{"parent":"1","price":0},"5":{"parent":"1","price":0},"6":{"parent":"1","price":0},"7":{"parent":"1","price":0},"8":{"parent":"1","price":0},"9":{"parent":"1","price":0},"10":{"parent":"1","price":0},"11":{"price":0,"parent":0},"19":{"parent":"11","price":0},"20":{"parent":"11","price":0},"21":{"parent":"11","price":0}},"3":{"1":{"price":0,"parent":0},"2":{"parent":"1","price":0},"3":{"parent":"1","price":0},"4":{"parent":"1","price":0},"5":{"parent":"1","price":0},"6":{"parent":"1","price":0},"7":{"parent":"1","price":0},"8":{"parent":"1","price":0},"9":{"parent":"1","price":0},"10":{"parent":"1","price":0},"11":{"price":0,"parent":0},"19":{"parent":"11","price":0},"20":{"parent":"11","price":0},"21":{"parent":"11","price":0}},"4":{"1":{"price":0,"parent":0},"2":{"parent":"1","price":0},"3":{"parent":"1","price":0},"4":{"parent":"1","price":0},"5":{"parent":"1","price":0},"6":{"parent":"1","price":0},"7":{"parent":"1","price":0},"8":{"parent":"1","price":0},"9":{"parent":"1","price":0},"10":{"parent":"1","price":0},"11":{"price":0,"parent":0},"19":{"parent":"11","price":0},"20":{"parent":"11","price":0},"21":{"parent":"11","price":0}}},"icon":"http://someurl/some_folder//1/MDF_modern_gladkiy.jpg","handle":1,"handle_offset":30,"types":{"0":{"name":"Глухой","icon":"","items":[{"min_width":0,"min_height":0,"model":"http://someurl/some_folder//1/mdf_full.fbx","group":"all"}]},"1":{"name":"Витрина","icon":"","items":[{"min_width":0,"min_height":0,"model":"http://someurl/some_folder//1/mdf_full.fbx","group":"all"},{"min_width":176,"min_height":176,"model":"http://someurl/some_folder//1/mdf_frame.fbx","group":"all"}]},"2":{"name":"Решетка","icon":"","items":[{"min_width":0,"min_height":0,"model":"http://someurl/some_folder//1/mdf_full.fbx","group":"all"},{"min_width":256,"min_height":296,"model":"http://someurl/some_folder//3/rect_frame_w396_h356.fbx","group":"all"},{"min_width":256,"min_height":716,"model":"http://someurl/some_folder//3/rect_frame_w296_h716.fbx","group":"all"},{"min_width":256,"min_height":956,"model":"http://someurl/some_folder//3/rect_frame_w296_h956.fbx","group":"all"}]},"3":{"name":"Радиус глухой","icon":"","items":[{"min_width":0,"min_height":0,"model":"http://someurl/some_folder//1/mdf_full_radius.fbx","group":"all"}],"radius":1},"4":{"name":"Радиус витрина","icon":"","items":[{"min_width":0,"min_height":0,"model":"http://someurl/some_folder//1/mdf_window_radius.fbx","group":"all"}],"radius":1}},"types_double":{},"types_triple":{},"double_offset":0,"triple_decor_model":{"model":"","model_file":"","current_model":"","thickness":0,"height":0,"offset":0},"additional_materials":{}}]';
    try {
        var data = JSON.parse(dataStr);
    } catch (e) {
    }
    var tree = jsonTree.create(data, wrapper);
    tree.expand(function (node) {
        return node.childNodes.length < 2 || node.label === 'phoneNumbers';
    });
</script>
