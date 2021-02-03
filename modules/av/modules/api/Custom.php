<?php


namespace app\models\api;


use app\models\interfaces\ApiModule;
use app\modules\student\models\lists\MarkValues;
use app\modules\student\models\students\Students;
use app\modules\load\models\groups\Groups;

use Yii;
use yii\db\Query;
use yii\web\ForbiddenHttpException;

/**
 * Class Database
 *
 * @package app\models\api
 */
class Custom extends ApiModule
{
    public $name = 'Кастомные запросы к базе данных';


    public function methodGetGroups()
    {
        return Groups::find()->all();
    }

    public function methodGetGroup($id)
    {
        return Groups::findOne($id);
    }

    public function methodGetStudentsByGroup($group_id)
    {
        return Students::find()
            ->where(['group_id' => $group_id])
            ->andWhere(['active' => 1])
            ->all();
    }

    public function methodGetCurriculumDisciplines($education_plan_id)
    {
        return (new Query())
            ->select(['p.id', 'p.code', 'p.discipline_id', 'p.level', 's.name', 's.name_short'])
            ->from('plan_curriculum_disciplines p')
            ->leftJoin('plan_disciplines s', 'p.discipline_id = s.id')
            ->where(['p.education_plan_id' => $education_plan_id, 'p.type' => null, 'p.level' => 3, ])
            ->andWhere(['not', ['s.name' => null]])
            ->orderBy(['left_node' => SORT_ASC])
            ->all();
    }

    public function methodGetMarksByGroup($group_id)
    {
        $students = $this->methodGetStudentsByGroup($group_id);

        return (new Query())
            ->select(['s.id', 's.journal_lesson_id', 's.student_id', 's.mark_type_id', 's.mark_value_id', 's.datetime', 's.parent_id','p.control_type_id', 'p.curriculum_discipline_id','p.class_type_id'])
            ->from('student_marks s')
            ->leftJoin('student_journal_lessons p', 's.journal_lesson_id = p.id')
            ->where(['in', 'student_id', array_column($students, 'id') ])
            ->andWhere(['not', ['mark_value_id' => null]])
            ->all();
    }

    public function methodGetMarkValues()
    {
        return MarkValues::find()
            ->asArray()
            ->all();
    }




}