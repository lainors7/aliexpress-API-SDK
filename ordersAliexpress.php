<?php
//WORK IN PROGRESS
//Develop

include 'SDK/top/TopClient.php';
include 'SDK/TopSdk.php';
include 'config.php';
include 'logFunction.php';

$c = new TopClient;
$c->appkey;
$c->secretKey;

//create log file with today date

$log = new Log(date('d_m_Y') . '.log', 'orders_Aliexpress.php');
$log->log_msg("-------------------Start looking for orders-------------------");
//Get all orders with status ready to send
//DOC https://developers.aliexpress.com/en/doc.htm?docId=42270&docType=2
$req = new AliexpressSolutionOrderGetRequest;

$param0 = new OrderQuery;

$param0->create_date_start = date('Y-01-01 00:00:01'); //always search in this year, from 01/01 to today

$param0->order_status_list = "SELLER_PART_SEND_GOODS";

$param0->page_size = "20";

$param0->current_page = "1";

$req->setParam0(json_encode($param0));
$resp = $c->execute($req, $sessionKey);

if ($resp->result[0]->total_count > 0) {
    //We need to loop all orders

    //Get the deail of each order.
    //DOC https://developers.aliexpress.com/en/doc.htm?docId=42707&docType=2

    //We need to loop al product in each order

    //Then format and put in a table to move after to a comunication table to our ERP or similar.

    //Get info to labels
    //DOC https://developers.aliexpress.com/en/doc.htm?docId=42369&docType=2

    //Call our function to make the label and save it in a folder to after print it

} else {
    $log->log_msg("No orders found.");
}


var_dump($resp);
$log->log_msg("----------------------End importing orders----------------------");
