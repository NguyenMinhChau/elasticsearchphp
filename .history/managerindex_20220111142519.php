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
    $listIndexsExits = ['$existsHealthcare_new','$existsDanhbayte','$existsHealthcare'];
    $listMessages = ['$msg','$msgDanhbayte','$msgHealthcare'];
    $listsSlug = ['healthcare_new','danhba_yte','healthcare'];
    
    foreach ($listIndexs as $key => $value) {
        $listIndexsExits[$key] = $client->indices()->exists(['index' => $value]);
        if(!$listIndexsExits[$key]){
            throw new Exception('Chỉ mục '.$value.' không tồn tại');
        }
    }

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
    foreach ($listIndexs as $key => $value) {
        if($listIndexsExits[$key]){
            $listMessages[$key] = 'Chỉ mục '.$listIndexs[$key].' đã tồn tại';
        }else{
            $listMessages[$key] = 'Chỉ mục '.$listIndexs[$key].' chưa tồn tại';
        }
    }
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Quản lý chỉ mục</h5>
    <div class='card-body'>
        <?php
            foreach ($listIndexs as $key => $value) :?>
                <div class='alert alert-primary mt-3'>
                    <?php echo $listMessages[$key] ?>
                </div>
                <?php 
                    if(!$listIndexsExits[$key]) :?>
                        <a class='btn btn-primary' href='http://localhost:8800/?page=managerindex&action=create<?=$listsSlug[$key]?>'>Tạo chỉ mục <?=$value?></a>
                    <?php else: ?>
                        <a class='btn btn-danger' href='http://localhost:8800/?page=managerindex&action=delete<?=$listsSlug[$key]?>'>Xóa chỉ mục <?=$value?></a>
                    <?php endif ?>
            <?php endforeach ?>
    </div>
</div>