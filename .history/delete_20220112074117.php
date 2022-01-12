<?php
    use Elasticsearch\ClientBuilder;
    require "vendor/autoload.php";
    $hosts = [
        [
            'host' => 'localhost',
            'port' => 9299 ? 9299 : 9200,
            'scheme' => 'http',
        ]
    ];
    $client = ClientBuilder::create()->setHosts($hosts)->build();
    // kiểm tra index có tồn tại hay không
    $exists = $client->indices()->exists(['index' => 'healthcare_new']);

    if(!$exists){
        throw new Exception('Chỉ mục healthcare_new không tồn tại');
    }
    $search = $_POST['search'] ?? null;
    $mgs = '';
    if($search != ''){
        $params = [
            'index' => 'healthcare_new',
            'type' => '_doc',
            'id' => $search,
        ];
        $client->delete($params);
        $mgs = 'Xóa thành công bác sĩ có id tài liệu = '.$search;
    }
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Xóa bác sĩ theo ID tài liệu trong Elasticsearch</h5>
    <div class='card-body'>
        <form method='POST' autocomplete="off">
            <div class="form-group">
                <label for="search">Nhập ID tài liệu muốn xóa</label>
                <input type="text" class="form-control" id="search" name='search' placeholder="Nhập ID tài liệu" value="<?=$search?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class='bx bx-folder-minus'></i> Xóa tài liệu</button>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php 
                if($mgs){
                    echo $mgs;
                }else{
                    echo '<p class="text-center"><span class="year"></span> &copy; Copyright all rights reserved.';
                }
            ?>
        </div>
    </div>
</div>