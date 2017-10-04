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
use common\models\CB03CONTABANC;
use common\models\PAG04TRANSFERENCIAS;

/**
 * Estabelecimento controller
 */
class EstabelecimentoController extends \common\controllers\GlobalBaseController {

    private $user = null;
    private $estabelecimento = null;

    public function __construct($id, $module, $config = []) 
    {
        if (($identity = \Yii::$app->user->identity)) {
            $this->user = $identity;
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
            $this->redirect(\yii\helpers\Url::to('index.php?r=estabelecimento/produto'));
            return;
        }

        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIOESTABELECIMENTO;

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
        $this->layout = 'smartAdminEstabelecimento';
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
        $this->layout = 'smartAdminEstabelecimento';
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

    public function fotoProduto() {
        $getAction = Yii::$app->request->get('param');
        $produto = Yii::$app->request->get('produto');
        if($produto){
            
            // salva imagem
            if ($getAction == 'save') {

                // testa quantidade de fotos
                $limitFotos = SYS01PARAMETROSGLOBAIS::getValor(6); // limit de fotos do produto
                $qtdFotos = CB14FOTOPRODUTO::find()->where(['CB14_PRODUTO_ID' => $produto])->count();
               /* if($limitFotos <= $qtdFotos) {
                    throw new \Exception('Limite de fotos atingido para o produto!');
                }*/

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

    public function deleteProduto($produto) {
        //CB07CASHBACK::deleteAll(['CB07_PRODUTO_ID' => $produto]);
        //CB06VARIACAO::deleteAll(['CB06_PRODUTO_ID' => $produto]);
        //CB12ITEMCATEGEMPRESA::deleteAll(['CB06_PRODUTO_ID' => $produto]);
        CB05PRODUTO::deleteAll(['CB05_ID' => $produto]);
    }

    public function actionPromocaoForm($produto) {
        \Yii::$app->view->title = '';
        $this->layout = 'empty';

        $model = new CB06VARIACAO();
        $al = $model->attributeLabels();

        $qtdMaxPromocao = (int) SYS01PARAMETROSGLOBAIS::getValor('4');
        $qtdPromocao = CB06VARIACAO::find()->where(['CB06_PRODUTO_ID' => $produto])->count();
        $maxPromocao = ($qtdMaxPromocao <= $qtdPromocao) ? "Você atingiu o limite de promoções por produto." : "";

        return $this->render('promocaoForm', [
                    'tituloTela' => 'Promoção',
                    'usuario' => $this->user->attributes,
                    'produto' => ['CB06_PRODUTO_ID' => $produto],
                    'al' => $al,
                    'estabelecimento' => $this->estabelecimento,
                    'maxPromocao' => $maxPromocao,
        ]);
    }

    public function savePromocao($param) {
        $CB06VARIACAO = new CB06VARIACAO();
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
                    'variacao' => $dataVariacao
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
        $CB07CASHBACK->saveCashback($param);
    }

    public function deleteCashback($param) {
        $CB07CASHBACK = new CB07CASHBACK();
        $CB07CASHBACK->deleteCashback($param);
    }
    
    public function actionDelivery() {
        $this->layout = 'smartAdminEstabelecimento';
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
    
    // altera status da entrega
    public function setStatusDelivery($param) {
        $CB16PEDIDO = CB16PEDIDO::find()->where(['CB16_ID' => $param['pedido']])->andWhere('>','CB16_STATUS', 30)->all()[0];
        if ($CB16PEDIDO) {
            $CB16PEDIDO->setAttribute('CB16_STATUS_DELIVERY', $param['new_status']);
            $CB16PEDIDO->save();     
        }
    }
    
    public function actionBaixarCompra() {
        $this->layout = 'smartAdminEstabelecimento';
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
        $CB16PEDIDO = CB16PEDIDO::findOne(['CB16_ID' => $param['pedido'], 'CB16_STATUS' => 30]);
        if ($CB16PEDIDO) {
            $CB16PEDIDO->setAttribute('CB16_STATUS', 20);
            $CB16PEDIDO->save();     
        }
    }

    
    public function actionExtrato() {
        $this->layout = 'smartAdminEstabelecimento';
        
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
    
    public function actionSaque() {
        $this->layout = 'smartAdminEstabelecimento';
            
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
        $this->layout = 'smartAdminEstabelecimento';
        
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
        // nova avaliacao
        } else {
            $dataAvaliacao = false;
            $itensSelecionados = false;
            
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
                if ($param['AVALIACAO-ITENS']) {
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
                if ($param['AVALIACAO-ITENS']) {
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
    
    
}
