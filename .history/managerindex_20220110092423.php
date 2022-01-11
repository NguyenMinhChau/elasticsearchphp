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
            'port' => 9299,
            'scheme' => 'http',
        ]
    ];
    $client = ClientBuilder::create()->setHosts($hosts)->build();
    // kiểm tra index có tồn tại hay không
    $exists = $client->indices()->exists(['index' => 'healthcare_new']);
    // $indices = $client->cat()->indices();
    // var_dump($indices);

    $action = $_GET['action'] ?? '';
    if($action === 'create') {
        // Tạo index
        if(!$exists){
            $client->indices()->create(['index' => 'healthcare_new']);
        }
    } else if($action === 'delete') {
        // Xóa index
        if($exists){
            $client->indices()->delete(['index' => 'healthcare_new']);
        }
    }
    $exists = $client->indices()->exists(['index' => 'healthcare_new']);
    $msg = $exists ? 'Chỉ mục bài viết đã tồn tại'  : 'Chỉ mục bài viết không tồn tại';
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Quản lý chỉ mục</h5>
    <div class='card-body'>
        <div class='alert alert-primary mt-3'>
            <?php echo $msg ?>
        </div>
        <?php if(!$exists): ?>
            <a class='btn btn-primary' href='http://localhost:8800/?page=managerindex&action=create'>Tạo chỉ mục healthcare_new</a>
        <?php else: ?>
            <a class='btn btn-danger' href='http://localhost:8800/?page=managerindex&action=delete'>Xóa chỉ mục healthcare_new</a>
        <?php endif ?>
    </div>
</div>