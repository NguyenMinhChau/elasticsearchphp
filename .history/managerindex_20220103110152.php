<?php
    use Elasticsearch\ClientBuilder;
    /**
     * Kết nối với ElasticSearch
     * Tạo / xóa index: article
     */
    require "vendor/autoload.php";
    $hosts = [
        [
            'host' => 'localhost',
            'port' => 9200,
            'scheme' => 'http',
        ]
    ];
    $client = ClientBuilder::create()->setHosts($hosts)->build();
    $indices = $client->cat()->indices();
    var_dump($indices);
?>