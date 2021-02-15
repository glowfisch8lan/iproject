<?php


namespace app\modules\av\modules\student\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
use yii\httpclient\Client;

use app\modules\av\modules\student\models\tables\StudentSkipReasons;
use app\modules\av\modules\student\models\tables\StudentMarks;
use app\modules\av\modules\student\models\tables\StudentMarkValues;

class StudentsApi extends ActiveRecord
{
    protected static $token_custom = 'c5f0dde7-cb0c-401d-8b11-81d4317da0f3';

    public static function getDb()
    {
        return \Yii::$app->db1;
    }

    /*МЕТОДЫ API */
    public static function getGroups()
    {
        $cache = Yii::$app->cache;
        $duration = 1200;

        /**
         * Кеширование списка групп
         */
        $response = $cache->get('groups');

        if ($response === false) {

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('https://av.dvuimvd.ru/api/call/system-custom/get-groups?token='.self::$token_custom)
                ->send()
                ->data['data'];
            $cache->set('groups', $response, $duration);
        }

        return $response;

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

        //https://av.dvuimvd.ru/api/call/system-custom/get-students-by-group?group_id=24&token=c5f0dde7-cb0c-401d-8b11-81d4317da0f3
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

        $students = self::getStudentsByGroup($group_id);

        return StudentMarks::find()->select(['s.id', 's.journal_lesson_id', 's.student_id', 's.mark_type_id', 's.skip_reason_id', 's.mark_value_id', 's.datetime', 's.parent_id','p.control_type_id', 'p.curriculum_discipline_id','p.class_type_id', 'p.date as lesson_date'])
            ->from('student_marks s')
            ->leftJoin('student_journal_lessons p', 's.journal_lesson_id = p.id')
            ->where(['in', 'student_id', array_column($students, 'id') ])
//            ->andWhere(['not', ['mark_value_id' => null]])
                ->asArray()
            ->all();

    }

    public static function getMarksValues()
    {

        $cache = Yii::$app->cache;
        $duration = 1200;

        /**
         * Кеширование списка групп
         */
        $response = $cache->get('markValues');
        if ($response === false) {

            $response = StudentMarkValues::find()
                ->asArray()
                ->all();

            $cache->set('markValues', $response, $duration);
        }

        return $response;

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
        return StudentSkipReasons::find()->asArray()->all();
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