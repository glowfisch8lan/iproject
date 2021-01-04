<p align="center">
    <a href="https://github.com/glowfisch8lan" target="_blank">
        <img src="https://avatars3.githubusercontent.com/u/76803288" height="100px">
    </a>
    <h1 align="center">iDapp - Internal Data Application</h1>
    <br>
</p>

<h5>Добавление модулей</h5>
В Module.php определить переменные:

```php
    public $controllerNamespace;
    public $name; // Название модуля
    public $defaultController; 
    public $modelNamespace;
    public $link; // http(s)://website.local/
    public $icon; // Иконка FontAwesome для отображения в меню
    public $visible; // Имя разрешения, которое будет определять общий доступ к модулю
    /*
    * Роуты - для вывода в основном SideBar меню.
    */
    public $routes = [
        [   'route' => '/system/users', //основной роут к контроллеру;
            'name' => 'Пользователи', //имя ссылки;
            'access' => 'viewUsers', //имя разрешения, наличие которого требуется для отображения пункта меню
            'description' => 'Доступ к подразделу Пользователи', //описание разрешения
            'visible' => true //показывать ли в меню или нет
        ]
    ];

    private $excludedRules = [
        ['route' => '/system/default', 'name' => 'Главная страница', 'module' => 'system'] //роут, который не учитывать, при построении меню;
    ];
```

<h5>GridHelper</h5>
В Module.php определить переменные:

```php
   public static function initWidget($data = []){}
   
   $data['dataProvider'] - передается провайдер данных, по-умолчанию: null;
   
   $data['searchModel']  - передается Модель Поиска, по-умолчанию: null;
   
   $data['ActionColumn'] - кастомный класс "ActionColumn", 
   по-умолчанию: app\modules\system\components\gridviewer\CustomActionColumns;
   => переопределена функция renderFilterCellContent() для вывода кнопки добавить в строку Фильтрации, для лучшего UI;
   
   $data['ActionColumnHeader'] - кастомный заголовок "ActionColumn", соответствует полю "header":
   
   $data['ActionColumnButtons'] - кастомный кнопки "ActionColumn", соответствует полю "buttons";
   
   $data['buttonsOptions']['template'] - кастомный шаблон расположения кнопок "ActionColumn", соответствует полю "template";
   
   $data['buttonsOptions']['headerWidth'] - ширина header "ActionColumn", по-умолчанию: 150; 
```






    /*
     * Перестройка индексного массива к  массиву вида [[ $key, $value ]];
     */
    public static function indexMap($array,$key){

        $dataArray = [];var_dump($key);
        foreach($array as $value) {
            $dataArray[] = [(int) $key, (int) $value];
        }
        return $dataArray;
    }
