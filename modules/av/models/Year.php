<?php


namespace app\modules\av\models;

use Yii;
use DateTime;
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
    }

    public static function getStartAndEndDate($week, $year)
    {
            $dto = new DateTime();
            $dto->setISODate($year, $week);
            $ret['week_start'] = $dto->format('Y-m-d');
            $dto->modify('+6 days');
            $ret['week_end'] = $dto->format('Y-m-d');
            return $ret;
    }

}