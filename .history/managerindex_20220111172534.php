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
    //lấy ra những index không bắt đầu bằng dâu chấm
    $result = array_filter($client->cat()->indices(), function($item) {
        return substr($item['index'], 0, 1) !== '.';
    });
    //lấy ra tên các index trong result
    $indexes = array_column($result, 'index');

    $listIndexs1 = [...$indexes];
    $listIndexsExits1 = array();
    foreach ($indexes as $key => $value) {
        $listIndexsExits1[$key] = 'exists' . ucfirst($value);
    }
    $listMessages1 = array();
    foreach ($indexes as $key => $value) {
        $listMessages1[$key] = 'mgs' . ucfirst($value);
    }
    $listsSlug1 = array();
    foreach ($indexes as $key => $value) {
        $listsSlug1[$key] = 'slug' . ucfirst($value);
    }
    var_dump($listMessages1);

    $indexText = $_POST['indexText'] ?? null;
    $mgs = '';
    // kiểm tra index có tồn tại hay không
    $listIndexs = ['healthcare_new','danhba_yte','healthcare'];
    $listIndexsExits = ['$existsHealthcare_new','$existsDanhbayte','$existsHealthcare'];
    $listMessages = ['$msgHealthcare_new','$msgDanhbayte','$msgHealthcare'];
    $listsSlug = ['healthcare_new','danhba_yte','healthcare'];
    if($indexText){
        $params = [
            'index' => $indexText,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0
                ]
            ]
        ];
        $client->indices()->create($params);
        $mgs = 'Tạo mới thành công index có tên là '.$indexText;
    }

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
    <h5 class='card-header text-center'>Tạo mới chỉ mục</h5>
    <div class='card-body'>
        <form action='' method='post' autocomplete='off'>
            <div class='form-group'>
                <label for='indexText'>Tên chỉ mục</label>
                <input type='text' class='form-control' id='indexText' name='indexText' placeholder='Nhập tên chỉ mục'/>
            </div>
            <button type='submit' class='btn btn-primary'>Tạo mới</button>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php 
                if($mgs != ''){
                    echo $mgs;
                }else{
                    echo '<p class="text-center">Copyright &copy; <span class="year"></span> Elasticsearch.</p>';
                }
            ?>
        </div>
    </div>
</div>
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