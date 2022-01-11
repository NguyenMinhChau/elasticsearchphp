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
    /*
    */
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Tìm kiếm Document trong Elasticsearch</h5>
    <div class='card-body'>
        <form action='#' method='POST' autocomplete="off">
            <div class="form-group">
                <label for="title">ID Document</label>
                <input type="text" class="form-control" id="id" name='id' placeholder="ID" value="<?=$id?>">
            </div>
            <div class="form-group">
                <label for="title">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name='title' placeholder="Nhập tiêu đề" value="<?=$title?>">
            </div>
            <div class="form-group">
                <label for="content">Nhập mô tả</label>
                <textarea class="form-control" id="content" name='content'><?=$content?></textarea>
            </div>
            <div class="form-group">
                <label for="keyword">Từ khóa</label>
                <input type="text" class="form-control" id="keyword" name='keyword' placeholder="Nhập tiêu đề" value="<?=$keyword?>">
            </div>
            <button type="submit" class="btn btn-primary">Tạo mới | Cập nhật</button>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php echo $mgs?>
        </div>
    </div>
</div>
