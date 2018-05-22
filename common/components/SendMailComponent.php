<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\SYS01PARAMETROSGLOBAIS;

class SendMailComponent extends Component {

    private $email;

    public function __construct($config = []) {

        // configuração do servidor de e-mail
        $configTransport = json_decode(SYS01PARAMETROSGLOBAIS::getValor('MAIL_CO'), true);

        // instancia do email
        $this->email = \Yii::$app->mail;
        $this->email->htmlLayout = 'layouts/html';
        $this->email->setTransport($configTransport);

        parent::__construct($config);
    }

    private function sendMail($sendMail, $setFrom = 'nao-responda@estalecas.com.br') 
    {
        try {
           $sendMail->setFrom($setFrom)->send();
           $msg = "E-mail enviado com sucesso";
           $status = true;

        } catch (\Exception $ex) {
            $msg = $ex->getMessage();
            $status = false;

        }
        return ['status' => $status, 'msg' => $msg];
    }
    
    // \Yii::$app->sendMail->enviarEmailTeste('seuemail@');
    public function enviarEmailTeste($email)
    {
//        $sendMail = $this->email->compose(['layout' => 'layouts/html', 'html' => 'teste'])
        $sendMail = $this->email->compose('teste', [])
                                ->setTo($email)
                                ->setSubject('E$TALECAS - Teste param E-mail');

        return $this->sendMail($sendMail);
    }

    public function enviarEmailCadastro($email, $authKey)
    {
        $sendMail = $this->email->compose('confirmacaoemail', ['authKey' => $authKey])
                                ->setTo($email)
                                ->setSubject('E$TALECAS - Confirmação de E-mail');

        return $this->sendMail($sendMail);
    }
    
    public function enviarEmailNovaSenha($email, $senha)
    {
        $sendMail = $this->email->compose('novasenha', ['senha' => $senha])
                                ->setTo($email)
                                ->setSubject('E$TALECAS - Esqueceu a senha?');

        return $this->sendMail($sendMail);
    }
    
    public function enviarEmailCreateRevendedor($email, $dados)
    {
        $sendMail = $this->email->compose('createrevendedor', $dados)
                                ->setTo($email)
                                ->setSubject('E$TALECAS - Seja bem vindo!');

        return $this->sendMail($sendMail);
    }
    
    public function enviarEmailCreateFuncionario($email, $dados)
    {
        $sendMail = $this->email->compose('createfuncionario', $dados)
                                ->setTo($email)
                                ->setSubject('E$TALECAS - Seja bem vindo!');

        return $this->sendMail($sendMail);
    }
}
