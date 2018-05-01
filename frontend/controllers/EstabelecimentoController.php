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
use common\models\CB19AVALIACAO;
use common\models\CB20ITEMAVALIACAO;
use common\models\CB21RESPOSTAAVALIACAO;
use common\models\CB22COMENTARIOAVALIACAO;
use common\models\CB23TIPOAVALIACAO;
use common\models\VIEWEXTRATO;
use common\models\VIEWFUNCIONARIO;
use common\models\CB03CONTABANC;
use common\models\PAG04TRANSFERENCIAS;
use common\models\User;
use common\models\EstabelecimentoExtratoModel;

/**
 * Estabelecimento controller
 */
class EstabelecimentoController extends \common\controllers\GlobalBaseController {

   /**
    * @var string Habilita validação Csrf 
   */
    public $enableCsrfValidation = false;
    
    private $user = null;
    private $estabelecimento = null;
    public $relatedModel = "";
    public $funcionario = null;
    
    public function __construct($id, $module, $config = []) 
    {
        $this->btns =  [];
        $this->layout = 'smartAdminEstabelecimento';
        if (($identity = \Yii::$app->user->identity)) {
            $this->user = $identity;
            $this->funcionario = self::isFuncionario($this->user->id);
            $this->estabelecimento = ($this->user->id_company) ? \common\models\GlobalModel::findTable('CB04_EMPRESA', 'CB04_ID = ' . $this->user->id_company)[0] : null;
        }
        parent::__construct($id, $module, $config);
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    private function goLogin() {
        return $this->redirect(\yii\helpers\Url::to('index.php?r=estabelecimento/login'));
    }    

    private static function isFuncionario($user) {
        return \common\models\GlobalModel::findTable('auth_assignment', 'item_name = \'funcionario\' AND user_id = ' . $user) ? true : false;
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
            if (!$this->funcionario) {
                $this->redirect(\yii\helpers\Url::to('index.php?r=estabelecimento/produto'));
            } else {
                $this->redirect(\yii\helpers\Url::to('index.php?r=estabelecimento/delivery-dx'));
            }
            return;
        }

        $model = new LoginForm();
        $model->setScenario(LoginForm::SCENARIOESTABELECIMENTO);
        if ($model->load(Yii::$app->request->post()) && $model->loginCpfCnpj()) {
            return $this->goLogin();
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
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goLogin();
    }

    public function actionPrincipal() {
        $this->redirect(\yii\helpers\Url::to('index.php?r=estabelecimento/produto'));
        return;
    }

    public function actionEmpresa() {
        $salvo = null;

        $model = new CB04EMPRESA();
        $al = $model->attributeLabels();
        $dataEstabelecimento = $model->findOne($this->user->id_company);
        if (($post = Yii::$app->request->post())) {
            unset($post['CB04_URL_LOGOMARCA']);
            $salvo = $dataEstabelecimento->saveEstabelecimento($post);

            if (!empty($_FILES['CB04_URL_LOGOMARCA']['name'])) {

                $infoFile = \Yii::$app->u->infoFile($_FILES['CB04_URL_LOGOMARCA']);
                if($infoFile['family'] == 'image') {
                    $infoFile['path'] = 'img/fotos/estabelecimento/';
                    $infoFile['newName'] = uniqid("logo_" . $salvo . "_") . '.' . $infoFile['ex'];

                    $file = \yii\web\UploadedFile::getInstanceByName('CB04_URL_LOGOMARCA');
                    $pathCompleto = $infoFile['path'] . $infoFile['newName'];

                    if ($file->saveAs($pathCompleto)) {

                        if(!empty($dataEstabelecimento->CB04_URL_LOGOMARCA)) {
                            @unlink($dataEstabelecimento->CB04_URL_LOGOMARCA);
                        }

                        $dataEstabelecimento->setAttribute('CB04_URL_LOGOMARCA', $pathCompleto);
                        $dataEstabelecimento->save();
                    }
                }
            }
            
        }

        $dataEstabelecimento = $dataEstabelecimento->getAttributes();
        $dataEstabelecimento["FORMA-PAGAMENTO"] = CB04EMPRESA::getFormaPagamento($this->user->id_company);
        $dataCategoria = CB04EMPRESA::findCombo('CB10_CATEGORIA', 'CB10_ID', 'CB10_NOME', 'CB10_STATUS=1');
        $dataFormaPagamento = CB04EMPRESA::findCombo('CB08_FORMA_PAGAMENTO', 'CB08_ID', 'CB08_NOME', 'CB08_STATUS=1');
        $limitFotos = SYS01PARAMETROSGLOBAIS::getValor(5); // limit de fotos da empresa
        
        $dataEstabelecimento['CB04_FUNCIONAMENTO'] = str_replace("\r\n", '\r\n', $dataEstabelecimento['CB04_FUNCIONAMENTO']);
        $dataEstabelecimento['CB04_OBSERVACAO'] = str_replace("\r\n", '\r\n', $dataEstabelecimento['CB04_OBSERVACAO']);
        unset($dataEstabelecimento['CB04_DADOS_API_TOKEN']);
        return $this->render('empresa', [
                    'tituloTela' => 'Empresa',
                    'usuario' => $this->user->attributes,
                    'estabelecimento' => $dataEstabelecimento,
                    'categorias' => $dataCategoria,
                    'formaPagamento' => $dataFormaPagamento,
                    'limitFotos' => $limitFotos,
                    'al' => $al,
                    'salvo' => $salvo
        ]);
    }

    public function fotoEmpresa() {
        $getAction = Yii::$app->request->get('param');
        $empresa = $this->user->id_company;
        
        // salva imagem
        if ($getAction == 'save') {
            
            // testa quantidade de fotos
            $limitFotos = SYS01PARAMETROSGLOBAIS::getValor(5); // limit de fotos da empresa
            $qtdFotos = CB13FOTOEMPRESA::find()->where(['CB13_EMPRESA_ID' => $empresa])->count();
            if($limitFotos <= $qtdFotos) {
                throw new \Exception('Limite de fotos atingido para o estabelecimento!');
            }
            
            $infoFile = \Yii::$app->u->infoFile($_FILES['file']);
            $infoFile['path'] = 'img/fotos/estabelecimento/';
            $infoFile['newName'] = uniqid($empresa."_") . '.' . $infoFile['ex'];
            
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

    public function actionProduto() {
        $salvo = null;

        $model = new CB05PRODUTO();
        $al = $model->attributeLabels();

        $dataProduto = $model->getProdutoVariacao($this->user->id_company);
//        print_r('<pre>');
//        print_r($dataProduto);
//        exit();
        return $this->render('produto', [
                    'tituloTela' => 'Produto',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'al' => $al,
                    'estabelecimento' => $this->estabelecimento,
                    'salvo' => $salvo
        ]);
    }

    public function actionProdutoAtivar($produto, $status) {
        $CB05PRODUTO = CB05PRODUTO::findOne($produto);
        $CB05PRODUTO->setAttribute('CB05_ATIVO', $status);
        return ($CB05PRODUTO->save()) ? '' : 'error';
    }

    public function actionProdutoForm($produto = null) {
        \Yii::$app->view->title = $maxProduto = "";
        $this->layout = 'empty';

        $dataProduto = [];

        $model = new CB05PRODUTO();
        $al = $model->attributeLabels();

        $dataItemProduto = CB04EMPRESA::findCombo('CB11_ITEM_CATEGORIA', 'CB11_ID', 'CB11_DESCRICAO', 'CB11_STATUS=1 AND CB11_CATEGORIA_ID=' . $this->estabelecimento['CB04_CATEGORIA_ID']);
        $limitFotos = SYS01PARAMETROSGLOBAIS::getValor(6); // limit de fotos do produto
        
        if (is_numeric($produto)) {
            // dados do produto
            $dataProduto = $model
                    ->find()
                    ->where(['CB05_EMPRESA_ID' => $this->user->id_company, 'CB05_ID' => $produto])
                    ->orderBy('CB05_NOME_CURTO')
                    ->one();
            $dataProduto = $dataProduto->getAttributes();
            $dataProduto['CB05_DESCRICAO'] = str_replace("\n", '\r\n', $dataProduto['CB05_DESCRICAO']);
            $dataProduto['CB05_IMPORTANTE'] = str_replace("\n", '\r\n', $dataProduto['CB05_IMPORTANTE']);

            // itens selecionados
            $dataProduto["ITEM-PRODUTO"] = CB05PRODUTO::getItem($produto);
        } else {
            $qtdMaxProduto = (int) SYS01PARAMETROSGLOBAIS::getValor('3');
            $qtdProduto = CB05PRODUTO::find()->where(['CB05_EMPRESA_ID' => $this->user->id_company])->count();
            if ($qtdMaxProduto <= $qtdProduto) {
                $maxProduto = "Você atingiu o limite de produtos do sistema.";
            }
        }

        return $this->render('produtoForm', [
                    'tituloTela' => 'Produto',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'itemProduto' => $dataItemProduto,
                    'limitFotos' => $limitFotos,
                    'al' => $al,
                   // 'maxProduto' => $maxProduto,
        		   	'maxProduto' => 0,
        ]);
    }

    public function actionProdutoFullForm($produto = null) {
        \Yii::$app->view->title = $maxProduto = "";
        $this->layout = 'empty';

        $dataProduto = [];

        $model = new CB05PRODUTO();
        $al = $model->attributeLabels();

        $dataItemProduto = CB04EMPRESA::findCombo('CB11_ITEM_CATEGORIA', 'CB11_ID', 'CB11_DESCRICAO', 'CB11_STATUS=1 AND CB11_CATEGORIA_ID=' . $this->estabelecimento['CB04_CATEGORIA_ID']);
        $limitFotos = SYS01PARAMETROSGLOBAIS::getValor(6); // limit de fotos do produto
        
        if (is_numeric($produto)) {
            // dados do produto
            $dataProduto = $model
                    ->find()
                    ->where(['CB05_EMPRESA_ID' => $this->user->id_company, 'CB05_ID' => $produto])
                    ->orderBy('CB05_NOME_CURTO')
                    ->one();
            $dataProduto = $dataProduto->getAttributes();
            $dataProduto['CB05_DESCRICAO'] = str_replace("\n", '\r\n', $dataProduto['CB05_DESCRICAO']);
            $dataProduto['CB05_IMPORTANTE'] = str_replace("\n", '\r\n', $dataProduto['CB05_IMPORTANTE']);

            // itens selecionados
            $dataProduto["ITEM-PRODUTO"] = CB05PRODUTO::getItem($produto);
        } else {
            $qtdMaxProduto = (int) SYS01PARAMETROSGLOBAIS::getValor('3');
            $qtdProduto = CB05PRODUTO::find()->where(['CB05_EMPRESA_ID' => $this->user->id_company])->count();
            if ($qtdMaxProduto <= $qtdProduto) {
                $maxProduto = "Você atingiu o limite de produtos do sistema.";
            }
        }

        // Avaliacoes ativas cadastradas pela empresa
        $avaliacoes = CB06VARIACAO::findCombo('CB19_AVALIACAO', 'CB19_ID', 'CB19_NOME', 'CB19_STATUS=1 AND CB19_EMPRESA_ID=' . $this->user->id_company);

        return $this->render('produtoFullForm', [
                    'tituloTela' => 'Produto',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'itemProduto' => $dataItemProduto,
                    'limitFotos' => $limitFotos,
                    'al' => $al,
                    'avaliacoes' => $avaliacoes,
                   // 'maxProduto' => $maxProduto,
        		   	'maxProduto' => 0,
        ]);
    }

    public function fotoProduto() {
        $getAction = Yii::$app->request->get('param');
        $produto = Yii::$app->request->get('produto');
        if($produto){
            
            // salva imagem
            if ($getAction == 'save') {

                // testa quantidade de fotos
                $limitFotos = SYS01PARAMETROSGLOBAIS::getValor(6); // limit de fotos do produto
                $qtdFotos = CB14FOTOPRODUTO::find()->where(['CB14_PRODUTO_ID' => $produto])->count();
                if($limitFotos <= $qtdFotos) {
                    throw new \Exception('Limite de fotos atingido para o produto!');
                }

                $infoFile = \Yii::$app->u->infoFile($_FILES['file']);
                $infoFile['path'] = 'img/fotos/produto/';
                $infoFile['newName'] = uniqid($produto."_") . '.' . $infoFile['ex'];

                $CB14FOTOPRODUTO = new CB14FOTOPRODUTO();
                $CB14FOTOPRODUTO->setAttributes([
                    'CB14_PRODUTO_ID' => $produto,
                    'CB14_URL' => $infoFile['path'] . $infoFile['newName']
                ]);
                $CB14FOTOPRODUTO->save();

                $file = \yii\web\UploadedFile::getInstanceByName('file');
                $file->saveAs($infoFile['path'] . $infoFile['newName']);

            // deleta imagem
            } else if ($getAction == 'delete') {
                $foto = Yii::$app->request->get('foto');
                if ($foto) {
                    $modelFoto = CB14FOTOPRODUTO::findOne(['CB14_ID' => $foto, 'CB14_PRODUTO_ID' => $produto]);
                    if ($modelFoto) {
                        $modelFoto->delete();
                        @unlink($modelFoto->CB14_URL);
                    }
                }
            } else if ($getAction == 'read') {
                $dataFotos = CB04EMPRESA::findCombo('CB14_FOTO_PRODUTO', 'CB14_ID', 'CB14_URL', 'CB14_PRODUTO_ID=' . $produto);
                throw new \Exception(json_encode($dataFotos));
            }
        }
    }
    
    public function saveProduto($param) {
        $param['CB05_EMPRESA_ID'] = $this->user->id_company;
        $modelId = CB05PRODUTO::primaryKey()[0];
        $CB05PRODUTO = (empty($param[$modelId])) ? new CB05PRODUTO() : CB05PRODUTO::findOne($param[$modelId]);
        $CB05PRODUTO->saveProduto($param);
    }
    
    
    public function createProdutoFull($param) {
        
        $param['CB05_EMPRESA_ID'] = $this->user->id_company;
        $transaction = \Yii::$app->db->beginTransaction();
        
        try {

            // cria produto
            $CB05PRODUTO = new CB05PRODUTO();
            $CB05PRODUTO->setAttributes($param);
            $CB05PRODUTO->save();
            $produto = $CB05PRODUTO->CB05_ID;

            // cria os itens do produto
            foreach ($param['ITEM-PRODUTO'] as $item) {
                $CB12ITEMCATEGEMPRESA = new CB12ITEMCATEGEMPRESA();
                $CB12ITEMCATEGEMPRESA->setAttributes(['CB12_PRODUTO_ID' => $produto, 'CB12_ITEM_ID' => $item]);
                $CB12ITEMCATEGEMPRESA->save();
            }
            
            // salva promocao
            $param['CB06_ID'] = null;
            $param['CB06_PRODUTO_ID'] = $produto;
            $this->savePromocao($param);
            
            // fotos do produto
            if($_FILES){
                
                // testa quantidade de fotos
                $limitFotos = (int) SYS01PARAMETROSGLOBAIS::getValor(6); // limit de fotos do produto
                $qtdFotos = 0;

                foreach ($_FILES as $foto) {
                    $qtdFotos++;
                    if($limitFotos < $qtdFotos) {
                        break;
//                        $transaction->rollBack();
//                        throw new \Exception('Limite de fotos atingido para o produto!');
                    }

                    $infoFile = \Yii::$app->u->infoFile($foto);
                    $infoFile['path'] = 'img/fotos/produto/';
                    $infoFile['newName'] = uniqid($produto."_") . '.' . $infoFile['ex'];

                    $CB14FOTOPRODUTO = new CB14FOTOPRODUTO();
                    $CB14FOTOPRODUTO->setAttributes([
                       'CB14_PRODUTO_ID' => $produto,
                       'CB14_URL' => $infoFile['path'] . $infoFile['newName']
                    ]);
                    $CB14FOTOPRODUTO->save();

                    $file = \yii\web\UploadedFile::getInstanceByName(str_replace('.', '_', $infoFile['name']));
                    $file->saveAs($infoFile['path'] . $infoFile['newName']);
                    
                }
            }
            
            $transaction->commit();
            return json_encode(['status' => true]);

        } catch (\Exception $exc) {
            $transaction->rollBack();
            throw new \Exception($exc->getMessage());
        }
    }

    public function deleteProduto($produto) {
        //CB07CASHBACK::deleteAll(['CB07_PRODUTO_ID' => $produto]);
        //CB06VARIACAO::deleteAll(['CB06_PRODUTO_ID' => $produto]);
        //CB12ITEMCATEGEMPRESA::deleteAll(['CB06_PRODUTO_ID' => $produto]);
        CB05PRODUTO::deleteAll(['CB05_ID' => $produto]);
    }

    public function actionPromocaoForm($produto, $promocao) {
        \Yii::$app->view->title = '';
        $this->layout = 'empty';

        $model = new CB06VARIACAO();
        $al = $model->attributeLabels();

        $qtdMaxPromocao = (int) SYS01PARAMETROSGLOBAIS::getValor('4');
        $qtdPromocao = CB06VARIACAO::find()->where(['CB06_PRODUTO_ID' => $produto])->count();
        $maxPromocao = ($qtdMaxPromocao <= $qtdPromocao) ? "Você atingiu o limite de promoções por produto." : "";

        // dados da promocao quando edicao
        $attrPromocao = '';
        if($promocao) {
            $promocao = CB06VARIACAO::findOne($promocao);
            $attrPromocao = (!empty($promocao->attributes) ? $promocao->attributes : '');
        }
        
        // Avaliacoes ativas cadastradas pela empresa
        $avaliacoes = CB06VARIACAO::findCombo('CB19_AVALIACAO', 'CB19_ID', 'CB19_NOME', 'CB19_STATUS=1 AND CB19_EMPRESA_ID=' . $this->user->id_company);

        return $this->render('promocaoForm', [
                    'tituloTela' => 'Promoção',
                    'usuario' => $this->user->attributes,
                    'produto' => ['CB06_PRODUTO_ID' => $produto],
                    'promocao' => $attrPromocao,
                    'avaliacoes' => $avaliacoes,
                    'al' => $al,
                    'estabelecimento' => $this->estabelecimento,
                    'maxPromocao' => $maxPromocao,
                    'semProduto' => !($produto)
        ]);
    }

    public function savePromocao($param) {
        $CB06VARIACAO = ($param['CB06_ID']) ?  CB06VARIACAO::findOne($param['CB06_ID']) : new CB06VARIACAO();
        if ($this->estabelecimento['CB04_FLG_DELIVERY']) {
            $CB06VARIACAO->scenario = CB06VARIACAO::SCENARIODELIVERY;
        }
        $CB06VARIACAO->setAttributes($param);
        $CB06VARIACAO->save();
    }

    /*
     * Excluir o cashback e a variacao
     */

    public function deletePromocao($promocao) {
        CB07CASHBACK::deleteAll(['CB07_VARIACAO_ID' => $promocao]);
        CB06VARIACAO::deleteAll(['CB06_ID' => $promocao]);
    }

    public function actionCashbackForm($produto) {
        \Yii::$app->view->title = '';
        $this->layout = 'empty';

        $dataProduto = CB05PRODUTO::findOne($produto)->getAttributes();
        $dataVariacao = CB04EMPRESA::findCombo('CB06_VARIACAO', 'CB06_ID', 'CB06_DESCRICAO', 'CB06_PRODUTO_ID=' . $produto);
        
        $dataProduto['CB05_DESCRICAO'] = str_replace("\n", '\r\n', $dataProduto['CB05_DESCRICAO']);
        $dataProduto['CB05_IMPORTANTE'] = str_replace("\n", '\r\n', $dataProduto['CB05_IMPORTANTE']);

        return $this->render('cashbackForm', [
                    'tituloTela' => 'CASHBACK',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'variacao' => $dataVariacao,
                    'empresa' => $this->estabelecimento,                    
        ]);
    }
    public function actionCashbackDiarioForm($produto) {
        \Yii::$app->view->title = '';
        $this->layout = 'empty';

        $dataProduto = CB05PRODUTO::findOne($produto)->getAttributes();
        $dataVariacao = CB04EMPRESA::findCombo('CB06_VARIACAO', 'CB06_ID', 'CB06_DESCRICAO', 'CB06_PRODUTO_ID=' . $produto);
        $dataCbEmpresa = CB07CASHBACK::getCashbackDiario(\Yii::$app->user->identity->id_company);
        
        $dataProduto['CB05_DESCRICAO'] = str_replace("\n", '\r\n', $dataProduto['CB05_DESCRICAO']);
        $dataProduto['CB05_IMPORTANTE'] = str_replace("\n", '\r\n', $dataProduto['CB05_IMPORTANTE']);

        return $this->render('cashbackDiarioForm', [
                    'tituloTela' => 'CASHBACK',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'variacao' => $dataVariacao,
                    'empresa' => $this->estabelecimento,
                    'cashback' => json_encode($dataCbEmpresa[0]),
        ]);
    }

    public function actionCashbackGrid($produto) {
        \Yii::$app->view->title = '';
        $this->layout = 'empty';
        $dataCashback = CB07CASHBACK::getCashback($produto);
        return $this->render('cashbackGrid', ['cashback' => $dataCashback]);
    }

    public function saveCashback($param) {
        $CB07CASHBACK = new CB07CASHBACK();
        $CB07CASHBACK->saveCashbackDiario($param);
    }

    public function deleteCashback($param) {
        $CB07CASHBACK = new CB07CASHBACK();
        $CB07CASHBACK->deleteCashback($param);
    }
    
    public function actionDelivery() {
        return $this->render('delivery', [
                    'tituloTela' => 'Delivery',
                    'usuario' => $this->user->attributes,
        ]);
    }
    
    public function actionDeliveryGrid() {
        $model = new CB16PEDIDO();
        $pedidos = $model->getPedidoDelivery($this->user->id_company);
        if ($pedidos) {
            $param = ['pedidos' => $pedidos, 'status' => $model->status_delivery];
        } else {
            $param = ['error' => 'Nenhum registro encontrado.'];
        }
        return $this->renderPartial('deliveryGrid', $param);
    }
    
    
    public function actionDeliveryDx() {
        $model = new CB16PEDIDO();
        echo $this->renderFile('@app/web/libs/C7.1.0.0.js.php');
        echo $this->renderFile('@app/views/estabelecimento/deliveryDxInit.php');
        return $this->render('deliveryDx', [
                    'tituloTela' => 'Delivery',
                    'al' => $model->attributeLabels(),
                    'status' => $model->status_delivery,
                    'usuario' => $this->user->attributes,
        ]);
    }

    // altera status da entrega
    public function setStatusDelivery($param) {
        $CB16PEDIDO = CB16PEDIDO::find()->where("CB16_ID = ".$param['pedido']." and CB16_STATUS >= 30")->all()[0];
        if ($CB16PEDIDO) {
            $CB16PEDIDO->setAttribute('CB16_STATUS_DELIVERY', $param['new_status']);
            $CB16PEDIDO->save();     
        }
    }
    
    
    public function actionBaixarCompra() {
        $salvo = null;

        $model = new CB05PRODUTO();
        $al = $model->attributeLabels();

        $dataProduto = $model->getProdutoVariacao($this->user->id_company);
        return $this->render('baixarCompra', [
                    'tituloTela' => 'Baixar Compra',
                    'usuario' => $this->user->attributes,
                    'produto' => $dataProduto,
                    'al' => $al,
                    'salvo' => $salvo
        ]);
    }
    
    public function actionBaixarCompraGrid() {
        $cpf = (string) Yii::$app->request->get('cpf');
        if($cpf){
            $cpfFormatado = preg_replace('/[^0-9]/', '', $cpf);
            $model = new CB16PEDIDO();
            $pedidos = $model->getPedidoByCPF($cpfFormatado, $this->user->id_company);
            if ($pedidos) {
                $param = ['pedidos' => $pedidos, 'status' => $model->status_pedido, 'cpf' => $cpf];
            } else {
                $param = ['error' => 'Nenhum registro encontrado para o CPF informado: <strong>' . $cpf . '</strong>'];
            }
            return $this->renderPartial('baixarCompraGrid', $param);
        }
    }
    
    // permite baixar apenas os pedidos pagos
    public function saveBaixaCompra($param) {        
        $CB16PEDIDO = CB16PEDIDO::find()->where("CB16_ID = ".$param['pedido']." and CB16_STATUS >= 30")->all()[0];
        if ($CB16PEDIDO) {
            $CB16PEDIDO->setAttribute('CB16_STATUS', 20);
            $CB16PEDIDO->save();     
        }
    }

    
    public function actionExtrato() {
//        var_dump($this);
//        exit();
        $model = new VIEWEXTRATO();
        $al = $model->attributeLabels();
        $saldoAtual = $model->saldoAtualByCliente($this->user->id);
        $saldoReceber = $model->saldoReceberByCliente($this->user->id);

        return $this->render('extrato', [
                    'tituloTela' => 'Extrato',
                    'usuario' => $this->user->attributes,
                    'al' => $al,
                    'saldoAtual' => Yii::$app->u->moedaReal($saldoAtual),
                    'saldoReceber' => Yii::$app->u->moedaReal($saldoReceber)
        ]);
    }
    
    public function actionExtratoGrid() {
            
        $model = new VIEWEXTRATO();
        $extrato = $model->find()->where(['USER' => $this->user->id])->asArray()->all();

        if ($extrato) {
            $param = ['extrato' => $extrato, 'tipo' => $model->tipos_para_estabelecimento];
        } else {
            $param = ['error' => 'Nenhuma transação foi realizada.'];
        }

        return $this->renderPartial('extratoGrid', $param);
    }
    
    public function actionExtratoDx() {
        
        $model = new EstabelecimentoExtratoModel();
        $al = $model->attributeLabels();
        $saldoAtual = $model->saldoAtual();
        $saldoReceber = $model->saldoPendente();

        echo $this->renderFile('@app/web/libs/C7.1.0.0.js.php');
        echo $this->renderFile('@app/views/estabelecimento/extratoDxInit.php');
        return $this->render('extratoDx', [
                    'tituloTela' => 'Extrato',
                    'usuario' => $this->user->attributes,
                    'al' => $al,
                    'saldoAtual' => Yii::$app->u->moedaReal($saldoAtual),
                    'saldoReceber' => Yii::$app->u->moedaReal($saldoReceber)
        ]);
    }
    
    
    public function actionSaque() {
            
        $saque_realizado = false;
        $formData = \Yii::$app->request->post();
        $idUser = $this->user->id;
        
        $VIEWEXTRATO = new VIEWEXTRATO();
        $saldoAtual = $VIEWEXTRATO->saldoAtualByCliente($idUser);

        $saqueMax = (float) $saldoAtual;
        $saqueMin = (float) 1;

        if(!($dadosSaque = CB03CONTABANC::findOne(['CB03_USER_ID' => $idUser]))) {        
            return $this->render('saque', ['sem_conta' => "Conta bancária não cadastrada, entre em contato com o suporte para cadastra-la."]);
            
        } else {
        
            $dadosSaque->setAttribute('CB03_VALOR', '');

            $dadosSaque->scenario = 'saque';

            if (!$formData) {
                $dadosSaque->setAttribute('CB03_USER_ID', $idUser);
                $dadosSaque->setAttribute('CB03_SAQUE_MIN', $saqueMin);
                $dadosSaque->setAttribute('CB03_SAQUE_MAX', $saqueMax);

            } else {
                $dadosSaque->setAttributes($formData);
                $dadosSaque->setAttribute('CB03_USER_ID', $idUser);
                $dadosSaque->setAttribute('CB03_SAQUE_MIN', $saqueMin);
                $dadosSaque->setAttribute('CB03_SAQUE_MAX', $saqueMax);

                if ($dadosSaque->validate()) {

                    $transaction = \Yii::$app->db->beginTransaction();

                    try {

                        $dadosSaque->save(false);
                        
                        $PAG04TRANSFERENCIAS = new PAG04TRANSFERENCIAS();
                        $PAG04TRANSFERENCIAS->setAttributes([
                            'PAG04_ID_USER_CONTA_ORIGEM' => $idUser,
                            'PAG04_ID_USER_CONTA_DESTINO' => $idUser,
                            'PAG04_DT_PREV' => date('Y-m-d', strtotime("+" . SYS01PARAMETROSGLOBAIS::getValor('PO_SQ') ." days", strtotime(date('Y-m-d')))),
                            'PAG04_VLR' => $dadosSaque->CB03_VALOR,
                            'PAG04_TIPO' => 'V2B',
                        ]);
                        $PAG04TRANSFERENCIAS->save();

                        $transaction->commit();
                        return json_encode(['saque_realizado' => true]);

                    } catch (\Exception $exc) {
                        $transaction->rollBack();
                    }

                }
                
                exit(json_encode(['error' => $dadosSaque->getErrors()]));
                
            }
        }
        
        // formata valores para moeda REAL
        $dadosSaque->setAttribute('CB03_VALOR', (string) \Yii::$app->u->moedaReal($dadosSaque->attributes['CB03_VALOR']));
        $dadosSaque->setAttribute('CB03_SAQUE_MAX', (string) \Yii::$app->u->moedaReal($dadosSaque->attributes['CB03_SAQUE_MAX']));

        return $this->render('saque', [
            'saque_realizado' => $saque_realizado,
            'dados_saque' => $dadosSaque->getAttributes(),
        ]);
    }

    
    public function actionAvaliacao() {
        
        $model = new CB19AVALIACAO();
        $al = $model->attributeLabels();
        
        // avaliacoes da empresa
        $avaliacoes = $model->avaliacaoEmpresa($this->user->id_company);
        
        // itens avaliados
        $itensAvaliados = CB21RESPOSTAAVALIACAO::getNotaPercentualItemByEmpresa($this->user->id_company);
        
        // Comentarios
        $comentarios = CB22COMENTARIOAVALIACAO::getComentariosByEmpresa($this->user->id_company);
        
        return $this->render('avaliacao', [
                    'tituloTela' => 'Avaliação',
                    'usuario' => $this->user->attributes,
                    'al' => $al,
                    'avaliacoes' => $avaliacoes,
                    'itensAvaliados' => $itensAvaliados,
                    'comentarios' => $comentarios
        ]);
    }
    

    public function actionAvaliacaoForm() {
        \Yii::$app->view->title = '';
        $this->layout = 'empty';

        $dataAvaliacao = false;
        $itensSelecionados = false;
        
        $model = new CB19AVALIACAO();
        $alAvaliacao = $model->attributeLabels();
        
        // itens pela categoria do estabelecimento
        $itens = CB23TIPOAVALIACAO::findCombo('CB23_TIPO_AVALIACAO', 'CB23_ID', 'CB23_DESCRICAO', 'CB23_STATUS = 1 AND CB23_CATEGORIA_ID = ' . $this->estabelecimento['CB04_CATEGORIA_ID']);

        // edicao da avaliacao
        if($avaliacao = Yii::$app->request->get('avaliacao')) {
            
            // dados da avaliacao
            $dataAvaliacao = CB19AVALIACAO::findOne($avaliacao)->getAttributes();
            
            // itens da selecionados na avaliacao ativos
            if($selecionados = CB20ITEMAVALIACAO::find()->select('CB20_TIPO_AVALICAO_ID')->where('CB20_STATUS = 1 AND CB20_AVALIACAO_ID=' . $avaliacao)->asArray()->all()){
                $itensSelecionados = [];
                foreach ($selecionados as $value) {
                    $itensSelecionados[] = $value['CB20_TIPO_AVALICAO_ID'];
                }
            }
        }
        
        return $this->render('avaliacaoForm', [
                    'alAvaliacao' => $alAvaliacao,
                    'itens' => $itens,
                    'dataAvaliacao' => $dataAvaliacao,
                    'itensSelecionados' => $itensSelecionados
        ]);
    }

    // permite baixar apenas os pedidos pagos
    public function saveAvaliacao($param) {
        
        $transaction = \Yii::$app->db->beginTransaction();
        
        try {

            // CADASTRO --------------------------------------------------------
            if(!($CB19_ID = $param['CB19_ID'])) {
                
                // salva a avaliacao
                $CB19AVALIACAO = new CB19AVALIACAO();
                $CB19AVALIACAO->setAttribute('CB19_EMPRESA_ID', $this->user->id_company);
                $CB19AVALIACAO->setAttribute('CB19_NOME', $param['CB19_NOME']);
                $CB19AVALIACAO->setAttribute('CB19_STATUS', 1);
                $CB19AVALIACAO->save();
                $CB19_ID = $CB19AVALIACAO->CB19_ID;

                // salva os itens da avaliação - pode nao ter itens, só exibe um campo de texto para avaliar
                if (!empty($param['AVALIACAO-ITENS'])) {
                    foreach ($param['AVALIACAO-ITENS'] as $item) {

                        $CB20ITEMAVALIACAO = new CB20ITEMAVALIACAO();
                        $CB20ITEMAVALIACAO->setAttribute('CB20_AVALIACAO_ID', $CB19_ID);
                        $CB20ITEMAVALIACAO->setAttribute('CB20_TIPO_AVALICAO_ID', $item);
                        $CB20ITEMAVALIACAO->setAttribute('CB20_STATUS', 1);
                        $CB20ITEMAVALIACAO->save();

                    }
                }

            // EDICAO ----------------------------------------------------------
            } else {
                
                // salva a avaliacao
                $CB19AVALIACAO = CB19AVALIACAO::findOne($CB19_ID);
                $CB19AVALIACAO->setAttribute('CB19_EMPRESA_ID', $this->user->id_company);
                $CB19AVALIACAO->setAttribute('CB19_NOME', $param['CB19_NOME']);
                $CB19AVALIACAO->setAttribute('CB19_STATUS', 1);
                $CB19AVALIACAO->save();
                
                // desativa todos os itens antes de add/ativar
                CB20ITEMAVALIACAO::updateAll(['CB20_STATUS' => 0], 'CB20_AVALIACAO_ID = ' . $CB19_ID);

                // salva os itens da avaliação - pode nao ter itens, só exibe um campo de texto para avaliar
                if (!empty($param['AVALIACAO-ITENS'])) {
                    foreach ($param['AVALIACAO-ITENS'] as $item) {

                        // verifica se o item existe desativado e ativa / se nao cria
                        if(!$CB20ITEMAVALIACAO = CB20ITEMAVALIACAO::find()->where("CB20_AVALIACAO_ID = $CB19_ID AND CB20_TIPO_AVALICAO_ID = $item")->one()) {
                            $CB20ITEMAVALIACAO = new CB20ITEMAVALIACAO();
                            $CB20ITEMAVALIACAO->setAttribute('CB20_AVALIACAO_ID', $CB19_ID);
                            $CB20ITEMAVALIACAO->setAttribute('CB20_TIPO_AVALICAO_ID', $item);
                        }
                        
                        $CB20ITEMAVALIACAO->setAttribute('CB20_STATUS', 1);
                        $CB20ITEMAVALIACAO->save();

                    }
                }
                
            }

            $transaction->commit();
            return json_encode(['status' => true]);

        } catch (\Exception $exc) {
            $transaction->rollBack();
            return json_encode(['status' => false]);
        }
        
    }
    
    // ativar/desativar avaliação
    public function actionAvaliacaoAtivar($avaliacao, $status) {
        $CB19AVALIACAO = CB19AVALIACAO::findOne($avaliacao);
        $CB19AVALIACAO->setAttribute('CB19_STATUS', $status);
        return ($CB19AVALIACAO->save()) ? '' : 'error';
    }
    
    
    /**
     * view Alterar senha 
     */
    public function actionAlterarSenha() {
        return $this->render('alterarSenha');
    }
    
    /**
     * action Alterar senha 
     */
    public function alterarSenha() {
        $post = \Yii::$app->request->post();
        $retorno = [];
        
        // valida senha
        if($post) {
            
            $current_password = $post['current-password'];
            $new_password = $post['new-password'];
            $auth_key = $this->user->auth_key;

            if (\Yii::$app->security->validatePassword($current_password, User::getHashPasswordByAuthKey($auth_key))) {
                $new_password_hash = \Yii::$app->security->generatePasswordHash($new_password);
                $user = User::findOne(['auth_key' => $auth_key]);
                $user->setAttribute('password_hash', $new_password_hash);
                $user->setAttribute('password_reset_token', NULL);
                if ($user->save()) {
                    $retorno = ['status' => true, 'message' => 'A senha foi alterada com sucesso!'];
                } else {
                    $retorno = ['status' => false, 'message' => 'A senha não foi alterada, tente novamente!'];
                }

            } else {
                $retorno = ['status' => false, 'message' => 'A senha atual esta incorreta!'];

            }
        }
        
        exit(json_encode($retorno));
    }
    
    
    /*
     * FUNCIONARIO
     */

    public function actionFuncionario()
    {
        $model = new VIEWFUNCIONARIO();
        echo $this->renderFile('@app/web/libs/C7.1.0.0.js.php');
        echo $this->renderFile('@app/views/estabelecimento/funcionarioDxInit.php');
        return $this->render('funcionario', [
                    'tituloTela' => 'Funcionário',
                    'empresa' => $this->user->id_company,
                    'al' => $model->attributeLabels()
        ]);
    }
    
    public function actionFuncionarioForm()
    {
        $this->layout = 'empty';

        $model = new VIEWFUNCIONARIO();
        $al = $model->attributeLabels();
        $dataFuncionario = [];

        if (($funcionario = Yii::$app->request->get('funcionario'))) {
            if (($dataFuncionario = $model->findOne($funcionario))) {
                $dataFuncionario = $dataFuncionario->getAttributes();
                unset($dataFuncionario['CB04_DADOS_API_TOKEN']);
                $dataFuncionario['CB04_OBSERVACAO'] = str_replace("\r\n", '\r\n', $dataFuncionario['CB04_OBSERVACAO']);
                $dataContaBancaria = CB03CONTABANC::getContaBancariaFuncionario($dataFuncionario['CB04_ID']);
                $dataFuncionario = array_merge($dataFuncionario, ($dataContaBancaria ? : []));
            }
        }

        return $this->render('funcionarioForm', [
                    'tituloTela' => 'Funcionário',
                    'usuario' => $this->user->attributes,
                    'funcionario' => $dataFuncionario,
                    'al' => $al,
        ]);
    }

    private function saveContaBancaria($param)
    {	 
        $conta = (empty($param['CB03_ID'])) ? new CB03CONTABANC() : CB03CONTABANC::findOne($param['CB03_ID']);
        if ($conta->isNewRecord) {
            $param['CB03_USER_ID'] = \Yii::$app->user->identity->id;
        }
        $conta->setAttributes($param);
        $conta->save();
        return $conta->CB03_ID;
    }
    
    public function saveFuncionario($param)
    {
        try {
            \Yii::$app->Iugu->transaction = \Yii::$app->db->beginTransaction();
            $model = (!$param['CB04_ID']) ? new CB04EMPRESA() : CB04EMPRESA::findOne($param['CB04_ID']);
            $param['CB04_ID_EMPRESA'] = $this->user->id_company;
            $new = $model->isNewRecord;
            $id = $model->saveFuncionario($param);
            $param['CB03_ID'] = $this->saveContaBancaria($param);
            
            if ($new) {
                \Yii::$app->Iugu->execute('createFuncionarioAccount', ['data' => $param, 'id' => $id]);
            } else {
                \Yii::$app->Iugu->transaction->commit();
            }
        } catch (\Exception $ex) {
            \Yii::$app->Iugu->transaction->rollBack();
            $this->throwError($ex->getMessage());
        }
    }

    public function actionFuncionarioAtivar($funcionario, $status) 
    {
        $dbTransaction = \Yii::$app->db->beginTransaction();
        
        // desativa a empresa/funcionario
        $model = VIEWFUNCIONARIO::findOne($funcionario);
        $model->setAttribute('CB04_STATUS', $status);

        // desativa o usuario
        $user = User::findOne(["id_company" => $model->CB04_ID]);
        $user->setAttribute('status', ($status ? User::STATUS_ACTIVE : User::STATUS_DELETED));
       
        if ($model->save() && $user->save()) {
            $dbTransaction->commit();
            $return = '';
        } else {
            $dbTransaction->rollBack();
            $return = 'error'; 
        }
        return $return;
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
            case 'ExtratoMain':
                $this->relatedModel = "common\models\EstabelecimentoExtratoModel";
            break;
            case 'DeliveryMain':
                $this->relatedModel = "common\models\CB16PEDIDO";
            break;
            case 'FuncionariosMain':
                $this->relatedModel = "common\models\VIEWFUNCIONARIO";
            break;
        }
        parent::actionGlobalRead($gridName, $param, $json);
    }
    
}
