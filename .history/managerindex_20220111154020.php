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

    foreach ($listIndexs as $key => $value) {
        //kiểm tra index có tồn tại hay không
        $exists = $client->indices()->exists(['index' => $value]);
        if($exists){
            $listIndexsExits[$key] = true;
            $listMessages[$key] = 'Chỉ mục '.$value.' đã tồn tại';
        }else{
            $listIndexsExits[$key] = false;
            $listMessages[$key] = 'Chỉ mục '.$value.' không tồn tại';
        }
    }
    $action = $_GET['action'] ?? '';
    foreach($listIndexs as $key => $value){
        //thực hiện tạo và xóa index dựa vào listsSlug
        if($action == 'create'.$listsSlug[$key]){
            $client->indices()->create(['index' => $value]);
            $listIndexsExits[$key] = true;
        }
        if($action == 'delete'.$listsSlug[$key]){
            $client->indices()->delete(['index' => $value]);
            $listIndexsExits[$key] = false;
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