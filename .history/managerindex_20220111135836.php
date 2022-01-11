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
    $listIndexs = ['healthcare_new','danhba_yte','healthcare'];
    $listIndexsExits = ['existsHealthcare_new','existsDanhbayte','existsHealthcare'];
    $listMessages = ['msg','msgDanhbayte','msgHealthcare'];
    foreach ($listIndexs as $key => $value) {
        $listIndexsExits[$key] = $client->indices()->exists(['index' => $value]);
        if(!$listIndexsExits[$key]){
            throw new Exception('Chỉ mục '.$value.' không tồn tại');
        }
    }

    // kiểm tra index có tồn tại hay không
    //$existsHealthcare_new = $client->indices()->exists(['index' => 'healthcare_new']);
    //$existsDanhbayte = $client->indices()->exists(['index' => 'danhba_yte']);
    //$existsHealthcare = $client->indices()->exists(['index' => 'healthcare']);

    $action = $_GET['action'] ?? '';
    switch($action) {
        case 'createhealthcare_new':
            if(!$existsHealthcare_new){
                $client->indices()->create(['index' => 'healthcare_new']);
            }
            break;
        case 'deletehealthcare_new':
            if($existsHealthcare_new){
                $client->indices()->delete(['index' => 'healthcare_new']);
            }
            break;
        case 'createdanhba_yte':
            if(!$existsDanhbayte){
                $client->indices()->create(['index' => 'danhba_yte']);
            }
            break;
        case 'deletedanhba_yte':
            if($existsDanhbayte){
                $client->indices()->delete(['index' => 'danhba_yte']);
            }
            break;
        case 'createhealthcare':
            if(!$existsHealthcare){
                $client->indices()->create(['index' => 'healthcare']);
            }
            break;
        case 'deletehealthcare':
            if($existsHealthcare){
                $client->indices()->delete(['index' => 'healthcare']);
            }
            break;
        default:
            break;
    }
    // $existsHealthcare_new = $client->indices()->exists(['index' => 'healthcare_new']);
    // $existsDanhbayte = $client->indices()->exists(['index' => 'danhba_yte']);
    // $existsHealthcare = $client->indices()->exists(['index' => 'healthcare']);
    foreach ($listIndexs as $key => $value) {
        $listIndexsExits[$key] = $client->indices()->exists(['index' => $value]);
        if(!$listIndexsExits[$key]){
            throw new Exception('Chỉ mục '.$value.' không tồn tại');
        }
    }
    $msg = $existsHealthcare_new ? 
        'Chỉ mục healthcare_new đã tồn tại'  : 'Chỉ mục healthcare_new không tồn tại';
    $msgDanhbayte = $existsDanhbayte ? 
        'Chỉ mục danhba_yte đã tồn tại'  : 'Chỉ mục danhba_yte không tồn tại';
    $msgHealthcare = $existsHealthcare ? 
        'Chỉ mục healthcare đã tồn tại'  : 'Chỉ mục healthcare không tồn tại';
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Quản lý chỉ mục</h5>
    <div class='card-body'>
        <div class='alert alert-primary mt-3'>
            <?php echo $msg ?>
        </div>
        <?php if(!$existsHealthcare_new): ?>
            <a class='btn btn-primary' href='http://localhost:8800/?page=managerindex&action=createhealthcare_new'>Tạo chỉ mục healthcare_new</a>
        <?php else: ?>
            <a class='btn btn-danger' href='http://localhost:8800/?page=managerindex&action=deletehealthcare_new'>Xóa chỉ mục healthcare_new</a>
        <?php endif ?>
    </div>
    <div class='card-body'>
        <div class='alert alert-primary mt-3'>
            <?php echo $msgDanhbayte ?>
        </div>
        <?php if(!$existsDanhbayte): ?>
            <a class='btn btn-primary' href='http://localhost:8800/?page=managerindex&action=createdanhba_yte'>Tạo chỉ mục danhba_yte</a>
        <?php else: ?>
            <a class='btn btn-danger' href='http://localhost:8800/?page=managerindex&action=deletedanhba_yte'>Xóa chỉ mục danhba_yte</a>
        <?php endif ?>
    </div>
    <div class='card-body'>
        <div class='alert alert-primary mt-3'>
            <?php echo $msgHealthcare ?>
        </div>
        <?php if(!$existsHealthcare): ?>
            <a class='btn btn-primary' href='http://localhost:8800/?page=managerindex&action=createhealthcare'>Tạo chỉ mục healthcare</a>
        <?php else: ?>
            <a class='btn btn-danger' href='http://localhost:8800/?page=managerindex&action=deletehealthcare'>Xóa chỉ mục healthcare</a>
        <?php endif ?>
    </div>
</div>