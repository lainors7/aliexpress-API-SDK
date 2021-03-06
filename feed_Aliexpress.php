<?php

include 'SDK/top/TopClient.php';
include 'SDK/TopSdk.php';
include 'config.php';
include 'logFunction.php';

$mysqli = new mysqli($host, $user, $password, $db, $port);

/* check the connection */
if ($mysqli->connect_errno) {
    printf("Falló la conexión: %s\n", $mysqli->connect_error);
    exit();
}

//create log file with today date

$log = new Log(date('d_m_Y').'.log', 'feed_Aliexpress.php');

$c = new TopClient;
$c->appkey;
$c->secretKey;

//DOC https://developers.aliexpress.com/en/api.htm?spm=a219a.7386797.0.0.667e9b71LDSRea&source=search&docId=42384&docType=2

$req = new AliexpressSolutionProductListGetRequest;
$x = 1;
$total = 2;

for ($x = 1; $x < $total; $x++) { //search in all pages

    $aeop_a_e_product_list_query = new ItemListQuery;

    $aeop_a_e_product_list_query->current_page = $x;

    $aeop_a_e_product_list_query->page_size = "100";

    $aeop_a_e_product_list_query->product_status_type = "onSelling";

    $req->setAeopAEProductListQuery(json_encode($aeop_a_e_product_list_query));

    $resp = $c->execute($req, $sessionKey);

    $total = $resp->result[0]->total_page; //Total pages

    //$log->log_msg("Total Pages:" . $total);
    //echo "Total Pages's:" . $total;
    
    //echo "\n";

    //var_dump($resp);

    if ($total != 0) {

        $tam = $resp->result[0]->aeop_a_e_product_display_d_t_o_list->item_display_dto->count(); //Size products to loop

        for ($i = 0; $i < $tam; $i++) {
            $ID = $resp->result[0]->aeop_a_e_product_display_d_t_o_list->item_display_dto[$i]->product_id;
            getEAN($ID, $c, $sessionKey, $mysqli, $log);
        }
    } else {
        $log->log_msg("No articles found.");
        //echo "No articles found.";
    }
    $log->log_msg("----------------------End update stocks----------------------");
}

function getEAN($ID, $c, $sessionKey, $mysqli,$log){
    //DOC https://developers.aliexpress.com/en/api.htm?spm=a219a.7386653.0.0.65a99b71oRgD5c&source=search&docId=42383&docType=2
    $req1 = new AliexpressSolutionProductInfoGetRequest;
    $req1->setProductId($ID);
    $resp = $c->execute($req1, $sessionKey);
    //var_dump($resp);

    $tam = $resp->result[0]->aeop_ae_product_s_k_us->global_aeop_ae_product_sku->count();
    //var_dump ( $resp->result[0]->aeop_ae_product_s_k_us->global_aeop_ae_product_sku->ean_code);
    //var_dump ( $resp->result[0]->aeop_ae_product_s_k_us);
    $log->log_msg("EAN Quantity: ". $tam);
    //echo "EAN Quantity: ". $tam;
    for ($i = 0; $i < $tam; $i++) {
        //echo $resp->result[0]->aeop_ae_product_s_k_us->global_aeop_ae_product_sku[$i]->ean_code;
        //echo "\n";
        //LOOP THE EAN TO GET THE STOCK OF EACH
        $sku_code = $resp->result[0]->aeop_ae_product_s_k_us->global_aeop_ae_product_sku[$i]->ean_code;
        $log->log_msg("El sku: ".$sku_code);
        //echo "El sku: ".$sku_code;
        //echo "\n";
        $stock = getStock($sku_code, $mysqli, $log);
        //AND UPDATE STOCK IN ALIEXPRESS
        updateStock($ID, $stock, $sku_code, $c, $sessionKey, $log);
    }
}

function getStock($sku_code, $mysqli, $log){
    //My Personal Function to get Stock from my DB
    $busqueda = $sku_code;

    /*Make the query */
    if ($resultado = $mysqli->query("SELECT int_amount FROM stocks WHERE str_stock_ean = {$busqueda}")) {
        $log->log_msg('The query Return '.$resultado->num_rows.' files.');
        //printf("The query Return %d files.\n", $resultado->num_rows);
        if ($resultado->num_rows != 0) {

            $EAN = mysqli_fetch_row($resultado)[0];
        } else {
            $EAN = 0;
        }

        /* clean */
        $resultado->close();
        return $EAN;
    }


    $mysqli->close();
}

function updateStock($ID, $stock, $sku_code, $c, $sessionKey, $log){
    //DOC https://developers.aliexpress.com/en/api.htm?spm=a219a.7386653.0.0.17b89b716umhk7&source=search&docId=45135&docType=2
    $req2 = new AliexpressSolutionBatchProductInventoryUpdateRequest;

    $mutiple_product_update_list = new SynchronizeProductRequestDto;

    $mutiple_product_update_list->product_id = intval($ID);

    $multiple_sku_update_list = new SynchronizeSkuRequestDto;

    $multiple_sku_update_list->sku_code = strval($sku_code);

    $multiple_sku_update_list->inventory = $stock;

    $mutiple_product_update_list->multiple_sku_update_list = $multiple_sku_update_list;

    $req2->setMutipleProductUpdateList(json_encode($mutiple_product_update_list));
    $log->log_msg("The ID product: " . $ID . " - and the ean: " . $sku_code . " - update with stock: " . $stock);
    //echo "The ID product: " . $ID . " - and the ean: " . $sku_code . " - update with stock: " . $stock;
    //echo "\n";
    
    $resp = $c->execute($req2, $sessionKey);
    //var_dump($resp);
}
