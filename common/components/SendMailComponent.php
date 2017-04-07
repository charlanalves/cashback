<?php

namespace app\components;

use Yii;
use yii\base\Component;

class SendMailComponent extends Component {

    private $smtp = array("SMTP"=>"146.47.242.5", "smtp_port"=>"25"/*"auth_username"=>"", "auth_password"=>""*/);

    public $de;
    public $para;
    public $copia;
    public $copia_oculta;
    public $assunto;
    public $mensagem;
    public $anexo;
    public $cc;
    public $cco;

    public function send()
    {
        foreach($this->smtp as $key=>$val)
            ini_set($key, $val);

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From:" . $this->de . "\r\n";
        if($this->anexo!=""){

            $boundary = strtotime('NOW');
            $headers .= "Content-Type: multipart/mixed;boundary=\"" . $boundary . "\"\n";
            $msg = "--" . $boundary . "\n";
            $msg .= "Content-Type: text/html;charset=\"utf-8\"\n";
            $msg .= "Content-Transfer-Encoding: 8bits\n\n"; //quoted-printable
            $msg .= "$this->mensagem\n";

            $msg .= "--" . $boundary . "\n";

            $msg .= "Content-Transfer-Encoding: base64\n";
            $msg .= "Content-Disposition: attachment;filename=\"$this->anexo\"\n\n";
            ob_start();
                readfile("../".$this->anexo);
                $enc = ob_get_contents();
            ob_end_clean();
            $msg_temp = base64_encode($enc). "\n";
            $tmp[1] = strlen($msg_temp);
            $tmp[2] = ceil($tmp[1]/76);
            for ($b = 0;$b <= $tmp[2];$b++){
                $tmp[3] = $b * 76;
                $msg .= substr($msg_temp, $tmp[3], 76) . "\n";
            }
            unset($msg_temp, $tmp, $enc);
        }else{
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            $msg = "$this->mensagem\r\n";
        }
        $headers .= "Cc:" . $this->cc . "\r\n";
        $headers .= "Bcc:" . $this->cco . "\r\n";
        //CVarDumper::dump(array($this->para,$this->assunto,$this->msg,$this->headers));
        //exit();
        return mail($this->para,$this->assunto,$msg,$headers);
    }
}
