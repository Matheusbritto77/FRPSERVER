<?php
/**
 * DHRU Fusion api standards V6.1
 */
 
 
require_once __DIR__ . "/../bootstrap.php";  // Caminho para o arquivo bootstrap.php

session_name("DHRUFUSION");
session_set_cookie_params(0, "/", null, false, true);
session_start();
error_reporting(0);
$apiversion = '6.1';
foreach ($_POST as $k => $v) {
    ${$k} = filter_var($v, FILTER_SANITIZE_STRING);
}


$apiresults = array();
if ($parameters) {
    $parameters = json_decode(base64_decode($parameters), true);
}


if ($User = validateAuth($username, $apiaccesskey)) {
    switch ($action) {

        case "accountinfo":
            $AccoutInfo['credit'] = 1000;
            $AccoutInfo['mail'] = 'fusionapistandards@dhrusoft.com';
            $AccoutInfo['currency'] = 'USD'; /* Currency code */
            $apiresults['SUCCESS'][] = array('message' => 'Your Accout Info', 'AccoutInfo' => $AccoutInfo);
            break;

        case "imeiservicelist":
            $ServiceList = NULL;
            $Group = 'FRP SAMSUNG';
            $ServiceList[$Group]['GROUPNAME'] = $Group;
            $ServiceList[$Group]['GROUPTYPE'] = 'IMEI'; // IMEI OR SERVER OR REMOTE

            /* LOOP of service by group*/
            {
                $SERVICEID = 1;
                $ServiceList[$Group]['GROUPTYPE'] = 'IMEI';  //IMEI OR SERVER
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['SERVICEID'] = $SERVICEID;
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['SERVICETYPE'] = 'IMEI'; // IMEI OR SERVER OR REMOTE
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['SERVICENAME'] = 'FRP IMEI BEST PRICE';
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['CREDIT'] = 0;
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['INFO'] = utf8_encode('VERIFIQUE A PAGINA DE STATUS PARA SABER SE O SERVIDOR ESTA ONLINE AQUI : ');
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['TIME'] = '1-5 Minutes';

               


              

                
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['Requires.Custom'] = $CUSTOM;
            }

            $apiresults['SUCCESS'][] = array('MESSAGE' => 'IMEI Service List', 'LIST' => $ServiceList);
            break;



case "placeimeiorder":
    // Extrai os parâmetros de 'parameters' do JSON recebido
    $dadosRecebidos = $_POST['parameters'] ?? null;

    if ($dadosRecebidos) {
        // Converte os parâmetros XML para um array
        $xmlConvertido = simplexml_load_string($dadosRecebidos);
        $imeiRecebido = (string) $xmlConvertido->IMEI ?? null;  // Pega o valor de IMEI
        $campoPersonalizado = (string) $xmlConvertido->CUSTOMFIELD ?? null;  // Pega o valor de CUSTOMFIELD

        if ($imeiRecebido && $campoPersonalizado) {
            // Criar nova instância de Order
            $novaOrdem = new \App\Entities\Order();
            $novaOrdem->setImei($imeiRecebido);  // Define o valor de IMEI
            $novaOrdem->setStatus(1);  // Status 1 (pode ser alterado conforme necessário)

            // Persistir o pedido no banco de dados usando o EntityManager
            $entityManager->persist($novaOrdem);
            $entityManager->flush();  // Realiza o commit na base de dados

            // Obtém o ID da ordem registrada (order_reff_id)
            $idOrdemRegistrada = $novaOrdem->getId();

            // Retorna os resultados com os dados extraídos
            $apiresults['SUCCESS'][] = [
                'MESSAGE' => 'Order received',
                'REFERENCEID' => $idOrdemRegistrada,
                'IMEI' => $imeiRecebido,
                'CUSTOMFIELD' => $campoPersonalizado,  // CustomField sem decodificação
            ];
        } else {
            $apiresults['ERROR'][] = [
                'MESSAGE' => 'Missing IMEI or CustomField',
            ];
        }
    } else {
        $apiresults['ERROR'][] = [
            'MESSAGE' => 'Missing parameters',
        ];
    }
    break;






        case "placeimeiorderbulk":
            /* Other Fusion 31- 59 api support for bulk submit */
            /*Validate each orders in loop */
            foreach ($parameters as $bulkReqId => $OrdersDetails) {

                $ServiceId = (int)$OrdersDetails['ID'];
                $CustomField = json_decode(base64_decode($OrdersDetails['customfield']), true);

                if (validateCredits($User, $credit)) {
                    /*  Process order and ger order reference id*/
                    $order_reff_id = 2323;
                    $apiresults[$bulkReqId]['SUCCESS'][] = array('MESSAGE' => 'Order received', 'REFERENCEID' => $order_reff_id);
                } else {
                    $apiresults[$bulkReqId]['ERROR'][] = array('MESSAGE' => 'Not enough credits');
                }


            }
            
            
            
            break;
            
            
            
            

case "getimeiorder":
    // Extrai os parâmetros de 'parameters' do JSON recebido
    $parameters = $_POST['parameters'] ?? null;

    if ($parameters) {
        // Converte os parâmetros XML para um array
        $xml = simplexml_load_string($parameters);
        $id = (int) ($xml->ID ?? null);  // Pega o valor do ID e garante que seja um inteiro

        if ($id) {
            // Busca a ordem no banco de dados pelo ID
            $order = $entityManager->find(\App\Entities\Order::class, $id);

            if ($order) {
                $apiresults['SUCCESS'][] = [
                    'STATUS' => $order->getStatus(), // Retorna o status real do pedido
                    'CODE' => $order->getCode() ?? null // Retorna o código ou null se não existir
                ];
            } else {
                $apiresults['ERROR'][] = [
                    'MESSAGE' => 'Order not found'
                ];
            }
        } else {
            $apiresults['ERROR'][] = [
                'MESSAGE' => 'Missing ID parameter'
            ];
        }
    } else {
        $apiresults['ERROR'][] = [
            'MESSAGE' => 'Missing parameters'
        ];
    }
    break;


            
            
            
            
            

        case "getimeiorderbulk":
            /* Other Fusion 31- 59 api support for bulk get */
            /*Validate each orders in loop */
            foreach ($parameters as $bulkReqId => $OrdersDetails) {
                $OrderID = (int)$OrdersDetails['ID'];
                $apiresults[$bulkReqId]['SUCCESS'][] = array(
                    'STATUS' => 3, /* 0 - New , 1 - InProcess, 3 - Reject(Refund), 4- Available(Success)  */
                    'CODE' => 'CODE');
            }
            break;

        default:
            $apiresults['ERROR'][] = array('MESSAGE' => 'Invalid Action');
    }
} else {
    $apiresults['ERROR'][] = array('MESSAGE' => 'Authentication Failed');
}







function validateAuth($username, $apikey)
{
    return true;
}

function validateCredits($username, $credit)
{
    return true;
}

if (count($apiresults)) {
    header("X-Powered-By: DHRU-FUSION");
    header("dhru-fusion-api-version: $apiversion");
    header_remove('pragma');
    header_remove('server');
    header_remove('transfer-encoding');
    header_remove('cache-control');
    header_remove('expires');
    header('Content-Type: application/json; charset=utf-8');
    $apiresults['apiversion'] = $apiversion;
    exit(json_encode($apiresults));
}