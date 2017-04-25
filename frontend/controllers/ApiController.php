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

/**
 * Site controller
 */
class ApiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'cadastro'],
                'rules' => [
                    [
                        'actions' => ['cadastro'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLoginAppPdv()
    {
        header('Content-type: application/json');
        $model = new LoginForm();
        $model->scenario = $model::SCENARIO_COMPANY_LOGIN;
        
        try {
            if ($model->load(Yii::$app->request->get(),'') && $model->loginCpfCnpj()) {
                //$msg = ['userdata'=> $model->getUserByCpfCnpj()->getAttributes(), 'error'=> false,'error_msg' => null];
                $user = $model->getUserByCpfCnpj()->getAttributes();
                if (empty($user['id_company'])) {
                    throw new \Exception("Usuário não tem empresa associada.");                
                }
                
                $msg = ['userdata'=> $this->getProdutosVarEmpresa($user['id_company']), 'error'=> false,'error_msg' => null];
            } else {
                if(empty($model->getFirstErrors())) {
                    throw new \Exception("Usuário e senha inválidos.");    
                }
                
                throw new \Exception(array_values($model->getFirstErrors())[0]);   
            }
    
         } catch (\Exception $exc) {
            $msg = ['userdata'=> null, 'error'=> true, 'error_msg' => $exc->getMessage()];
        }
        
      $this->layout = false;
  
      echo json_encode($msg);
      \Yii::$app->end();
    }
    
    public function actionGetProdutosVarEmpresa()
    {
        try {
            header('Content-type: application/json');
            $idEmpresa = Yii::$app->request->get('idEmpresa');

            $prodVariacoes = $this->getProdutosVarEmpresa($idEmpresa);

            $msg = ['userdata'=> $prodVariacoes, 'error'=> false,'error_msg' => null];
        } catch (\Exception $exc) {
            $msg = ['userdata'=> null, 'error'=> true, 'error_msg' => $exc->getMessage()];
        }
        
        $this->layout = false;  
        echo json_encode($msg);
        \Yii::$app->end();
    }
    
    public function getProdutosVarEmpresa($idEmpresa)
    {
        if (empty($idEmpresa)) {
            throw new \Exception("Erro interno o código da empresa não foi informado.");
        }

        $connection = \Yii::$app->getDb();
        $prodVariacoes = $connection->createCommand('
          SELECT 
                CB05_PRODUTO.CB05_TITULO,
                CB06_VARIACAO.CB06_DESCRICAO,
                CB06_VARIACAO.CB06_PRECO,
           IFNULL(CB07_CASH_BACK.CB07_PERCENTUAL, 0) AS "CB07_PERCENTUAL"
          FROM CB05_PRODUTO
          JOIN CB06_VARIACAO ON CB06_VARIACAO.CB06_PRODUTO_ID = CB05_PRODUTO.CB05_ID 
          LEFT JOIN CB07_CASH_BACK ON CB06_VARIACAO.CB06_ID = CB07_CASH_BACK.CB07_VARIACAO_ID 
            AND CB07_CASH_BACK.CB07_DIA_SEMANA = WEEKDAY(NOW())
          WHERE CB05_PRODUTO.CB05_EMPRESA_ID = :idEmpresa
        ')->bindValue(':idEmpresa', $idEmpresa)
          ->queryAll();


        if (empty($prodVariacoes)) {
            throw new \Exception("Você ainda não tem produtos cadastrados.");
        }

       return $this->parsePadraoApp($prodVariacoes);
    }
    
    private function parsePadraoApp($prodVariacoes)
    {
        $prodNew = [];
        foreach ($prodVariacoes as $key => $value) 
        {
            $prodNew['produtos'][$key]['titulo'] = $value['CB05_TITULO'];
            $prodNew['produtos'][$key]['descricao'] = $value['CB06_DESCRICAO'];
            $prodNew['produtos'][$key]['preco'] = $value['CB06_PRECO'];
            $prodNew['produtos'][$key]['percentual'] = $value['CB07_PERCENTUAL'];
        }
        return $prodVariacoes;
    }
    
    
    /**
     * Logs in a user.
     *
     * @return mixed
     */
     public function actionLogin()
    {
        $this->layout = 'main-login';
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->loginCpfCnpj()) {
            return $this->goBack();
        } else {
            
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Cadastro user up.
     *
     * @return mixed
     */
    public function actionCadastro()
    {   
        $this->layout = 'main-login';
        
        $convidado['codIndicacao'] = Yii::$app->request->get('cod');
        $convidado['idIndicacao'] = \common\models\User::getIdByAuthKey($convidado['codIndicacao']);
        
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('cadastro', [
            'model' => $model,
            'convidado' => $convidado,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
