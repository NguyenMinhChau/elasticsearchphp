<?php
    use Elasticsearch\ClientBuilder;
    /**
     * Kết nối với ElasticSearch
     * Tạo / xóa index: Bài viết
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
    // $indices = $client->cat()->indices();
    // var_dump($indices);

    $action = $_GET['action'] ?? '';
    if($action === 'create') {
        $params = [
            'index' => 'article',
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0
                ]
            ]
        ];
        $response = $client->indices()->create($params);
        var_dump($response);
    } else if($action === 'delete') {
        $params = [
            'index' => 'article'
        ];
        $response = $client->indices()->delete($params);
        var_dump($response);
    }
?>
<div class='card m-4'>
    <div class='card-header text-center'>Quản lý chỉ mục</div>
    <div class='card-body'>
        <a class='btn btn-primary' href='http://localhost:8800/?page=managerindex&action=create'>Tạo chỉ mục bài viết</a>
        <a class='btn btn-danger' href='http://localhost:8800/?page=managerindex&action=delete'>Xóa chỉ mục bài viết</a>
    </div>
</div>