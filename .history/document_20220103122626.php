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
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;
    $content = $_POST['content'] ?? null;
    $keyword = $_POST['keyword'] ?? null;
    var_dump($id, $title, $content, $keyword);
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Tạo mới/Cập nhật Document trong Elasticsearch</h5>
    <div class='card-body'>
        <form action='#' method='POST' autocomplete="off">
            <div class="form-group">
                <label for="title">ID Document</label>
                <input type="text" class="form-control" id="id" name='id' placeholder="ID">
            </div>
            <div class="form-group">
                <label for="title">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name='title' placeholder="Nhập tiêu đề">
            </div>
            <div class="form-group">
                <label for="content">Nhập mô tả</label>
                <textarea class="form-control" id="content" name='content'></textarea>
            </div>
            <div class="form-group">
                <label for="keyword">Từ khóa</label>
                <input type="text" class="form-control" id="keyword" name='keyword' placeholder="Nhập tiêu đề">
            </div>
            <button type="submit" class="btn btn-primary">Tạo mới</button>
        </form>
    </div>
</div>
