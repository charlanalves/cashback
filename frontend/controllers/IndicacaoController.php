<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;


class IndicacaoController extends Controller
{
    
    public function actionIndex()
    {
        $this->layout = false;
        $cod = \Yii::$app->request->get('c');
        return $this->render('index', ['cod' => $cod]);
    }

}
