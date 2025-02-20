<?php
/**
 * DHRU Fusion api standards V6.1
 */

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
            $ServiceId = (int)$parameters['ID'];
            $CustomField = json_decode(base64_decode($parameters['customfield']), true);

            if (validateCredits($User, $credit)) {

                /*  Process order and ger order reference id*/
                $order_reff_id = 2323;

                $apiresults['SUCCESS'][] = array('MESSAGE' => 'Order received', 'REFERENCEID' => $order_reff_id);


            } else {
                $apiresults['ERROR'][] = array('MESSAGE' => 'Not enough credits');

            }
            break;

       

        case "getimeiorder":
            $OrderID = (int)$parameters['ID'];
            $apiresults['SUCCESS'][] = array(
                'STATUS' => 4, /* 0 - New , 1 - InProcess, 3 - Reject(Refund), 4- Available(Success)  */
                'CODE' => 'CODE');
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