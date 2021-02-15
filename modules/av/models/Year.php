<?php


namespace app\modules\av\models;

use Yii;

trait Year
{

    public static function getSemesterNow()
    {
        $arr = [
            [
                'semester' =>
                    [
                        [
                    'name' => '1 семестр',
                    'startDate' => '01.01',
                    'endDate' => '31.07'
                ],
                        [
                            'name' => '2 семестр',
                            'startDate' => '01.09',
                            'endDate' => '31.12'
                        ]
                        ]
            ]
        ];
        var_dump($arr);

    }

}