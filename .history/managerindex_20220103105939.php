<?php
    /**
     * Kết nối với ElasticSearch
     * Tạo / xóa index: article
     */
    require "vendor/autoload.php";
    $hosts = [
        
    ];
    $client = ClientBuilder::create()->setHosts($hosts)->build();
?>