<p>Добавление материалов:</p>
<p>Тип запроса: POST<br />URL: <?php echo $this->config->item( 'const_path' ) ?>config/index.php/api/materials_add</p>
<p>Тело запроса:<br />data - данные с материалами в формате JSON<br />sync_key - Ключ для автоматической синхронизации <?php echo crypt( $this->config->item( 'login' ), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56' ) ?></p>
<p><br />Формат данных:</p>
<p>/**<br />* name - Название, string<br />* code - Артикул, string<br />* category - ID категории, integer<br />* params<br />* color - HEX код цвета, string<br />* map - URL изображения, string<br />* roughness - Шероховатость, float<br />* metalness - Металлик, float<br />* transparent - Прозрачность (работает только для .png текстур с прозрачными частями), bool<br />* add_params<br />* real_width - Сколько в текстуре реальных миллиметров по ширине, integer<br />* real_height - Сколько в текстуре реальных миллиметров по высоте, integer<br />* stretch_width - Растягивать текстуру по ширине, bool<br />* stretch_height - Растягивать текстуру по высоте, bool<br />* wrapping - Тип наложения текстуры, string (mirror, repeat)<br />*/</p>
<p><br />[<br />&nbsp; {<br />&nbsp; &nbsp; "name": "2516-LArpa",<br />&nbsp; &nbsp; "code": "2516",<br />&nbsp; &nbsp; "category": 16,<br />&nbsp; &nbsp; "params": {<br />&nbsp; &nbsp; &nbsp; "color": "#ffffff",<br />&nbsp; &nbsp; &nbsp; "map": "https://test.com/images/image.jpg",<br />&nbsp; &nbsp; &nbsp; "roughness": 0.8,<br />&nbsp; &nbsp; &nbsp; "metalness": 0,<br />&nbsp; &nbsp; &nbsp; "transparent": false<br />&nbsp; &nbsp; },<br />&nbsp; &nbsp; "add_params": {<br />&nbsp; &nbsp; &nbsp; "real_width": 256,<br />&nbsp; &nbsp; &nbsp; "real_height": 256,<br />&nbsp; &nbsp; &nbsp; "stretch_width": false,<br />&nbsp; &nbsp; &nbsp; "stretch_height": false,<br />&nbsp; &nbsp; &nbsp; "wrapping": "mirror"<br />&nbsp; &nbsp; }<br />&nbsp; },<br />&nbsp; ...<br />]</p>
<p>Ответ:</p>
<p>Успех:&nbsp;</p>
<p>{<br />&nbsp;"result":"success";<br />&nbsp;"failed":null<br />}</p>
<p><br />Ошбика:&nbsp;</p>
<p>{<br />&nbsp;"result":"error";<br />&nbsp;"failed":<br />&nbsp;[&nbsp;<br />&nbsp; {<br />&nbsp; &nbsp;"name": "2516-LArpa",<br />&nbsp; &nbsp;"code": "2516",<br />&nbsp; &nbsp;"category": 16,<br />&nbsp; &nbsp;"params": {<br />&nbsp; &nbsp; &nbsp;"color": "#ffffff",<br />&nbsp; &nbsp; &nbsp;"map": "https://test.com/images/image.jpg",<br />&nbsp; &nbsp; &nbsp;"roughness": 0.8,<br />&nbsp; &nbsp; &nbsp;"metalness": 0,<br />&nbsp; &nbsp; &nbsp;"transparent": false<br />&nbsp; &nbsp;},<br />&nbsp; &nbsp;"add_params": {<br />&nbsp; &nbsp; &nbsp;"real_width": 256,<br />&nbsp; &nbsp; &nbsp;"real_height": 256,<br />&nbsp; &nbsp; &nbsp;"stretch_width": false,<br />&nbsp; &nbsp; &nbsp;"stretch_height": false,<br />&nbsp; &nbsp; &nbsp;"wrapping": "mirror"<br />&nbsp; &nbsp;}<br />&nbsp; &nbsp; },<br />&nbsp; &nbsp; ...<br />&nbsp;]<br />}</p>
<p>-------------------------------------------------------------------</p>
<p>Получение информации о категориях:</p>
<p>Тип запроса: GET</p>
<p>URL: <?php echo $this->config->item( 'const_path' ) ?>config/index.php/api/get_materials_categories</p>
<p>Параметры:<br />sync_key - Ключ для автоматической синхронизации <?php echo crypt( $this->config->item( 'login' ), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56' ) ?></p>
<p><br />Ответ:</p>
<p>Формат данных:</p>
<p>/**<br />* id - ID категории, integer<br />* name - Название категории, string<br />* parent - ID родительской категории, integer<br />* active - Флаг активности категории, integer (1,0)<br />*/</p>
<p>[<br />&nbsp; {<br />&nbsp; &nbsp; "id": 1,<br />&nbsp; &nbsp; "name": "Название категории",<br />&nbsp; &nbsp; "parent": 0,<br />&nbsp; &nbsp; "active": 1<br />&nbsp; },<br />&nbsp; ...<br />]</p>
<p><br />---------------------------------------------------------------------</p>
<p>Добавление категорий</p>
<p>Получение информации о категориях:</p>
<p>Тип запроса: POST</p>
<p>URL: <?php echo $this->config->item( 'const_path' ) ?>config/index.php/api/add_materials_categories</p>
<p>Тело запроса:<br />data - данные с материалами в формате JSON<br />sync_key - Ключ для автоматической синхронизации <?php echo crypt( $this->config->item( 'login' ), 'RTKtGEh%d$f4`r6w1*zCsdg53sq234qs56' ) ?></p>
<p>Формат данных:</p>
<p>/**<br />* name - Название категории, string<br />* parent - ID родительской категории, integer<br />* active - Флаг активности категории, integer (1,0)<br />*/</p>
<p>[<br />&nbsp; {<br />&nbsp; &nbsp; "name": "Название категории",<br />&nbsp; &nbsp; "parent": 0,<br />&nbsp; &nbsp; "active": 1<br />&nbsp; },<br />&nbsp; ...<br />]</p>
<p>&nbsp;</p>
<p>Ответ:</p>
<p>Формат данных:</p>
<p>/**<br />* name - Название категории, string<br />* ID - ID Категории<br />*/</p>
<p><br />Массив в формате JSON</p>
<p>[<br />&nbsp; {<br />&nbsp; &nbsp; "name": "Название категории",<br />&nbsp; &nbsp; "id": 100<br />&nbsp; }<br />&nbsp; ...<br />]</p>