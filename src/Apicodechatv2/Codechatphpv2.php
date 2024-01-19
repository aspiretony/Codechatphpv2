<?php

namespace Apicodechatv2;

class Codechatphpv2 extends Construtor {

    private function send($method,$resource,$request = []) {
        $endpoint = $this->url . $resource;
        $headers = [
            "Cache-Control: no-cache",
            "Content-type: application/json"
        ];

        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL 			=> 	$endpoint,
            CURLOPT_RETURNTRANSFER 	=> 	true,
            CURLOPT_CUSTOMREQUEST 	=> 	$method,
            CURLOPT_HTTPHEADER 		=> 	$headers,
            CURLOPT_SSL_VERIFYHOST  =>  false,
            CURLOPT_SSL_VERIFYPEER  =>  false
        ]);
        switch ($method) {
            case "POST":
            case "PUT":
                curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($request));
                break;
        }
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response,true);
    }

    private function _enviar($acao, $method, $json, $auth){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.$acao,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS =>$json,
            CURLOPT_SSL_VERIFYHOST  =>  false,
            CURLOPT_SSL_VERIFYPEER  =>  false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$auth
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    ///////////////////////////////////////ADMIN/////////////////////////////////////////////
    public function createBusiness($name, $atributes){
        $json = array("name" => $name, 'attributes' => array($atributes));
        $method = 'POST';
        $acao = '/api/v2/admin/business';
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenAdmin);
    }

    public function recoverAllBusiness($oldToken, $BusinessID){
        $json = array();
        $method = 'GET';
        $acao = '/api/v2/admin/business';
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenAdmin);
    }

    public function refreshBusinessToken($oldToken, $BusinessID){
        $json = array("oldToken" => $oldToken);
        $method = 'PATCH';
        $acao = '/api/v2/admin/business/'.$BusinessID.'/refresh-token';
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenAdmin);
    }

    public function deleteBusiness($BusinessID){
        $method = 'DELETE';
        $acao = '/api/v2/admin/business/'.$BusinessID;
        $json = array();
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenAdmin);
    }

    public function moveInstance($InstanceID){
        return null;
    }
///////////////////////////////////// FINISH ADMIN////////////////////////////////////////////////////////////////
////////////////////////////////////////// BUSSINERS /////////////////////////////////////////////////////////////

    public function recoverBusiness($BusinessID){
        $method = 'GET';
        $acao = '/api/v2/admin/business/'.$BusinessID;
        $json = array();
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenBussiners);
    }

    public function updateInstance($BusinessID, $instanceName){
        return null;
    }

    public function createInstance($BusinessID, $instanceName){
        $method = 'POST';
        $acao = '/api/v2/business/'.$BusinessID.'/instance/';
        $json = array("instanceName" => $instanceName);
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenBussiners);
    }

    public function deleteInstance($BusinessID, $instanceName){
        $method = 'DELETE';
        $acao = '/api/v2/business/'.$BusinessID.'/instance/';
        $json = array("instanceName" => $instanceName);
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenBussiners);
    }

    public function fetchAllInstance($BusinessID){
        $method = 'GET';
        $acao = '/api/v2/business/'.$BusinessID.'/instance/';
        $json = array();
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenBussiners);
    }

    /**
     * Realiza uma pesquisa de instância para um determinado BusinessID e critérios de pesquisa.
     *
     * @param string $BusinessID O ID do negócio para o qual a pesquisa será realizada.
     * @param string $seachType   O tipo de pesquisa a ser realizado "instanceId","name", "state 'ACTIVE, INACTIVE'", "connection 'OPEN, CLOSE, REFUSED'".
     * @param string $nameSearch  O critério de pesquisa pelo nome.
     * @param int|null $page      O número da página para a pesquisa paginada (opcional).
     *
     * @return mixed Os resultados da pesquisa.
     *
     * @example
     * $searchResult = $obj->searchInstance(123, 'instanceId', 'exampleName', 1);
     */
    public function searchInstance($BusinessID, $seachType, $nameSearch, $page = null){
        $method = 'GET';

/*        {
            "search": {
            "instanceId": "string",
            "name": "string",
            "state": "active | inactive",
            "connection": "open | close | refused"
                        }
        }
*/

        if($page){
            $acao = '/api/v2/business/'.$BusinessID.'/instance/connected?page='.$page;
        }
        $acao = '/api/v2/business/'.$BusinessID.'/instance/connected';
        $json = array($seachType => $nameSearch);
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenBussiners);
    }

    public function moveWhatsapp($BusinessID, $URL, $enabled){
        return null;
    }

    public function createWebhookBusiness($BusinessID, $URL, $enabled){
        $method = 'POST';
        $acao = '/api/v2/business/'.$BusinessID.'/webhook/';
        $json = array("url" => $URL, "enable" => $enabled);
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenBussiners);
    }

    public function findWebhookBusiness($BusinessID){
        $method = 'GET';
        $acao = '/api/v2/business/'.$BusinessID.'/webhook/';
        $json = array();
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenBussiners);
    }

    public function updateWebhookBusiness($BusinessID, $URL, $enabled){
        $method = 'PUT';
        $acao = '/api/v2/business/'.$BusinessID.'/webhook/';
        $json = array("url" => $URL, "enable" => $enabled);
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenBussiners);
    }

    ////////////////////////////////////////////FIM BUSSINERS API////////////////////////////////////////
    ////////////////////////////////////////////////INSTANCE////////////////////////////////////////////
    public function findInstance($instanceID){
        $method = 'GET';
        $acao = '/api/v2/instance/'.$instanceID;
        $json = array();
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenInstance);
    }

    public function connectInstance($instanceID){
        $method = 'GET';
        $acao = '/api/v2/instance/'.$instanceID.'/connect';
        $json = array();
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenInstance);
    }

    public function refreshInstanceToken($instanceID, $tokenOld){
        $method = 'PATCH';
        $acao = '/api/v2/instance/'.$instanceID.'/refresh-token';
        $json = array("oldToken" => $tokenOld);
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenInstance);
    }

    public function logoutInstance($instanceID, $tokenOld){
        $method = 'DELETE';
        $acao = '/api/v2/instance/'.$instanceID.'/logout';
        $json = array("oldToken" => $tokenOld);
        return $this->_enviar($acao, json_encode($json), $method, $this->tokenInstance);
    }

}