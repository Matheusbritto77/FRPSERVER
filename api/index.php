<?php

// Incluir o arquivo de configuração do Doctrine (bootstrap.php)
require_once __DIR__ . "../bootstrap.php"; 

session_name("DHRUFUSION");
session_set_cookie_params(0, "/", null, false, true);
session_start();
error_reporting(0);

$apiversion = '6.1';

// Sanitização dos dados de entrada
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
            $AccoutInfo['currency'] = 'USD'; 
            $apiresults['SUCCESS'][] = array('message' => 'Your Accout Info', 'AccoutInfo' => $AccoutInfo);
            break;

        case "imeiservicelist":
            $ServiceList = NULL;
            $Group = 'FRP SAMSUNG';
            $ServiceList[$Group]['GROUPNAME'] = $Group;
            $ServiceList[$Group]['GROUPTYPE'] = 'IMEI';

            {
                $SERVICEID = 1;
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['SERVICEID'] = $SERVICEID;
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['SERVICENAME'] = 'FRP IMEI BEST PRICE';
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['TIME'] = '1-5 Minutes';

                $CUSTOM = array();
                $CUSTOM[0]['type'] = 'serviceimei';
                $CUSTOM[0]['fieldname'] = 'IMEI';
                $CUSTOM[0]['required'] = 1;
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['Requires.Custom'] = $CUSTOM;
            }

            $apiresults['SUCCESS'][] = array('MESSAGE' => 'IMEI Service List', 'LIST' => $ServiceList);
            break;

        case "placeimeiorder":
            $ServiceId = (int)$parameters['ID'];
            $CustomField = json_decode(base64_decode($parameters['customfield']), true);

            // Acesso ao EntityManager para persistir a ordem no banco de dados
            $entityManager = require_once 'bootstrap.php'; 

            // Criar nova ordem
            $order = new \App\Entity\Order();
            $order->setImei($CustomField['imei']); 
            $order->setStatus(1); 

            // Persistir a ordem no banco de dados
            $entityManager->persist($order);
            $entityManager->flush();

            // Retornar o ID da ordem criada
            $order_reff_id = $order->getId(); 

            // Resultado da API
            $apiresults['SUCCESS'][] = array('MESSAGE' => 'Order received', 'REFERENCEID' => $order_reff_id);
            break;

        case "getimeiorder":
            $OrderID = (int)$parameters['ID'];

            // Acesso ao EntityManager para consultar a ordem no banco de dados
            $entityManager = require_once 'bootstrap.php'; 

            // Procurar a ordem com o ID fornecido
            $order = $entityManager->find(\App\Entity\Order::class, $OrderID);

            if ($order) {
                $status = $order->getStatus();
                $apiresults['SUCCESS'][] = array(
                    'STATUS' => $status,  
                    'CODE' => 'CODE'
                );
            } else {
                $apiresults['ERROR'][] = array(
                    'MESSAGE' => 'Order not found'
                );
            }
            break;

        case "getimeiorderbulk":
            foreach ($parameters as $bulkReqId => $OrdersDetails) {
                $OrderID = (int)$OrdersDetails['ID'];
                $apiresults[$bulkReqId]['SUCCESS'][] = array(
                    'STATUS' => 3, 
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

// Enviar o resultado em formato JSON
if (count($apiresults)) {
    header("X-Powered-By: DHRU-FUSION");
    header("dhru-fusion-api-version: $apiversion");
    header('Content-Type: application/json; charset=utf-8');
    $apiresults['apiversion'] = $apiversion;
    exit(json_encode($apiresults));
}
