<?php
    use Elasticsearch\ClientBuilder;
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
    $listMessages = ['$msgHealthcare_new','$msgDanhbayte','$msgHealthcare'];
    $listsSlug = ['healthcare_new','danhba_yte','healthcare'];

    $action = $_GET['action'] ?? '';
    foreach($listIndexs as $key => $value){
        switch($action){
            case 'create'.$listsSlug[$key]:
                if(!$listIndexsExits[$key]){
                    $client->indices()->create(['index' => $value]);
                }
            case 'delete'.$listsSlug[$key]:
                if($listIndexsExits[$key]){
                    $client->indices()->delete(['index' => $value]);
                }
            default:
                break;
        }
    }
    foreach ($listIndexsExits as $key => $value) {
        if($value){
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
            foreach ($listIndexsExits as $key => $value) :?>
                <div class='alert alert-primary mt-3'>
                    <?php echo $listMessages[$key] ?>
                </div>
                <?php 
                    if(!$value) :?>
                        <a class='btn btn-primary' href='http://localhost:8800/?page=managerindex&action=create<?=$listsSlug[$key]?>'>Tạo chỉ mục <?=$value?></a>
                    <?php else: ?>
                        <a class='btn btn-danger' href='http://localhost:8800/?page=managerindex&action=delete<?=$listsSlug[$key]?>'>Xóa chỉ mục <?=$value?></a>
                    <?php endif ?>
            <?php endforeach ?>
    </div>
</div>