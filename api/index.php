<?php
/**
 * DHRU Fusion api standards V6.1
 */


 require_once __DIR__ . "/bootstrap.php";

 
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

                /*Custom Fields*/
                $CUSTOM = array();
                {
                    $CUSTOM[0]['type'] = 'serviceimei';
                    $CUSTOM[0]['fieldname'] = 'IMEI';
                  
                    $CUSTOM[0]['description'] = '';
                    $CUSTOM[0]['fieldoptions'] = '';
                    $CUSTOM[0]['required'] = 1;

                  
                }
                $ServiceList[$Group]['SERVICES'][$SERVICEID]['Requires.Custom'] = $CUSTOM;
            }


            $apiresults['SUCCESS'][] = array('MESSAGE' => 'IMEI Service List', 'LIST' => $ServiceList);
            break;



            case "placeimeiorder":
                // Parâmetros recebidos
                $ServiceId = (int)$parameters['ID'];
                $CustomField = json_decode(base64_decode($parameters['customfield']), true);
            
                // Criação do arquivo de log na raiz do projeto
                $logFile = __DIR__ . '/order_log.txt'; // Caminho absoluto para o arquivo de log
                $logMessage = "[" . date('Y-m-d H:i:s') . "] - Place IMEI Order Request\n";
                $logMessage .= "ServiceId: $ServiceId\n";
                $logMessage .= "CustomField: " . print_r($CustomField, true) . "\n";
                
                // Acesso ao EntityManager para persistir a ordem no banco de dados
                $entityManager = require_once 'bootstrap.php'; // Acesso ao EntityManager configurado previamente
                
                // Criar nova ordem e salvar o IMEI
                $order = new \App\Entities\Order();
                $order->setImei($CustomField['imei']); // Salva o IMEI no campo imei
                $order->setStatus(1); // Define o status como 1 antes de salvar
                
                // Log de criação da ordem
                $logMessage .= "Creating order with IMEI: " . $CustomField['imei'] . "\n";
                $logMessage .= "Setting status to: 1\n";
            
                // Persistir a ordem no banco de dados
                try {
                    $entityManager->persist($order);
                    $entityManager->flush();
                    
                    // Retornar o ID da ordem criada
                    $order_reff_id = $order->getId(); 
                    
                    // Log de sucesso
                    $logMessage .= "Order successfully created with ID: $order_reff_id\n";
                    $logMessage .= "Status set to: 1\n";
            
                    // Resultado da API
                    $apiresults['SUCCESS'][] = array('MESSAGE' => 'Order received', 'REFERENCEID' => $order_reff_id);
                } catch (\Exception $e) {
                    // Log de erro
                    $logMessage .= "Error: " . $e->getMessage() . "\n";
                    $apiresults['ERROR'][] = array('MESSAGE' => 'Failed to create order', 'ERROR' => $e->getMessage());
                }
                
                // Escrever no arquivo de log
                file_put_contents($logFile, $logMessage, FILE_APPEND);
                break;
            
            

       

                case "getimeiorder":
                    $OrderID = (int)$parameters['ID'];
                
                    // Acesso ao EntityManager para consultar a ordem no banco de dados
                    $entityManager = require_once 'bootstrap.php'; // Acesso ao EntityManager configurado previamente
                
                    // Procurar a ordem com o ID fornecido
                    $order = $entityManager->find(\App\Entities\Order::class, $OrderID);
                
                    // Verificar se a ordem foi encontrada
                    if ($order) {
                        // Obter o status da ordem
                        $status = $order->getStatus();
                
                        // Retornar o número do status e código
                        $apiresults['SUCCESS'][] = array(
                            'STATUS' => $status,  // Retorna o número do status
                            'CODE' => 'CODE'  // Pode ser substituído por um código dinâmico se necessário
                        );
                    } else {
                        // Caso a ordem não seja encontrada, retorna erro
                        $apiresults['ERROR'][] = array(
                            'MESSAGE' => 'Order not found'
                        );
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