<?php

include 'top/TopClient.php';
include 'TopSdk.php';

$c = new TopClient;
$c->appkey;
$c->secretKey;

//Get all orders with status ready to send
$req = new AliexpressSolutionOrderGetRequest;

$param0 = new OrderQuery;

$param0->order_status_list="SELLER_PART_SEND_GOODS";

$param0->page_size="50";

$param0->current_page="1";

$param0->order_status="WAIT_SELLER_SEND_GOODS";

$req->setParam0(json_encode($param0));
$resp = $c->execute($req, $sessionKey);