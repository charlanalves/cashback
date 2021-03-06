<?php

namespace common\components;

use Yii;
use yii\base\Component;

/**
 * UtilComponent
 * Funções (em php) gerais e uteis para todo o sistema
 *
 * @access Public
 * @package Component
 *
 * */
class UtilComponent extends Component {

    private $aux;

    public function addMinutesToDateTime($date, $minutes, $format = 'd/m/Y H:i') {
        $time = new \DateTime($date);
        $time->add(new \DateInterval('PT' . $minutes . 'M'));
        return $time->format($format);
    }

    public function dateBR($date) {
        return (!$date) ? ' - ' : ((strlen($date) > 10) ? date('d/m/Y H:i:s', strtotime($date)) : date('d/m/Y', strtotime($date)));
    }

    public function distanciaGeografica($lat_inicial, $lat_final, $long_inicial, $long_final) {
        $d2r = 0.017453292519943295769236;

        $dlong = ($long_final - $long_inicial) * $d2r;
        $dlat = ($lat_final - $lat_inicial) * $d2r;

        $temp_sin = sin($dlat/2.0);
        $temp_cos = cos($lat_inicial * $d2r);
        $temp_sin2 = sin($dlong/2.0);

        $a = ($temp_sin * $temp_sin) + ($temp_cos * $temp_cos) * ($temp_sin2 * $temp_sin2);
        $c = 2.0 * atan2(sqrt($a), sqrt(1.0 - $a));

        return 6368.1 * $c;
    }

    public function arredondar($vlr) {
        return floor($vlr * 100) / 100;
    }
    
    public function arrayCres($a, $b) {
        return $a[$this->aux] > $b[$this->aux];
    }
    public function arrayDesc($a, $b) {
        return $a[$this->aux] < $b[$this->aux];
    }
    public function orderArrayMult($array, $key, $ordem = 'arrayCres') {
        $this->aux = $key;
        usort($array, array($this, $ordem));
        return $array;
    }

    public function filterOrder($param) {
        switch ($param) {
            case 'destaque' :
                $retorno = "CB06_DINHEIRO_VOLTA DESC";
            break;
            case 'mais-proximos' :
                $retorno = "CB06_DINHEIRO_VOLTA DESC";
            break;
            case 'mais-vendidos' :
                $retorno = "CB06_DINHEIRO_VOLTA DESC";
            break;
            case 'menor-preco' :
                $retorno = "CB06_DINHEIRO_VOLTA DESC";
            break;
            case 'maior-preco' :
                $retorno = "CB06_DINHEIRO_VOLTA DESC";
            break;
            default :
                $retorno = "CB06_DINHEIRO_VOLTA DESC";
            break;
        }
        return $retorno;
    }

    public function infoFile($file) {
        $retorno = $file;
        switch ($file['type']) {
            case 'image/png' :
                $retorno['ex'] = 'png';
                $retorno['family'] = 'image';
            break;
            case 'image/jpeg' :
                $retorno['ex'] = 'jpeg';
                $retorno['family'] = 'image';
            break;
            case 'image/jpg' :
                $retorno['ex'] = 'jpg';
                $retorno['family'] = 'image';
            break;
            case 'image/gif' :
                $retorno['ex'] = 'gif';
                $retorno['family'] = 'image';
            break;
            default :
                $retorno['ex'] = '';
                $retorno['family'] = '';
            break;
        }
        
        return $retorno;
    }

    public function moedaReal($num) {
        return number_format((float) $num, 2, ',', '.');
    }

    public function getTipoContaBancaria() {
        return [1 => 'CORRENTE', 2 => 'POUPANÇA'];
    }

    public function jsonEncodeRecursive($a) {
        
        function json_encode_recursive($in) {
            $_escape = function ($str) {
                return addcslashes($str, "\v\t\n\r\f\"\\/");
            };
            $out = "";
            if (is_object($in)) {
                $class_vars = get_object_vars(($in));
                $arr = array();
                foreach ($class_vars as $key => $val) {
                    $arr[$key] = "\"{$_escape($key)}\":\"{$val}\"";
                }
                $val = implode(',', $arr);
                $out .= "{{$val}}";
            } elseif (is_array($in)) {
                $obj = false;
                $arr = array();
                foreach ($in AS $key => $val) {
                    if (!is_numeric($key)) {
                        $obj = true;
                    }
                    $arr[$key] = json_encode_recursive($val);
                }
                if ($obj) {
                    foreach ($arr AS $key => $val) {
                        $arr[$key] = "\"{$_escape($key)}\":{$val}";
                    }
                    $val = implode(',', $arr);
                    $out .= "{{$val}}";
                } else {
                    $val = implode(',', $arr);
                    $out .= "[{$val}]";
                }
            } elseif (is_bool($in)) {
                $out .= $in ? 'true' : 'false';
            } elseif (is_null($in)) {
                $out .= 'null';
            } elseif (is_string($in)) {
                $out .= '"' . $_escape(str_replace(array("\r\n","\r","\n","\r","\n","\r\n"),'\n', $in)) . '"';
            } else {
                $out .= $in;
            }
            return "{$out}";
        }
        return json_encode_recursive($a);
    }

    
    
    public function setMaskBancaria($banco, $agencia, $conta, $tp_conta = null) {
        $r = [];
        
        $d_agencia = substr($agencia, -1, 1);
        $agencia_s_d = substr($agencia, 0, strlen($agencia)-1);
        
        $d_conta = substr($conta, -1, 1);
        $conta_s_d = substr($conta, 0, strlen($conta)-1);
        
        switch ($banco) {
            
            // Banco do Brasil
            case '1':
                $r['A'] = $agencia_s_d . '-' . $d_agencia;
                $r['C'] = $conta_s_d . '-' . $d_conta;
            break;
            
            // Santander
            case '33':
                $r['A'] = $agencia;
                $r['C'] = $conta_s_d . '-' . $d_conta;
            break;
            
            // Caixa Econômica
            case '104':
                $r['A'] = $agencia;
                $r['C'] = ($tp_conta == 1 ? '001' : '013') . $conta_s_d . '-' . $d_conta;
            break;
            
            // Bradesco
            case '237':
                $r['A'] = $agencia_s_d . '-' . $d_agencia;
                $r['C'] = $conta_s_d . '-' . $d_conta;
            break;
            
            // Itaú
            case '341':
                $r['A'] = $agencia;
                $r['C'] = $conta_s_d . '-' . $d_conta;
            break;

        }
        
        return $r;
        
    }
    
    
    public function getBancos() {
        
        return [
            "1" => "Banco do Brasil",
            "33" => "Santander",
            "104" => "Caixa Econômica",
            "237" => "Bradesco",
            "341" => "Itaú",
        ];
        
        /*
        return [
            "654" => "Banco A.J.Renner S.A.",
            "246" => "Banco ABC Brasil S.A.",
            "25" => "Banco Alfa S.A.",
            "641" => "Banco Alvorada S.A.",
            "213" => "Banco Arbi S.A.",
            "19" => "Banco Azteca do Brasil S.A.",
            "29" => "Banco Banerj S.A.",
            "0" => "Banco Bankpar S.A.",
            "740" => "Banco Barclays S.A.",
            "107" => "Banco BBM S.A.",
            "31" => "Banco Beg S.A.",
            "739" => "Banco BGN S.A.",
            "96" => "Banco BM&F de Serviços de Liquidação e Custódia S.A",
            "318" => "Banco BMG S.A.",
            "752" => "Banco BNP Paribas Brasil S.A.",
            "248" => "Banco Boavista Interatlântico S.A.",
            "218" => "Banco Bonsucesso S.A.",
            "65" => "Banco Bracce S.A.",
            "36" => "Banco Bradesco BBI S.A.",
            "204" => "Banco Bradesco Cartões S.A.",
            "394" => "Banco Bradesco Financiamentos S.A.",
            "237" => "Banco Bradesco S.A.",
            "225" => "Banco Brascan S.A.",
            "M15" => "Banco BRJ S.A.",
            "208" => "Banco BTG Pactual S.A.",
            "44" => "Banco BVA S.A.",
            "263" => "Banco Cacique S.A.",
            "473" => "Banco Caixa Geral - Brasil S.A.",
            "412" => "Banco Capital S.A.",
            "40" => "Banco Cargill S.A.",
            "745" => "Banco Citibank S.A.",
            "M08" => "Banco Citicard S.A.",
            "241" => "Banco Clássico S.A.",
            "M19" => "Banco CNH Capital S.A.",
            "215" => "Banco Comercial e de Investimento Sudameris S.A.",
            "756" => "Banco Cooperativo do Brasil S.A. - BANCOOB",
            "748" => "Banco Cooperativo Sicredi S.A.",
            "75" => "Banco CR2 S.A.",
            "721" => "Banco Credibel S.A.",
            "222" => "Banco Credit Agricole Brasil S.A.",
            "505" => "Banco Credit Suisse (Brasil) S.A.",
            "229" => "Banco Cruzeiro do Sul S.A.",
            "266" => "Banco Cédula S.A.",
            "3" => "Banco da Amazônia S.A.",
            "083-3" => "Banco da China Brasil S.A.",
            "M21" => "Banco Daimlerchrysler S.A.",
            "707" => "Banco Daycoval S.A.",
            "300" => "Banco de La Nacion Argentina",
            "495" => "Banco de La Provincia de Buenos Aires",
            "494" => "Banco de La Republica Oriental del Uruguay",
            "M06" => "Banco de Lage Landen Brasil S.A.",
            "24" => "Banco de Pernambuco S.A. - BANDEPE",
            "456" => "Banco de Tokyo-Mitsubishi UFJ Brasil S.A.",
            "214" => "Banco Dibens S.A.",
            "1" => "Banco do Brasil S.A.",
            "47" => "Banco do Estado de Sergipe S.A.",
            "37" => "Banco do Estado do Pará S.A.",
            "39" => "Banco do Estado do Piauí S.A. - BEP",
            "41" => "Banco do Estado do Rio Grande do Sul S.A.",
            "4" => "Banco do Nordeste do Brasil S.A.",
            "265" => "Banco Fator S.A.",
            "M03" => "Banco Fiat S.A.",
            "224" => "Banco Fibra S.A.",
            "626" => "Banco Ficsa S.A.",
            "M18" => "Banco Ford S.A.",
            "233" => "Banco GE Capital S.A.",
            "734" => "Banco Gerdau S.A.",
            "M07" => "Banco GMAC S.A.",
            "612" => "Banco Guanabara S.A.",
            "M22" => "Banco Honda S.A.",
            "63" => "Banco Ibi S.A. Banco Múltiplo",
            "M11" => "Banco IBM S.A.",
            "604" => "Banco Industrial do Brasil S.A.",
            "320" => "Banco Industrial e Comercial S.A.",
            "653" => "Banco Indusval S.A.",
            "630" => "Banco Intercap S.A.",
            "077-9" => "Banco Intermedium S.A.",
            "249" => "Banco Investcred Unibanco S.A.",
            "M09" => "Banco Itaucred Financiamentos S.A.",
            "184" => "Banco Itaú BBA S.A.",
            "479" => "Banco ItaúBank S.A",
            "376" => "Banco J. P. Morgan S.A.",
            "74" => "Banco J. Safra S.A.",
            "217" => "Banco John Deere S.A.",
            "76" => "Banco KDB S.A.",
            "757" => "Banco KEB do Brasil S.A.",
            "600" => "Banco Luso Brasileiro S.A.",
            "212" => "Banco Matone S.A.",
            "M12" => "Banco Maxinvest S.A.",
            "389" => "Banco Mercantil do Brasil S.A.",
            "746" => "Banco Modal S.A.",
            "M10" => "Banco Moneo S.A.",
            "738" => "Banco Morada S.A.",
            "66" => "Banco Morgan Stanley S.A.",
            "243" => "Banco Máxima S.A.",
            "45" => "Banco Opportunity S.A.",
            "M17" => "Banco Ourinvest S.A.",
            "623" => "Banco Panamericano S.A.",
            "611" => "Banco Paulista S.A.",
            "613" => "Banco Pecúnia S.A.",
            "094-2" => "Banco Petra S.A.",
            "643" => "Banco Pine S.A.",
            "724" => "Banco Porto Seguro S.A.",
            "735" => "Banco Pottencial S.A.",
            "638" => "Banco Prosper S.A.",
            "M24" => "Banco PSA Finance Brasil S.A.",
            "747" => "Banco Rabobank International Brasil S.A.",
            "088-4" => "Banco Randon S.A.",
            "356" => "Banco Real S.A.",
            "633" => "Banco Rendimento S.A.",
            "741" => "Banco Ribeirão Preto S.A.",
            "M16" => "Banco Rodobens S.A.",
            "72" => "Banco Rural Mais S.A.",
            "453" => "Banco Rural S.A.",
            "422" => "Banco Safra S.A.",
            "33" => "Banco Santander (Brasil) S.A.",
            "250" => "Banco Schahin S.A.",
            "743" => "Banco Semear S.A.",
            "749" => "Banco Simples S.A.",
            "366" => "Banco Société Générale Brasil S.A.",
            "637" => "Banco Sofisa S.A.",
            "12" => "Banco Standard de Investimentos S.A.",
            "464" => "Banco Sumitomo Mitsui Brasileiro S.A.",
            "082-5" => "Banco Topázio S.A.",
            "M20" => "Banco Toyota do Brasil S.A.",
            "M13" => "Banco Tricury S.A.",
            "634" => "Banco Triângulo S.A.",
            "M14" => "Banco Volkswagen S.A.",
            "M23" => "Banco Volvo (Brasil) S.A.",
            "655" => "Banco Votorantim S.A.",
            "610" => "Banco VR S.A.",
            "370" => "Banco WestLB do Brasil S.A.",
            "21" => "BANESTES S.A. Banco do Estado do Espírito Santo",
            "719" => "Banif-Banco Internacional do Funchal (Brasil)S.A.",
            "755" => "Bank of America Merrill Lynch Banco Múltiplo S.A.",
            "744" => "BankBoston N.A.",
            "73" => "BB Banco Popular do Brasil S.A.",
            "78" => "BES Investimento do Brasil S.A.-Banco de Investimento",
            "69" => "BPN Brasil Banco Múltiplo S.A.",
            "70" => "BRB - Banco de Brasília S.A.",
            "092-2" => "Brickell S.A. Crédito, financiamento e Investimento",
            "104" => "Caixa Econômica Federal",
            "477" => "Citibank N.A.",
            "081-7" => "Concórdia Banco S.A.",
            "097-3" => "Cooperativa Central de Crédito Noroeste Brasileiro Ltda.",
            "085-x" => "Cooperativa Central de Crédito Urbano-CECRED",
            "099-x" => "Cooperativa Central de Economia e Credito Mutuo das Unicreds",
            "090-2" => "Cooperativa Central de Economia e Crédito Mutuo das Unicreds",
            "089-2" => "Cooperativa de Crédito Rural da Região de Mogiana",
            "087-6" => "Cooperativa Unicred Central Santa Catarina",
            "098-1" => "Credicorol Cooperativa de Crédito Rural",
            "487" => "Deutsche Bank S.A. - Banco Alemão",
            "751" => "Dresdner Bank Brasil S.A. - Banco Múltiplo",
            "64" => "Goldman Sachs do Brasil Banco Múltiplo S.A.",
            "62" => "Hipercard Banco Múltiplo S.A.",
            "399" => "HSBC Bank Brasil S.A. - Banco Múltiplo",
            "168" => "HSBC Finance (Brasil) S.A. - Banco Múltiplo",
            "492" => "ING Bank N.V.",
            "652" => "Itaú Unibanco Holding S.A.",
            "341" => "Itaú Unibanco S.A.",
            "79" => "JBS Banco S.A.",
            "488" => "JPMorgan Chase Bank",
            "14" => "Natixis Brasil S.A. Banco Múltiplo",
            "753" => "NBC Bank Brasil S.A. - Banco Múltiplo",
            "086-8" => "OBOE Crédito Financiamento e Investimento S.A.",
            "254" => "Paraná Banco S.A.",
            "409" => "UNIBANCO - União de Bancos Brasileiros S.A.",
            "230" => "Unicard Banco Múltiplo S.A.",
            "091-4" => "Unicred Central do Rio Grande do Sul",
            "84" => "Unicred Norte do Paraná"];
         * 
         */
    }

}

?>
