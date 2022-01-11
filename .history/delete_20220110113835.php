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
    $exists = $client->indices()->exists(['index' => 'healthcare_new']);

    if(!$exists){
        throw new Exception('Chỉ mục healthcare_new không tồn tại');
    }
    $search = $_POST['search'] ?? null;
    if($search != ''){
        $params = [
            'index' => 'healthcare_new',
            'type' => '_doc',
            'body' => [
                
            ]
        ];
        $result = $client->search($params);
    }
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Tìm kiếm Document trong Elasticsearch</h5>
    <div class='card-body'>
        <form method='POST' autocomplete="off">
            <div class="form-group">
                <label for="search">Nhập ID tài liệu muốn xóa</label>
                <input type="text" class="form-control" id="search" name='search' placeholder="Nhập nội dung tìm kiếm" value="<?=$search?>">
            </div>
            <button type="submit" class="btn btn-primary">Xóa tài liệu</button>
        </form>
    </div>
</div>
