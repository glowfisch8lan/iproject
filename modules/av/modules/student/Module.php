<?php

namespace app\modules\av\modules\student;

/**
 * staff module definition class
 */
class Module
{

    /**
     * {@inheritdoc}
     */
    public $modelNamespace = 'app\modules\av\modules\student';
    public $plugins = [
        [
            'id' => 'academicPerformance',
            'name' => 'Успеваемость',
            'module' => [
                'id' => 'student',
                'name' => 'Студент',
            ],
            'category' => 'plugins',
            'controller' => 'academicPerformance',
            'visible' => true,
        ],
        [
            'id' => 'journal',
            'name' => 'Электронный журнал',
            'module' => [
                'id' => 'student',
                'name' => 'Студент',
            ],
            'category' => 'plugins',
            'controller' => 'journal',
            'visible' => false,
        ]
    ];

    public function __construct() {
        /**
         * Принудительно открываем доступ ко всем плагинам
         */
//        foreach($this->plugins as $key => $value)
//        {
//            $this->plugins[$key]['visible'] = true;
//        }
    }
}
