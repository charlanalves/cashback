<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\CB04EMPRESA;
use common\models\CB09FORMAPAGEMPRESA;
use common\models\CB05PRODUTO;
use common\models\CB11ITEMCATEGORIA;
use common\models\CB12ITEMCATEGEMPRESA;
use common\models\CB06VARIACAO;
use common\models\CB07CASHBACK;
use common\models\CB13FOTOEMPRESA;
use common\models\CB14FOTOPRODUTO;
use common\models\SYS01PARAMETROSGLOBAIS;
use common\models\CB16PEDIDO;

/**
 * Administrador controller
 */
class AdministradorController extends \common\controllers\GlobalBaseController {

    private $user = null;
    public $layout = 'smartAdminAdministrador';

    public function __construct($id, $module, $config = []) {
        if (($identity = \Yii::$app->user->identity)) {
            $this->user = $identity;
        }
        parent::__construct($id, $module, $config);
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    private function goLogin() {
        return $this->redirect(\yii\helpers\Url::to('index.php?r=administrador/login'));
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        return $this->goLogin();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        $this->layout = 'main-login';

        if (!\Yii::$app->user->isGuest) {
            $this->redirect(\yii\helpers\Url::to('index.php?r=administrador/principal'));
            return;
        }

        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIOADMINISTRADOR;

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goLogin();
        } else {
            return $this->render('login', ['model' => $model]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goLogin();
    }

    public function actionPrincipal() {
        $this->redirect(\yii\helpers\Url::to('index.php?r=administrador/empresa'));
        return;
    }

    public function actionEmpresa() 
    {
        return $this->render('empresa', [
                    'tituloTela' => 'Empresa',
                    'usuario' => $this->user->attributes
        ]);
    }
    
    public function actionTrans() 
    {	
        $pedido = \common\models\CB16PEDIDO::findOne(5);
       \Yii::$app->Iugu->execute('criaTransferencias', ['pedido' => $pedido]);
    }
    
    public function actionTransferencias() 
    {	
        
        echo $this->renderFile('@app/web/libs/C7.1.0.0.js.php');
        echo $this->renderFile('@app/views/administrador/_form.php');
        
        return $this->render('trasferencias', ['tituloTela' => 'Empresa']);
    }

    public function actionEmpresaForm() {
        $this->layout = 'empty';

        $model = new CB04EMPRESA();
        $al = $model->attributeLabels();
        $dataEstabelecimento = [];

        if (($empresa = Yii::$app->request->get('empresa'))) {
            if (($dataEstabelecimento = $model->findOne($empresa))) {

                $dataEstabelecimento = $dataEstabelecimento->getAttributes();
                
                $dataEstabelecimento["FORMA-PAGAMENTO"] = CB04EMPRESA::getFormaPagamento($dataEstabelecimento['CB04_ID']);
                $dataEstabelecimento['CB04_FUNCIONAMENTO'] = str_replace("\r\n", '\r\n', $dataEstabelecimento['CB04_FUNCIONAMENTO']);
                $dataEstabelecimento['CB04_OBSERVACAO'] = str_replace("\r\n", '\r\n', $dataEstabelecimento['CB04_OBSERVACAO']);
            }
        }

        $dataCategoria = CB04EMPRESA::findCombo('CB10_CATEGORIA', 'CB10_ID', 'CB10_NOME', 'CB10_STATUS=1');
        $dataFormaPagamento = CB04EMPRESA::findCombo('CB08_FORMA_PAGAMENTO', 'CB08_ID', 'CB08_NOME', 'CB08_STATUS=1');
        $limitFotos = SYS01PARAMETROSGLOBAIS::getValor(5); // limit de fotos da empresa
        //  
        return $this->render('empresaForm', [
                    'tituloTela' => 'Empresa',
                    'usuario' => $this->user->attributes,
                    'estabelecimento' => $dataEstabelecimento,
                    'categorias' => $dataCategoria,
                    'formaPagamento' => $dataFormaPagamento,
                    'limitFotos' => $limitFotos,
                    'al' => $al,
        ]);
    }

    public function actionEmpresaGrid() {
        
        if(($busca = (string) Yii::$app->request->get('busca'))){
            $empresas = CB04EMPRESA::findAll(['' => $busca]);        
        } else {
            $empresas = CB04EMPRESA::find()->orderBy('CB04_ID DESC')->all();
        }
        
        if ($empresas) {
            $param = ['empresas' => $empresas];
        } else {
            $param = ['error' => 'Nenhum registro encontrado...' . ($busca ? " <strong> $busca </strong>" : "" )];
        }
        return $this->renderPartial('empresaGrid', $param);
    }

    public function actionEmpresaAtivar($empresa, $status) {
        $CB04EMPRESA = CB04EMPRESA::findOne($empresa);
        $CB04EMPRESA->setAttribute('CB04_STATUS', $status);
        return ($CB04EMPRESA->save()) ? '' : 'error';
    }
    
    public function saveEmpresa($param) {
        unset($param['CB04_URL_LOGOMARCA']);
        $model = (!$param['CB04_ID']) ? new CB04EMPRESA() : CB04EMPRESA::findOne($param['CB04_ID']);
        $id = $model->saveEstabelecimento($param);
        
        if (!empty($_FILES['CB04_URL_LOGOMARCA']['name'])) {
            
            $infoFile = \Yii::$app->u->infoFile($_FILES['CB04_URL_LOGOMARCA']);
            if($infoFile['family'] == 'image') {
                $infoFile['path'] = 'img/fotos/estabelecimento/';
                $infoFile['newName'] = uniqid("logo_" . $id . "_") . '.' . $infoFile['ex'];

                $file = \yii\web\UploadedFile::getInstanceByName('CB04_URL_LOGOMARCA');
                $pathCompleto = $infoFile['path'] . $infoFile['newName'];

                if ($file->saveAs($pathCompleto)) {
                    if(!empty($model->CB04_URL_LOGOMARCA)) {
                        @unlink($model->CB04_URL_LOGOMARCA);
                    }
                    $model->setAttribute('CB04_URL_LOGOMARCA', $pathCompleto);
                    $model->save();
                }
            }
        }
        
        exit(json_encode(['message' => $id, 'status' => true]));
    }
    
    
    public function fotoEmpresa() {
        $get = Yii::$app->request->get();
        
        if(($empresa = $get['empresa'])) {

            $getAction = $get['param'];

            // salva imagem
            if ($getAction == 'save') {

                // testa quantidade de fotos
                $limitFotos = SYS01PARAMETROSGLOBAIS::getValor(5); // limit de fotos da empresa
                $qtdFotos = CB13FOTOEMPRESA::find()->where(['CB13_EMPRESA_ID' => $empresa])->count();
                if ($limitFotos <= $qtdFotos) {
                    throw new \Exception('Limite de fotos atingido para o estabelecimento!');
                }

                $infoFile = \Yii::$app->u->infoFile($_FILES['file']);
                $infoFile['path'] = 'img/fotos/estabelecimento/';
                $infoFile['newName'] = uniqid($empresa . "_") . '.' . $infoFile['ex'];

                $CB13FOTOEMPRESA = new CB13FOTOEMPRESA();
                $CB13FOTOEMPRESA->setAttributes([
                    'CB13_EMPRESA_ID' => $empresa,
                    'CB13_URL' => $infoFile['path'] . $infoFile['newName']
                ]);
                $CB13FOTOEMPRESA->save();

                $file = \yii\web\UploadedFile::getInstanceByName('file');
                $file->saveAs($infoFile['path'] . $infoFile['newName']);

                // deleta imagem
            } else if ($getAction == 'delete') {
                $foto = Yii::$app->request->get('foto');
                if ($foto) {
                    $modelFoto = CB13FOTOEMPRESA::findOne(['CB13_ID' => $foto, 'CB13_EMPRESA_ID' => $empresa]);
                    if ($modelFoto) {
                        $modelFoto->delete();
                        @unlink($modelFoto->CB13_URL);
                    }
                }
            } else if ($getAction == 'read') {
                $dataFotos = CB04EMPRESA::findCombo('CB13_FOTO_EMPRESA', 'CB13_ID', 'CB13_URL', 'CB13_EMPRESA_ID=' . $empresa);
                throw new \Exception(json_encode($dataFotos));
            }
        }
    }

}
