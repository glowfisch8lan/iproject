<p align="center">
    <a href="https://github.com/glowfisch8lan" target="_blank">
        <img src="https://avatars3.githubusercontent.com/u/76803288" height="100px">
    </a>
    <h1 align="center">iDapp - Internal Data Application</h1>
    <br>
</p>
<h4>TODO:</h4>
<p>
<h5>Yii2:</h5>
<ul style="unlysted">
<li>
Обновить версию до 2.0.38
</li>
<li>
Автозагрузка модулей в config/web.php
</li>
<li>Настроить gii для создания шаблонов <strong>контроллера, моделей, CRUD</strong> из своего контекста</li>
</ul>
<h5>system:</h5>
<ul style="unlysted">
<li>
update-manager: разработать;
</li>
<li>
Поведение file-upload
</li>
</ul>
</p>

<h2>Добавление модулей</h2>
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

<h2>GridHelper</h2>

```php
public static function initWidget($data = []){}
   
$data['dataProvider'] - передается провайдер данных, по-умолчанию: null;
   
$data['searchModel']  - передается Модель Поиска, по-умолчанию: null;
   
$data['ActionColumn'] - кастомный класс "ActionColumn", 
   по-умолчанию: app\modules\system\components\gridviewer\CustomActionColumns;
   => переопределена функция renderFilterCellContent() для вывода кнопки добавить в строку Фильтрации, для лучшего UI;
   
$data['ActionColumnHeader'] - кастомный заголовок "ActionColumn", соответствует полю "header", чтобы выключить заголовок, задайте невидимый символ &nbsp;
   
$data['ActionColumnButtons'] = [] - кастомный кнопки "ActionColumn", соответствует полю "buttons";
   
$data['buttonsOptions']['template'] - кастомный шаблон расположения кнопок "ActionColumn", соответствует полю "template";
   
$data['buttonsOptions']['headerWidth'] - ширина header "ActionColumn", по-умолчанию: 150; 

$data['pagination'] = [
    'forcePageParam' => false,
    'pageSizeParam' => false,
    'pageSize' => 10
];

````

<h5>Система : Модули</h5>
1. При регистрации модуля обновляются права доступа у группы Администраторов.
TODO
1. Меню: добавить сортировку модулей;
 
<h5>Обратная связь: Заявки</h5>
TODO:
1. Возможность выбора отправителя по-умолчанию;
=> настройки хранятся в таблице модуля _settings



<h5>Хелперы: ArrayHelper</h5>
1. Перестройка индексного массива 
```php
public static function indexMap($array,$key){}

$array - входной массив,
$key - значение, которое будет использовано в качестве ключа.
````
```php
[
    0 => 'value',
    1 => 'value',
    ...
    index => value,
]
````
 
 к  массиву вида [[ key, value ]].
 Например,[['user_id', 'group_id']] для группового SQL-запроса метода addMembers() класса Groups;
 
 ```php
[ 
     ['user_id', 'group_id']
     ...
     ['key' => 'value1']
    
 ]
 ````

