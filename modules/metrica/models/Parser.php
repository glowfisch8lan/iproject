<?php

namespace app\modules\metrica\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\Session;
use app\modules\metrica\models\Pattern;

class Parser extends Model
{

    public $url;
    public $date;
    public $user;
    public $patterns;

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['date', 'url', 'user', 'patterns'], 'required'],
            [['date', 'patterns'], 'safe'],
            [['url', 'value'], 'string'],
            [['user'], 'integer'],
        ];
    }
    /**
     * @var array
     */
    private static $arErrorCodes = [
        "CURLE_UNSUPPORTED_PROTOCOL",
        "CURLE_FAILED_INIT",
        "CURLE_URL_MALFORMAT",
        "CURLE_URL_MALFORMAT_USER",
        "CURLE_COULDNT_RESOLVE_PROXY",
        "CURLE_COULDNT_RESOLVE_HOST",
        "CURLE_COULDNT_CONNECT",
        "CURLE_FTP_WEIRD_SERVER_REPLY",
        "CURLE_REMOTE_ACCESS_DENIED",
        "CURLE_FTP_WEIRD_PASS_REPLY",
        "CURLE_FTP_WEIRD_PASV_REPLY",
        "CURLE_FTP_WEIRD_227_FORMAT",
        "CURLE_FTP_CANT_GET_HOST",
        "CURLE_FTP_COULDNT_SET_TYPE",
        "CURLE_PARTIAL_FILE",
        "CURLE_FTP_COULDNT_RETR_FILE",
        "CURLE_QUOTE_ERROR",
        "CURLE_HTTP_RETURNED_ERROR",
        "CURLE_WRITE_ERROR",
        "CURLE_UPLOAD_FAILED",
        "CURLE_READ_ERROR",
        "CURLE_OUT_OF_MEMORY",
        "CURLE_OPERATION_TIMEDOUT",
        "CURLE_FTP_PORT_FAILED",
        "CURLE_FTP_COULDNT_USE_REST",
        "CURLE_RANGE_ERROR",
        "CURLE_HTTP_POST_ERROR",
        "CURLE_SSL_CONNECT_ERROR",
        "CURLE_BAD_DOWNLOAD_RESUME",
        "CURLE_FILE_COULDNT_READ_FILE",
        "CURLE_LDAP_CANNOT_BIND",
        "CURLE_LDAP_SEARCH_FAILED",
        "CURLE_FUNCTION_NOT_FOUND",
        "CURLE_ABORTED_BY_CALLBACK",
        "CURLE_BAD_FUNCTION_ARGUMENT",
        "CURLE_INTERFACE_FAILED",
        "CURLE_TOO_MANY_REDIRECTS",
        "CURLE_UNKNOWN_TELNET_OPTION",
        "CURLE_TELNET_OPTION_SYNTAX",
        "CURLE_PEER_FAILED_VERIFICATION",
        "CURLE_GOT_NOTHING",
        "CURLE_SSL_ENGINE_NOTFOUND",
        "CURLE_SSL_ENGINE_SETFAILED",
        "CURLE_SEND_ERROR",
        "CURLE_RECV_ERROR",
        "CURLE_SSL_CERTPROBLEM",
        "CURLE_SSL_CIPHER",
        "CURLE_SSL_CACERT",
        "CURLE_BAD_CONTENT_ENCODING",
        "CURLE_LDAP_INVALID_URL",
        "CURLE_FILESIZE_EXCEEDED",
        "CURLE_USE_SSL_FAILED",
        "CURLE_SEND_FAIL_REWIND",
        "CURLE_SSL_ENGINE_INITFAILED",
        "CURLE_LOGIN_DENIED",
        "CURLE_TFTP_NOTFOUND",
        "CURLE_TFTP_PERM",
        "CURLE_REMOTE_DISK_FULL",
        "CURLE_TFTP_ILLEGAL",
        "CURLE_TFTP_UNKNOWNID",
        "CURLE_REMOTE_FILE_EXISTS",
        "CURLE_TFTP_NOSUCHUSER",
        "CURLE_CONV_FAILED",
        "CURLE_CONV_REQD",
        "CURLE_SSL_CACERT_BADFILE",
        "CURLE_REMOTE_FILE_NOT_FOUND",
        "CURLE_SSH",
        "CURLE_SSL_SHUTDOWN_FAILED",
        "CURLE_AGAIN",
        "CURLE_SSL_CRL_BADFILE",
        "CURLE_SSL_ISSUER_ERROR",
        "CURLE_FTP_PRET_FAILED",
        "CURLE_FTP_PRET_FAILED",
        "CURLE_RTSP_CSEQ_ERROR",
        "CURLE_RTSP_SESSION_ERROR",
        "CURLE_FTP_BAD_FILE_LIST",
        "CURLE_CHUNK_FAILED"
    ];
    /**
     * @param array $arParams
     * @return array|bool
     */

    /**
     *  Получаем страницу
     */
    private function getPage($arParams = [])
    {
        if ($arParams) {
            if (!empty($arParams["url"])) {
                $sUrl = $arParams["url"];
                $sUserAgent = !empty($arParams["useragent"]) ? $arParams["useragent"] : "Mozilla/5.0 (Windows NT 6.3; W…) Gecko/20100101 Firefox/57.0";
                $iTimeout = !empty($arParams["timeout"]) ? $arParams["timeout"] : 5;
                $iConnectTimeout = !empty($arParams["connecttimeout"]) ? $arParams["connecttimeout"] : 5;
                $bHead = !empty($arParams["head"]) ? $arParams["head"] : false;
                $sCookieFile = !empty($arParams["cookie"]["file"]) ? $arParams["cookie"]["file"] : false;
                $bCookieSession = !empty($arParams["cookie"]["session"]) ? $arParams["cookie"]["session"] : false;
                $sProxyIp = !empty($arParams["proxy"]["ip"]) ? $arParams["proxy"]["ip"] : false;
                $iProxyPort = !empty($arParams["proxy"]["port"]) ? $arParams["proxy"]["port"] : false;
                $sProxyType = !empty($arParams["proxy"]["type"]) ? $arParams["proxy"]["type"] : false;
                $arHeaders = !empty($arParams["headers"]) ? $arParams["headers"] : false;
                $sPost = !empty($arParams["post"]) ? $arParams["post"] : false;
                if ($sCookieFile) {
                    file_put_contents(__DIR__ . "/" . $sCookieFile, "");
                }
                $rCh = curl_init();
                curl_setopt($rCh, CURLOPT_URL, $sUrl);
                curl_setopt($rCh, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($rCh, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($rCh, CURLOPT_USERAGENT, $sUserAgent);
                curl_setopt($rCh, CURLOPT_TIMEOUT, $iTimeout);
                curl_setopt($rCh, CURLOPT_CONNECTTIMEOUT, $iConnectTimeout);

                curl_setopt($rCh, CURLOPT_SSL_VERIFYPEER, false);//чтобы не было ошибок при обрабокте https
                curl_setopt($rCh, CURLOPT_SSL_VERIFYHOST, false);//чтобы не было ошибок при обрабокте https

                if ($bHead) {
                    curl_setopt($rCh, CURLOPT_HEADER, true);
                    curl_setopt($rCh, CURLOPT_NOBODY, true);
                }

                /* if (strpos($sUrl, "https") !== false) { //чтобы не было ошибок при обрабокте https
                    curl_setopt($rCh, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($rCh, CURLOPT_SSL_VERIFYPEER, true);
                } */

                if ($sCookieFile) {
                    curl_setopt($rCh, CURLOPT_COOKIEJAR, __DIR__ . "/" . $sCookieFile);
                    curl_setopt($rCh, CURLOPT_COOKIEFILE, __DIR__ . "/" . $sCookieFile);
                    if ($bCookieSession) {
                        curl_setopt($rCh, CURLOPT_COOKIESESSION, true);
                    }
                }
                if ($sProxyIp && $iProxyPort && $sProxyType) {
                    curl_setopt($rCh, CURLOPT_PROXY, $sProxyIp . ":" . $iProxyPort);
                    curl_setopt($rCh, CURLOPT_PROXYTYPE, $sProxyType);
                }
                if ($arHeaders) {
                    curl_setopt($rCh, CURLOPT_HTTPHEADER, $arHeaders);
                }
                if ($sPost) {
                    curl_setopt($rCh, CURLOPT_POSTFIELDS, $sPost);
                }
                curl_setopt($rCh, CURLINFO_HEADER_OUT, true);
                $sContent = curl_exec($rCh);
                $arInfo = curl_getinfo($rCh);
                $arError = false;
                if ($sContent === false) {
                    $arData = false;
                    $arError["message"] = curl_error($rCh);
                    $arError["code"] = self::$arErrorCodes[curl_errno($rCh)];
                } else {
                    $arData["content"] = $sContent;
                    $arData["info"] = $arInfo;
                }
                curl_close($rCh);
                return [
                    "data" => $arData,
                    "error" => $arError
                ];
            }
        }
        return false;
    }

    /**
     * @param $url
     * @return string
     */
    private function clean_string_from_protocol($url){
        return preg_replace('#^https?://#', '', $url);
    }


    /*
     * $pattern принимает паттерн в виде строки типа mc.yandex.ru/watch/{id};
     * $input принимает html код страницы
     */
    private function find($input, $expression)
    {

        $arr = array(
            array(
                'orig' => '/',
                'replace' => '\/'
            ) ,
            array(
                'orig' => '.',
                'replace' => '\.'
            ) ,
            array(
                'orig' => '?',
                'replace' => '\?'
            ) ,

            array(
                'orig' => '{id}',
                'replace' => '([0-9]+)'
            ) ,
        );

        foreach ($arr as $var)
        {
            $expression = str_replace($var['orig'], $var['replace'], $expression);
        }

        if( preg_match_all("/$expression/", $input, $id) ) {return $id[1][0];}
        else return 0;
    }

    /*
     *  Возвращает массив данных и http_code;
     *  Принимает ссылку url;
     */
    private function parse($url){

        $html = (object) self::getPage([
            "url" => $url, "cookie" => [ "file" => '', "session" => false ]
        ]);

        return [$html->data['info']['http_code'], $html];
    }

    //применить регулярное выражение к url-странице
    private function doRegular($url, $id, $p){

         $input = $this->parse($url);

         if( $input[1]->data['info']['http_code'] === 200 ){
             $regular = $p['pattern'][$id];
             $arr['mid'] = $this->find( $input[1]->data['content'], $regular );
             $arr['metrika_name'] = $p['name'][$id];
             $arr['url'] = $url;
             return $arr;
         }
         else{
             Yii::warning('debug',  print_r('http_code: ' . $input[1]->data['info']['http_code'], true));
             return 0;
         }

    }

    public function analyze($data){

        $pattern = new Pattern();
        $patterns = $pattern->getAllPatterns();

        $p['name'] = ArrayHelper::map($patterns,'id','name');
        $p['pattern'] = ArrayHelper::map($patterns,'id','pattern');
        $p['user'] = ArrayHelper::map($patterns,'id','user');

        foreach ( $data as $obj1 ) {
            foreach ($obj1['patterns'] as $key => $id){
                $result[] = $this->doRegular($obj1['url'], $id,  $p);
            }
        }
        return $result;

    }
}
