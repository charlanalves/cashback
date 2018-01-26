<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\CB04EMPRESA;
use common\models\CB03CONTABANC;
use common\models\CB09FORMAPAGTOEMPRESA;
use common\models\CB05PRODUTO;
use common\models\CB12ITEMCATEGEMPRESA;
use common\models\CB06VARIACAO;
use common\models\CB07CASHBACK;
use common\models\CB10CATEGORIA;
use common\models\CB11ITEMCATEGORIA;
use common\models\CB13FOTOEMPRESA;
use common\models\CB14FOTOPRODUTO;
use common\models\SYS01PARAMETROSGLOBAIS;
use common\models\CB16PEDIDO;
use common\models\CB23TIPOAVALIACAO;

/**
 * Administrador controller
 */
class AdministradorController extends \common\controllers\GlobalBaseController {

   /**
    * @var string Habilita validação Csrf 
   */
    public $enableCsrfValidation = false;
    
    private $user = null;
    public $layout = 'smartAdminAdministrador';
    public $relatedModel = "";

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
        echo $this->renderFile('@app/web/libs/C7.1.0.0.js.php');
        echo $this->renderFile('@app/views/administrador/formaPagamentoDxInit.php');
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
    public function actionRealizaSaquesCliente() 
    {	
       \Yii::$app->Iugu->execute('realizaSaques', ['']);
    }
    
	public function actionTrans2() 
    {	
    	 \Yii::$app->Iugu->execute('createAccount',['a'=>1]);
       //\Yii::$app->Iugu->execute('criaTransferencias2',['aa'=>1]);
    }
    
    public function actionSaque() 
    {   
         $trans = \common\models\PAG04TRANSFERENCIAS::getTransSaques();
        
         \Yii::$app->Iugu->execute('doTranfer', $trans);
    }
    
	public function actionAtualizadtdep()
    {
    	$pedidos = CB16PEDIDO::getPedidoByStatus(CB16PEDIDO::status_pago_trans_agendadas);
    	  if (count($pedidos) > 0) {
       		\Yii::$app->Iugu->execute('fetchUpdateDtDepInvoice', $pedidos);
    	  }
    }
    public function actionTransferencias() 
    {	
        
        echo $this->renderFile('@app/web/libs/C7.1.0.0.js.php');
        echo $this->renderFile('@app/views/administrador/_form.php');
        
        return $this->render('trasferencias', ['tituloTela' => 'Empresa']);
    }

	public function actionFetchAccount()
    {
      
    
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
        unset($dataEstabelecimento['CB04_DADOS_API_TOKEN']);
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
            $empresas = CB04EMPRESA::find()->where('CB04_TIPO = 1')->orderBy('CB04_ID DESC')->all();
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
    
    private function prepareAccountData($param)
    {	 
	    return [
                "price_range" => "Mais que R$ 500,00",
                "physical_products" => false,
                "business_type" => "Serviços e produtos diversos",
                "automatic_transfer" => true,
                "person_type" => 'Pessoa Jurídica',
                "cnpj" => $param['CB04_CNPJ'],
                "company_name" => $param['CB04_NOME'], 
                "address" => $param['CB04_END_LOGRADOURO'], 
                "cep"=> $param['CB04_END_CEP'], 
                "city" => $param['CB04_END_CIDADE'], 
                "state" => $param['CB04_END_UF'], 
                "telephone" => $param['CB04_TEL_NUMERO'], 
                "bank" => $param['CB03_NOME_BANCO'], 
                "bank_ag" => $param['CB03_AGENCIA'], 
                "account_type" => ($param['CB03_TP_CONTA']) ? 'Corrente': 'Poupança', 
                "bank_cc" => $param['CB03_NUM_CONTA']
	    ];
    }
    
    private function saveContaBancaria($param)
    {	 
	   $conta = new CB03CONTABANC;
	   $param['CB03_USER_ID'] = \Yii::$app->user->identity->id;
	   $conta->setAttributes($param);
	   $conta->save();
    }
    
    public function saveEmpresa($param) {
        
        unset($param['CB04_URL_LOGOMARCA']);
        
        $model = (!$param['CB04_ID']) ? new CB04EMPRESA() : CB04EMPRESA::findOne($param['CB04_ID']);
        $new = $model->isNewRecord;
        \Yii::$app->Iugu->transaction = \Yii::$app->db->beginTransaction();
        $id = $model->saveEstabelecimento($param);
        $this->saveContaBancaria($param);
        
        if ($new) {
            $data = $this->prepareAccountData($param);
            \Yii::$app->Iugu->execute('createCompanyAccount',[ 'data'=> $data,'model'=> $model, 'id'=> $id] );
        } else {        
            \Yii::$app->Iugu->transaction->commit();
        }
        $a  = 1;
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
    
    public function actionCategoria() {
        $model = new CB10CATEGORIA();
        echo $this->renderFile('@app/web/libs/C7.1.0.0.js.php');
        echo $this->renderFile('@app/views/administrador/categoriaDxInit.php');
        return $this->render('categoria', [
                    'tituloTela' => 'Categoria',
                    'al' => $model->attributeLabels()
        ]);
    }

    public function createCategoria($param) {
        $model = new CB10CATEGORIA();
        $model->setAttribute('CB10_NOME', $param['nome']);
        $model->setAttribute('CB10_ICO', $param['ico']);
        $model->setAttribute('CB10_STATUS', 1);
        $model->save();
    }

    public function categoriaDisable($param) {
        $model = CB10CATEGORIA::findOne($param['id']);
        if($model) {
            $model->setAttribute('CB10_STATUS', 0);
            $model->save();
        }
    }

    public function createItemCategoria($param) {
        $model = new CB11ITEMCATEGORIA();
        $model->setAttribute('CB11_CATEGORIA_ID', $param['cat']);
        $model->setAttribute('CB11_DESCRICAO', $param['item']);
        $model->setAttribute('CB11_STATUS', 1);
        $model->save();
    }

    public function itemCategoriaDisable($param) {
        $model = CB11ITEMCATEGORIA::findOne($param['id']);
        if($model) {
            $model->setAttribute('CB11_STATUS', 0);
            $model->save();
        }
    }

    public function createItemAvaliacao($param) {
        $model = new CB23TIPOAVALIACAO();
        $model->setAttribute('CB23_CATEGORIA_ID', $param['id']);
        $model->setAttribute('CB23_DESCRICAO', $param['item']);
        $model->setAttribute('CB23_ICONE', $param['ico']);
        $model->setAttribute('CB23_STATUS', 1);
        $model->save();
    }

    public function itemAvaliacaoDisable($param) {
        $model = CB23TIPOAVALIACAO::findOne($param['id']);
        if($model) {
            $model->setAttribute('CB23_STATUS', 0);
            $model->save();
        }
    }
    
    public function actionParamSistema() {
        $parans = SYS01PARAMETROSGLOBAIS::find()->asArray()->all();
        return $this->render('param-sistema', [
                    'tituloTela' => 'Parâmetros do Sistema',
                    'parans' => Yii::$app->u->jsonEncodeRecursive($parans)
        ]);
    }
    
    public function saveParamSistema($param) {
        foreach ($param as $key => $value) {
            SYS01PARAMETROSGLOBAIS::setValor($key, $value);
        }
    }

    public function callMethodDynamically($action, $data, $returnThowException = true, $class = NULL) {
        if (empty($class)) {
            $class = $this;
        }

        $methodExists = method_exists($class, $action);
        Yii::$app->v->isFalse(['methodExists' => $methodExists], '', 'app', $returnThowException);

        return call_user_func_array([$class, $action], [$data]);
    }

    public function actionGlobalRead($gridName, $param = '', $json = false) {
        switch ($gridName) {
            case 'CategoriaMain':
                $this->relatedModel = "common\models\CB10CATEGORIA";
            break;
            case 'ItensCategoriaMain':
                $this->relatedModel = "common\models\CB11ITEMCATEGORIA";
            break;
            case 'ItensAvaliacaoMain':
                $this->relatedModel = "common\models\CB23TIPOAVALIACAO";
            break;
            case 'FormaPagamentoMain':
                $this->relatedModel = "common\models\CB09FORMAPAGTOEMPRESA";
            break;
        }
        parent::actionGlobalRead($gridName, $param, $json);
    }
}
