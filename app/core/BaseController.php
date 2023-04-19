<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:01
 */

namespace app\core;

use app\core\controllers\ClientController;
use app\core\controllers\ClientSoapController;
use Jacwright\RestServer\RestException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

abstract class BaseController extends Restruction
{
    protected $apiClient;
    protected $apiClientSoap;
    protected $paramGET = [];
    protected $paramPOST = [];
    protected $paramFILE = [];
    protected $views;
    public    $appConfig;
    public    $dbConfig;
    public    $_USER;
    public    $url;
    public    $historique;
    public    $lang;
    public    $lang_choice;

    /**
     * BaseController constructor.
     * @param bool $testUserCon
     * @throws RestException
     */
    public function __construct($testUserCon = true)
    {
        $this->appConfig = \parse_ini_file(ROOT . 'config/app.config.ini');
        $this->dbConfig = \parse_ini_file(ROOT . 'config/db.config.ini');

        if($this->appConfig['law_generate'] == 1) {
            $this->addDroitControllers();
        }

        $this->historique = Session::getAttributArray('historique');

        if($this->appConfig['use_api_client'] == "1") {
            $this->apiClient = ClientController::initClient();
            $this->apiClientSoap = ClientSoapController::initClientSoap();
        }

        if ($testUserCon) Session::isConnected(SESSIONNAME);

        if (Session::existeAttribut(SESSIONNAME)) $this->_USER = Session::getAttributArray(SESSIONNAME)[0];

        if (!Session::existeAttribut("lang")) Session::setAttribut("lang", "fr");

        $this->views = new BaseViews(["header" => $this->appConfig[SPACE . '_header'], "sidebar" => $this->appConfig[SPACE . '_sidebar'], "footer" => $this->appConfig[SPACE . '_footer']], $this->appConfig);
        $this->appConfig = (object)$this->appConfig;
    }

    /**
     * @param null $controller
     * @param null $action
     */
    protected function validateToken($controller = null, $action = null)
    {
        $token = Session::getAttributArray("_token_");

        if (count($this->paramPOST) > 0) {
            if ((isset($this->paramPOST[$token['name']]) && password_verify($token['value'], $this->paramPOST[$token['name']]))) {
                unset($this->paramPOST[$token['name']]);
                $token["used"] = 1;
                Session::setAttributArray("_token_", $token);
                Session::setAttribut("token", sprintf('<input type="hidden" name="%s" value="%s" />', $token["name"], Utils::getPassCrypt($token["value"])));
            } else {
                Utils::setMessageALert(["warning", "Token invalide"]);
                Utils::redirect($controller, $action);
                exit();
            }
        } else {
            Utils::setMessageALert(["warning", "Données invalides"]);
            Utils::redirect($controller, $action);
            exit();
        }
    }

    /**
     * @param $model
     * @return mixed
     */
    protected function model($model)
    {
        $model = Prefix_Model . ucfirst($model) . 'Model';
        return new $model();
    }

    /**
     * @param $model
     * @param $method
     * @param array $param
     * @throws RestException
     */
    protected function processing($model, $method, $param = [], $useAPI = false)
    {
        extract($param);
        $requestData = $_REQUEST;
        $queryData = (is_object($model) ? ((is_null($args) || count($args) == 0) ? $model->$method() : $model->$method($args)) : $model);
        $tempData = $queryData[0];
        $totalData = $queryData[1];
        $totalFiltered = $totalData;
        $data = [];
        if (!is_array($tempData)) print json_encode($tempData);
        else {
            $classModal = (isset($this->paramPOST['id'])) ? "open-modal-processing-".$this->paramPOST['id'] : "open-modal-processing";
            $classConfirm = (isset($this->paramPOST['id'])) ? "confirm-modal-".$this->paramPOST['id'] : "confirm-modal";
            foreach ($tempData as $item) {
                $dataId = (isset($item['id'])) ? $item['id'] : $item['rowid'];
                unset($item['id']);
                unset($item['rowid']);

                if(count($fonction) > 0){
                    foreach ($item as $key => $value){
                        if(in_array($key, array_keys($fonction))){
                            $oneMet = explode("/",$fonction[$key])[0];
                            $oneMet = explode("|",$oneMet);
                            foreach ($oneMet as $oneMetItem)
                                $item[$key] = @call_user_func_array([Utils::class, $oneMetItem],((explode("/",$fonction[$key])[1] == "alldata") ? [$item] : [$item[$key]]));
                        }
                    }
                }

                $href = "";
                $initTooltip = "";
                $addClassCss = "";
                $addAttribut = "";

                if (count($button) > 0) {
                    foreach ($button as $indice => $oneButton) {
                        if ($indice == "modal") {
                            if (count($oneButton) > 0) {
                                foreach ($oneButton as $oneButtonKey => $oneButtonElem) {
                                    $this->setProcessing($item, $indice, $oneButtonKey, $oneButtonElem, $initTooltip, $addClassCss, $addAttribut, $tooltip, $classCss, $attribut);
                                    if (count($oneButtonElem) === 3) {
                                        if(count($oneButtonElem[0]) === 2) {
                                            $droit = $oneButtonElem[0][1];
                                            $oneButtonElem[0] = $oneButtonElem[0][0];
                                        }
//                                        echo"<pre>";var_dump($oneButtonElem);exit();
                                        $oneButtonElem[1] = explode("/", $oneButtonElem[1]);
                                        $modalView = $oneButtonElem[1][0] . '/' . $oneButtonElem[1][1];
                                        unset($oneButtonElem[1][0]);
                                        unset($oneButtonElem[1][1]);
                                        $oneButtonElem[1] = array_values($oneButtonElem[1]);
                                        $modalParam = [base64_encode($dataId)];

                                        if (count($oneButtonElem[1]) > 0)
                                            foreach ($oneButtonElem[1] as $oneButtonElemVal)
                                                if (isset($item[$oneButtonElemVal]))
                                                    array_push($modalParam, base64_encode($item[$oneButtonElemVal]));

                                        if(isset($droit)){
                                            if($useAPI) $auth = Utils::allowed($droit[0], $droit[1]);
                                            else{
                                                $droit = explode("/", $droit);
                                                $auth = Utils::authorized($droit[0], $droit[1]);
                                            }
                                            if($auth) $href .= '<a class="action ' . $classModal . ' ' . $addClassCss . '" ' . $initTooltip . ' ' . $addAttribut . ' href="javascript:;" data-modal-controller="' . $oneButtonElem[0] . '" data-modal-view="' . $modalView . '" data-modal-param="' . implode("/", $modalParam) . '"><i class="' . $oneButtonElem[2] . '"></i></a> ';
                                        }else $href .= '<a class="action ' . $classModal . ' ' . $addClassCss . '" ' . $initTooltip . ' ' . $addAttribut . ' href="javascript:;" data-modal-controller="' . $oneButtonElem[0] . '" data-modal-view="' . $modalView . '" data-modal-param="' . implode("/", $modalParam) . '"><i class="' . $oneButtonElem[2] . '"></i></a> ';

                                        $initTooltip = '';
                                        $addClassCss = '';
                                        $addAttribut = '';
                                    }
                                }
                            }
                        }
                        elseif ($indice == "default") {
                            if (count($oneButton) > 0) {
                                foreach ($oneButton as $oneButtonKey => $oneButtonElem) {
                                    $this->setProcessing($item, $indice, $oneButtonKey, $oneButtonElem, $initTooltip, $addClassCss, $addAttribut, $tooltip, $classCss, $attribut);
                                    if (count($oneButtonElem) === 2) {
                                        if(count($oneButtonElem[0]) === 2) {
                                            $droit = ($useAPI) ? $oneButtonElem[0][1] : explode("/", $oneButtonElem[0][1]);
                                            $oneButtonElem[0] = $oneButtonElem[0][0];
                                        }else $droit = explode("/", $oneButtonElem[0]);
                                        $oneButtonElem[0] = explode("/", $oneButtonElem[0]);
                                        $linkHref = $oneButtonElem[0][0] . '/' . $oneButtonElem[0][1];
                                        unset($oneButtonElem[0][0]); unset($oneButtonElem[0][1]);
                                        $oneButtonElem[0] = array_values($oneButtonElem[0]);
                                        $linkParam = [base64_encode($dataId)];
                                        if (count($oneButtonElem[0]) > 0)
                                            foreach ($oneButtonElem[0] as $oneButtonElemVal)
                                                if (isset($item[$oneButtonElemVal]))
                                                    array_push($linkParam, base64_encode($item[$oneButtonElemVal]));
                                        $auth = $useAPI ? Utils::allowed($droit[0], $droit[1]) : Utils::authorized($droit[0], $droit[1]);
                                        if($auth)
                                            $href .= "<a class='action " . $addClassCss . "' " . $initTooltip . " " . $addAttribut . " href='" . WEBROOT . $linkHref . '/' . implode("/", $linkParam) . "'><i class='" . $oneButtonElem[1] . "'></i></a> ";
                                        $initTooltip = '';
                                        $addClassCss = '';
                                        $addAttribut = '';

                                    }
                                }
                            }
                        }
                        elseif ($indice == "custom") {
                            if (count($oneButton) > 0) {
                                foreach ($oneButton as $oneButtonElem) {
                                    if (isset($oneButtonElem["champ"]) && isset($oneButtonElem["val"]))
                                        $oneButtonElem = $oneButtonElem["val"][$item[$oneButtonElem["champ"]]];
                                    $href .= "<span class='action'>" . $oneButtonElem . "<span hidden>base64_encode($dataId)</span></span>";
                                }
                            }
                        }
                    }
                }
                if (count($dataVal) > 0)
                    foreach ($dataVal as $oneValue)
                        if (isset($item[$oneValue['champ']]))
                            $item[$oneValue['champ']] = $oneValue['val'][$item[$oneValue['champ']]];

                foreach ($item as $key => $val)
                    if (Utils::startsWith($key, '_')
                        && Utils::endsWith($key, '_'))
                        unset($item[$key]);

                $temp = array_values($item);
                array_push($temp, $href);
                $data[] = $temp;
            }
            $modalJS = '<script>
                            $(".' . $classModal . '").on("click", function() {
                                        let racine = "' . WEBROOT . '";
                                        let controller = $(this).data("modal-controller");
                                        let view = $(this).data("modal-view");
                                        let param = $(this).data("modal-param");
                                        let staticModal = $(this).data("modal-static");
                                        staticModal = staticModal == true || staticModal == false ? staticModal : staticGlobalModal;
                                        let $url = (param === undefined) ? webroot + controller : webroot + controller + "/" + param;
                                        if (controller !== undefined) {
                                            $.post (
                                                $url, {view : view},
                                                function(data){
                                                    if (parseInt(data) !== 0) {
                                                        let modal = \'<div class="modal fade bs-modal-lg" id="modal" \'+(staticModal == true ? \'data-backdrop="static"\' : "")+\' data-keyboard="false" data-dismiss="modal" tabindex="-1" role="dialog" aria-hidden="true"> <div class="modal-dialog modal-lg"> <div class="modal-content" id="content-modal"> <div class="modal-header"> <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button> <h4 class="modal-title">En cours de chargement</h4> </div> <div class="modal-body"> <div align="center"> <img src="\'+assets+\'_main_/loading.gif" width="25%"/> </div> </div> <div class="modal-footer"> <button class="btn btn-default" type="button" data-dismiss="modal"> <i class="fa fa-times"></i> Annuler </button> </div> </div> </div> </div>\';
                                                        $(\'#modal-container\').html(modal);
                                                        $(\'#content-modal\').html(data);
                                                        $(\'#modal\').modal("show");
                                                    } else alert("La vue n\'a pas été définie !")
                                                }
                                            );
                                        }else alert("Le controller n\'a pas été défini !")
                                    });
                                    $(".' . $classConfirm . '").on("click", function (e) {
                                        let type_link = "url";
                                        let link = $(this).attr("href");
                                        if(link === undefined) {
                                            link = $(this).data("form");
                                            type_link = "form"
                                        }
                                        if(link !== undefined){
                                            e.preventDefault();
                                            $.getJSON(racine+"language/getLang/' . base64_encode(SPACE) . '", (lang) => {
                                                console.log(lang);
                                                $.confirm({
                                                title: lang.confirmTitre,
                                                escapeKey: true, // close the modal when escape is pressed.
                                                content: lang.confirmMessage,
                                                backgroundDismiss: false, // for escapeKey to work, backgroundDismiss should be enabled.
                                                icon: "fa fa-question",
                                                theme: "material",
                                                closeIcon: true,
                                                animation: "scale",
                                                type: "red",
                                                buttons: {
                                                    "non" : {
                                                        text: lang.confirmBtnKo,
                                                        btnClass: "btn-red",
                                                        keys: ["ctrl","shift"],
                                                        action: () => {}
                                                    },
                                                    "oui" : {
                                                        text: lang.confirmBtnOk,
                                                        btnClass: "btn-green",
                                                        keys: ["enter"],
                                                        action: () => {
                                                            if(type_link === "url") window.location = link;
                                                            else $("#"+link).submit();
                                                        }
                                                    }
                                                },
                                            });
                                            });
                                        }
                                    });
                                    $(\'a[data-toggle="tooltip"]\').tooltip();
                                </script>';
            if (isset($modalJS)) $data[count($data) - 1][count($data[count($data) - 1]) - 1] .= $modalJS;

            $json_data = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered),// total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            echo json_encode($json_data);  // send data as json format
        }
    }

    /**
     * @param $model
     * @param $method
     * @param array $param
     * @return array
     */
    public function pagination($model, $method, $param = [])
    {
        extract($param);
        redo:;
        if(!isset($length) || intval($length) > 3) $length = 0;
        $tabLength = [10, 25, 50, 100];
        $start = (isset($pages) && isset($pages['nombre']) && isset($pages['active'])) ? ($pages['active'] - 1) * $tabLength[$length] : 0;
        $model->params['limit'] = ['start'=>$start, 'length'=>$tabLength[$length]];
        $queryData = (is_object($model) ? (((is_null($args) || count($args) === 0)) ? $model->$method() : $model->$method($args)) : null);
        if($model->apiCall) {
            $tempData = $queryData["data"][0];
            $totalData = $queryData["data"][1];
        }else{
            $tempData = $queryData[0];
            $totalData = $queryData[1];
        }

        $nombre = intval(ceil($totalData/$tabLength[$length]));
        if(!isset($pages) || intval($pages['active']) > $nombre) {
            $pages = ["nombre"=>$nombre, "active"=>1];
            goto redo;
        }
        $data = [];
        if (!is_array($tempData)) print json_encode($tempData);
        else {
            foreach ($tempData as $item) {
                if(isset($fonction) && count($fonction) > 0){
                    foreach ($item as $key => $value){
                        if(in_array($key, array_keys($fonction))){
                            $oneMet = explode("/",$fonction[$key])[0];
                            $oneMet = explode("|",$oneMet);
                            foreach ($oneMet as $oneMetItem)
                                $item[$key] = @call_user_func_array([Utils::class, $oneMetItem],((explode("/",$fonction[$key])[1] == "alldata") ? [$item] : [$item[$key]]));
                        }
                    }
                }

                if (isset($dataVal) && count($dataVal) > 0)
                    foreach ($dataVal as $oneValue)
                        if (isset($item[$oneValue['champ']]))
                            $item[$oneValue['champ']] = $oneValue['val'][$item[$oneValue['champ']]];

                $data[] = $item;
            }
            $data = [
                "data"=>$data,
                "length"=>["valeur"=>$tabLength, "active"=>$length],
                "total"=>$totalData,
                "pages"=>$pages
            ];
            $_2 = ((($data["pages"]["active"] - 1) * $tabLength[$length]) + $tabLength[$length]);
            $_2 = $_2 > $totalData ? $totalData : $_2;
            $data["text"] = str_replace("$1", ((($data["pages"]["active"] - 1) * $tabLength[$length]) + 1), str_replace("$2", $_2, str_replace("$3", $totalData, $this->lang['pagination'])));
        }
        return $data;
    }

    /**
     * @param mixed $paramGET
     */
    public function setParamGET($paramGET)
    {
        $paramGET = Utils::setPurgeArray($paramGET);
        $this->paramGET = $paramGET;
    }

    /**
     * @param mixed $paramPOST
     */
    public function setParamPOST($paramPOST)
    {
        $paramPOST = Utils::setPurgeArray($paramPOST);
        $this->paramPOST = $paramPOST;
        unset($_POST);
    }

    /**
     * @param mixed $paramFILE
     */
    public function setParamFILE($paramFILE)
    {
        $this->paramFILE = $paramFILE;
    }


    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        if (\array_key_exists('space_'.$this->url[0], (array)$this->appConfig)) {
            unset($this->url[0]);
            $this->url = array_values($this->url);
        }
        $this->views->setUrl($url);
    }

    public function setLang()
    {
        $this->lang_choice = Session::getAttribut('lang');
        $this->lang = Language::getLang($this->lang_choice, SPACE);
        $this->views->setLang($this->lang, $this->lang_choice);
    }

    protected function modal()
    {
        ob_start();
        $this->views->getModal($this->paramPOST['view']);
        $content = ob_get_clean();
        echo $content;
    }

    /**
     * @param array $param
     * @return bool
     */
    protected function sendMail(array $param)
    {
        if (count($param) > 0) {
            extract($param);
            if (isset($subject) && isset($content) && isset($email)) {
                try {
                    if (isset($data)) extract($data);
                    $mail = new PHPMailer();
                    $mail->SetLanguage(Session::getAttribut('lang'));
                    $mail->CharSet = 'UTF-8';
                    $mail->isHTML(true);
                    $mail->setFrom($this->appConfig->mail_from);
                    $mail->addAddress($email);
                    $mail->Subject = $subject;
                    $email->Body = '<html><head><meta charset="utf-8"></head><body>';
                    if (file_exists(ROOT . Prefix_View . $content . '.php')) {
                        ob_start();
                        include(ROOT . Prefix_View . $content . '.php');
                        $mail->Body .= ob_get_clean();
                    } else $mail->Body .= $content;
                    $email->Body .= '</body></html>';
                    if (isset($joint) && count($joint) > 0) {
                        $file = [];
                        $index = 1;
                        foreach ($joint as $onpj) {
                            if ($onpj['path'] == "serveur") {
                                $file["file"] = ROOT . $onpj['content'];
                                $file["ext"] = explode(".", $onpj['content'])[1];
                                $mail->addAttachment($file["file"], $index . '.' . $file["ext"]);
                            } elseif ($onpj['path'] == "generate") {
                                $file["file"] = $this->views->exportToPdf($onpj['content'], $index, 'S');
                                $file["ext"] = "pdf";
                                $mail->addStringAttachment($file["file"], $index . '.' . $file["ext"]);
                            }
                            $index++;
                        }
                    }
                    return $mail->send();
                } catch (Exception $e) {
                    Utils::setMessageError([$e->getMessage()]);
                    Utils::redirect("error", "error", [], "default");
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * @param $item
     * @param $indice
     * @param $oneButtonKey
     * @param $oneButtonElem
     * @param $initTooltip
     * @param $addClassCss
     * @param $addAttribut
     * @param $tooltip
     * @param $classCss
     * @param $attribut
     */
    private function setProcessing(&$item, &$indice, &$oneButtonKey, &$oneButtonElem, &$initTooltip, &$addClassCss, &$addAttribut, &$tooltip, &$classCss, &$attribut)
    {
        if (isset($oneButtonElem["champ"]) && isset($oneButtonElem["val"]))
            $oneButtonElem = $oneButtonElem["val"][$item[$oneButtonElem["champ"]]];
        if (isset($tooltip[$indice][$oneButtonKey])) {
            if (isset($tooltip[$indice][$oneButtonKey]["champ"]) && isset($tooltip[$indice][$oneButtonKey]["val"])) {
                $initTooltip = $tooltip[$indice][$oneButtonKey]["val"][$item[$tooltip[$indice][$oneButtonKey]["champ"]]];
                $initTooltip = (is_null($initTooltip)) ? '' : "title='" . $initTooltip . "' data-placement='top' data-toggle='tooltip'";
            } else
                $initTooltip = "title='" . $tooltip[$indice][$oneButtonKey] . "' data-placement='top' data-toggle='tooltip'";
        }
        if (isset($classCss[$indice][$oneButtonKey])) {
            if (isset($classCss[$indice][$oneButtonKey]["champ"]) && isset($classCss[$indice][$oneButtonKey]["val"])) {
                $addClassCss = $classCss[$indice][$oneButtonKey]["val"][$item[$classCss[$indice][$oneButtonKey]["champ"]]];
                $addClassCss = (is_null($addClassCss)) ? '' : str_replace("confirm", "confirm-modal ", $classCss[$indice][$oneButtonKey]);
            } else
                $addClassCss = $addClassCss = str_replace("confirm", "confirm-modal ", $classCss[$indice][$oneButtonKey]);
        }
        if (isset($attribut[$indice][$oneButtonKey])) {
            if (isset($attribut[$indice][$oneButtonKey]["champ"]) && isset($attribut[$indice][$oneButtonKey]["val"])) {
                $addAttribut = $attribut[$indice][$oneButtonKey]["val"][$item[$attribut[$indice][$oneButtonKey]["champ"]]];
                $addAttribut = (is_null($addAttribut)) ? '' : $attribut[$indice][$oneButtonKey];
            } else
                $addAttribut = $attribut[$indice][$oneButtonKey];
        }
    }

    /**
     * @throws RestException
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     */
    public function unique()
    {
        try{
            $this->paramPOST["data"] = unserialize(Utils::decryptString($this->paramPOST["data"]));
        }catch(\Exception $ex){
            echo"<pre>";var_dump($ex->getMessage());exit();
        }
        $data = (new Model())->get(["table"=>$this->paramPOST["data"]['table'], "champs"=>[$this->paramPOST["data"]['champ']], "condition"=>[$this->paramPOST["data"]['champ']." ="=>$this->paramPOST["value"]]]);
        print json_encode((count($data) === 0 ? true : "Oups! '".$this->paramPOST["value"]."' est déja renseignée pour le champ ".$this->paramPOST["data"]['champ']));
    }
}