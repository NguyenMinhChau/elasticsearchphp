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
    // kiểm tra index có tồn tại hay không
    $exists = $client->indices()->exists(['index' => 'article']);
    // $indices = $client->cat()->indices();
    // var_dump($indices);

    $action = $_GET['action'] ?? '';
    if($action === 'create') {
        // Tạo index
        if(!$exists){
            $client->indices()->create(['index' => 'article']);
        }
    } else if($action === 'delete') {
        $params = [
            'index' => 'article'
        ];
        $response = $client->indices()->delete($params);
    }
?>
<div class='card m-4'>
    <div class='card-header text-center'>Quản lý chỉ mục</div>
    <div class='card-body'>
        <?php if($exists): ?>
            <a class='btn btn-primary' href='http://localhost:8800/?page=managerindex&action=create'>Tạo chỉ mục bài viết</a>
        <?php else: ?>
            <a class='btn btn-danger' href='http://localhost:8800/?page=managerindex&action=delete'>Xóa chỉ mục bài viết</a>
        <?php endif; ?>

    </div>
</div>