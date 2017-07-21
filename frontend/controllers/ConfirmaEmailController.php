<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;


class ConfirmaEmailController extends Controller
{
    
    public function actionIndex()
    {
        $this->layout = $conf = $email = false;

        $cod = \Yii::$app->request->get('c');
        if($cod) {
            $user = User::findOne(['auth_key' => $cod]);
            if($user) {
            	$email = $user->email;
                $user->setAttribute('email_valid', 1);
                $conf = $user->save();
            }
        }
        return $this->render('index', ['conf' => $conf, 'email' => $email]);
    }

}
