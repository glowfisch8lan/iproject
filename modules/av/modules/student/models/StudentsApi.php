<?php


namespace app\modules\av\modules\student\models;


use yii\httpclient\Client;

class StudentsApi
{
    protected static $token_custom = 'c5f0dde7-cb0c-401d-8b11-81d4317da0f3';

    /*МЕТОДЫ API */
    public static function getGroups()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-groups?token='.self::$token_custom)
            ->send();
        return $response->data['data'];

    }

    public static function getGroup($id)
    {

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-group?token='.self::$token_custom)
            ->setData(['id' => $id])
            ->send();
        return $response->data['data'];

    }

    public static function getStudentsByGroup($group_id)
    {

        https://av.dvuimvd.ru/api/call/system-custom/get-students-by-group?group_id=24&token=c5f0dde7-cb0c-401d-8b11-81d4317da0f3
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-students-by-group?token='.self::$token_custom)
            ->setData(['group_id' => $group_id])
            ->send();
        return $response->data['data'];
    }

    public static function getCurriculumDisciplines($education_plan_id)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-curriculum-disciplines?&token='.self::$token_custom)
            ->setData([
                'education_plan_id' => $education_plan_id
            ])
            ->send();

        return $response->data['data'];
    }

    public static function getMarksByGroup($group_id)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-marks-by-group?token='.self::$token_custom)
            ->setData(['group_id' => $group_id])
            ->send();
        return $response->data['data'];

    }

    public static function getMarksValues()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-mark-values?token='.self::$token_custom)
            ->send();
        return $response->data['data'];

    }

    /*
     * Не работает!
     */
    public static function getJournalLesson($group_id, $curriculum_discipline_id, $date)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-journal-lesson?token='.self::$token_custom)
            ->setData(['group_id' => $group_id, 'curriculum_discipline_id' => $curriculum_discipline_id, 'date' => $date])
            ->send();

        return $response->data['data'];

    }

    public static function getSkipReasons()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-skip-reasons?token='.self::$token_custom)
            ->send();

        return $response->data['data'];

    }

    /**
     * Список групп, по курсам с количеством студентов;
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public static function getGroupList(){

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-group-list?token='.self::$token_custom)
            ->send();

        return $response->data['data'];
    }
    /*МЕТОДЫ API */
}