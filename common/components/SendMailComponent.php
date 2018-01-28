<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\SYS01PARAMETROSGLOBAIS;

class SendMailComponent extends Component {
    
    public function enviarEmailCadastro($email, $authKey)
    {
        //$link = $this->urlController . 'valid-mail&c='. $post['auth_key'];
        //$texto = SYS01PARAMETROSGLOBAIS::getValor('TX_MAIL') . "<br />" . $link;
        
      $teste =  \Yii::$app->mail->compose('confirmacaoemail',['authKey' => $authKey])
        ->setFrom('nao-responda@estalecas.com.br')
        ->setTo($email)
        ->setSubject('E$TALECAS - Confirmação de E-mail')
        ->send();
      $a=1;
    }
    
    public function enviarEmailNovaSenha($email, $senha)
    {
        //$link = $this->urlController . 'valid-mail&c='. $post['auth_key'];
        //$texto = SYS01PARAMETROSGLOBAIS::getValor('TX_MAIL') . "<br />" . $link;
        
        \Yii::$app->mail->compose('novasenha',['senha' => $senha])
        ->setFrom('nao-responda@estalecas.com.br')
        ->setTo($email)
        ->setSubject('E$TALECAS - Esqueceu a senha?')
        ->send();
    }
    
    public function enviarEmailCreateRevendedor($email, $dados)
    {
        \Yii::$app->mail->compose('createrevendedor', $dados)
        ->setFrom('nao-responda@estalecas.com.br')
        ->setTo($email)
        ->setSubject('E$TALECAS - Seja bem vindo!')
        ->send();
    }
}
