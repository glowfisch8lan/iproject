<?php
use Yii;
use yii\db\Migration;

/**
 * Class m200705_124745_create_rbac_data
 */
class m200705_124745_create_rbac_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $auth = Yii::$app->authManager;
        //Разрешения;
        $accessSettingsPermission = $auth->createPermission('viewSettings');
        $auth->add($accessSettingsPermission);

        //Роли;

        $userRole = $auth->createRole('user');
        $auth->add($userRole);

        $adminRole = $auth->createRole('admin');
        $auth->add($adminRole);

        //Связи;

              $auth->addChild($adminRole, $accessSettingsPermission);

        $user = new User([
            'username' => 'admin',
            'password' => Yii::$app->security->generatePasswordHash($model->password)

            ]);
        $user->generateAuthKey();
        $user->save();

        $auth->assign($adminRole, $user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200705_124745_create_rbac_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200705_124745_create_rbac_data cannot be reverted.\n";

        return false;
    }
    */





}
