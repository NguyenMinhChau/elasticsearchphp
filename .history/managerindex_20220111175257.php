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
    //lấy ra những index không bắt đầu bằng dấu chấm
    $result = array_filter($client->cat()->indices(), function($item) {
        return substr($item['index'], 0, 1) !== '.';
    });
    //lấy ra tên các index trong result
    $indexes = array_column($result, 'index');

    $listIndexs = [...$indexes];
    $listIndexsExits = array();
    $listMessages = array();
    $listsSlug = array();
    foreach ($indexes as $key => $value) {
        $listIndexsExits[$key] = 'exists' . ucfirst($value);
        $listMessages[$key] = 'mgs' . ucfirst($value);
        $listsSlug[$key] = $value;
    }
    $indexText = $_POST['indexText'] ?? null;
    $mgs = '';
    //Tạo mới index
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
            $listMessages[$key] = 'Chỉ mục '.$value.' chưa tồn tại';
        }
    }
    $action = $_GET['action'] ?? '';
    foreach($listIndexs as $key => $value){
        //thực hiện xóa index dựa vào listsSlug
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
                <table class='table table-responsive table-hover'>
                    <thead>
                        <tr>
                            <th>Tên chỉ mục</th>
                            <th>Tình trạng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $value; ?></td>
                            <td>
                                <?php
                                    if($listIndexsExits[$key]){
                                        echo '<span class="badge badge-success">Đã tồn tại</span>';
                                    }else{
                                        echo '<span class="badge badge-danger">Chưa tồn tại</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if($listIndexsExits[$key]){
                                        echo '<a href="http://localhost:8800/?page=managerindex&action=delete'.$listsSlug[$key].'" class="btn btn-danger">Xóa</a>';
                                    }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class='alert alert-primary mt-3'>
                    <?php echo $listMessages[$key] ?>
                </div>
                <?php 
                    if($listIndexsExits[$key]) :?>
                        <a class='btn btn-danger' href='http://localhost:8800/?page=managerindex&action=delete<?=$listsSlug[$key]?>'>Xóa chỉ mục <?=$value?></a>
                    <?php endif ?>
            <?php endforeach ?>
    </div>
</div>