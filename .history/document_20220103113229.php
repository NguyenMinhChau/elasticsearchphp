<?php
    use Elasticsearch\ClientBuilder;
    require "vendor/autoload.php";
    $hosts = [
        [
            'host' => 'localhost',
            'port' => 9200,
            'scheme' => 'http',
        ]
    ];
    $client = ClientBuilder::create()->setHosts($hosts)->build();
    // kiểm tra index có tồn tại hay không
    $exists = $client->indices()->exists(['index' => 'article']);

    if(!$exists){
        throw new Exception('Chỉ mục bài viết không tồn tại');
    }
    /**
     * Document
     * Title
     * Content
     * keywords
     */
?>
<div class='container mt-3'>
    <h3 class='text-center'>Tạo mới Document trong Elasticsearch</h3>
    <form>
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" class="form-control" id="title" placeholder="Nhập tiêu đề">
        </div>
        <div class="form-group">
            <label for="content">Nhập mô tả</label>
            <textarea class="form-control" id="content"></textarea>
        </div>
        <div class="form-group">
            <label for="keyword">Từ khóa</label>
            <input type="text" class="form-control" id="keyword" placeholder="Nhập tiêu đề">
        </div>
        <button type="submit" class="btn btn-primary">Tạo mới</button>
    </form>
</div>
