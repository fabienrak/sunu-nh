<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:01
 */

namespace app\core;

abstract class ApiClientSoap
{
    private    $server;
    protected  $uri;
    protected  $service;
    protected  $params;
    protected  $xml;
    protected  $soapAction;

    private function __initSoap($uri)
    {
        $this->uri = $uri;
        $this->server = new \nusoap_client($this->uri,true);
        $this->server->soap_defencoding = 'utf-8';
    }

    /**
     * @param $uri
     * @param $service
     * @param array $params
     * @return array
     */
    protected function call($uri, $service, $params = [])
    {
        $this->__initSoap($uri);
        $this->service = $service;
        $this->params = $params;
        return ["response"=>$this->server->call($this->service, $this->params), "error"=>$this->server->getError()];
    }

    protected function callXML($uri, $xml, $soapAction)
    {
        $this->__initSoap($uri);
        $this->xml = $xml;
        $this->soapAction = $soapAction;

        $this->server->send($this->xml,$this->soapAction);
        $retour = ["response"=>null, "error"=>null];
        if($this->server->responseHeader['ResponseHeader']['StatusCode'] == 0) {
            $test = str_replace('BalanceResponse','',$this->server->document);
            $xml =  str_replace('xsi:type=""','',$test);
            $retour["response"] = $this->xmlstr_to_array($xml);
        }
        else {
            $test = str_replace('ErrorResponse','',$this->server->document);
            $xml =  str_replace('xsi:type=""','',$test);
            $retour["error"] = $this->xmlstr_to_array($xml);
        }
        return $retour;
    }

    /**
     * @param $xmlstr
     * @return array|string
     */
    private function xmlstr_to_array($xmlstr)
    {
        @$doc = new \DOMDocument();
        @$doc->loadXML($xmlstr);
        return $this->domnode_to_array($doc->documentElement);
    }

    /**
     * @param $node
     * @return array|string
     */
    private function domnode_to_array($node)
    {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domnode_to_array($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v) {
                        $output = (string) $v;
                    }
                }
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }

}