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
            $listMessages[$key] = 'Đang hoạt động';
        }
    }
    $action = $_GET['action'] ?? '';
    foreach($listIndexs as $key => $value){
        //thực hiện xóa index dựa vào listsSlug
        if($action == 'delete'.$listsSlug[$key]){
            $params = [
                'index' => $value,
            ];
            $client->indices()->delete($params);
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
            <button type='submit' class='btn btn-primary'><i class='bx bx-folder-plus'></i> Tạo mới</button>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php 
                if($mgs != ''){
                    echo $mgs;
                }else{
                    echo '<p class="text-center"><span class="year"></span> &copy; Copyright all rights reserved.</p>';
                }
            ?>
        </div>
    </div>
</div>
<div class='card m-4'>
    <h5 class='card-header text-center'>Quản lý chỉ mục</h5>
    <div class='card-body'>
        <table class='table table-striped table-bordered'>
            <thead class='text-center'>
                <tr>
                    <th>STT</th>
                    <th>Tên chỉ mục</th>
                    <th>Kích thước</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody class='text-center'>
                <?php
                    foreach($listIndexs as $key => $value){
                        echo '<tr>';
                        echo '<td>'.($key+1).'</td>';
                        echo '<td>'.$value.'</td>';
                        echo '<td>'.(($client->indices()->stats(['index' => $value])['indices'][$value]['total']['store']['size_in_bytes'] / 1024)) .'</td>';
                        echo '<td>
                            <span class="badge badge-success">
                                '.$listMessages[$key].' <span class="badge badge-light">'.$client->count(['index' => $value])['count'].' docs</span>
                            </span>
                        </td>';
                        echo '<td>';
                        if($listIndexsExits[$key]){
                            echo '<a href="http://localhost:8800/?page=managerindex&action=delete'.$listsSlug[$key].'" class="btn btn-outline-danger"><i class="bx bx-folder-minus"></i> Xóa chỉ mục</a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    document.querySelector('.year').innerHTML = new Date().getFullYear();
</script>