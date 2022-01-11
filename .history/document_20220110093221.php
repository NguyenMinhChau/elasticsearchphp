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
    /**
     * Document
     * Title
     * Content
     * keywords
     */
    $id = $_POST['id'] ?? null;
    $mgs = '';
    if($id != null && $title != null && $content != null && $keyword != null){
        //Cập nhật/Tạo mới document
        $params = [
            'index' => 'article',
            'type' => 'article_type',
            'id' => $id,
            'body' => [
                'title' => $title,
                'content' => $content,
                'keyword' => explode(', ', $keyword),
            ]
        ];
        $client->index($params);
        $mgs = 'Tạo mới/Cập nhật thành công cho document có id = '.$id;
        $id = $title = $content = $keyword = null;
    }
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Tạo mới/Cập nhật Document trong Elasticsearch</h5>
    <div class='card-body'>
        <form action='#' method='POST' autocomplete="off">
            <div class="form-group">
                <label for="id">ID Tài liệu</label>
                <input type="text" class="form-control" id="id" name='id' placeholder="ID" value="<?=$id?>">
            </div>
            <div class="form-group">
                <label for="idDoctor">Mã bác sĩ</label>
                <input type="text" class="form-control" id="idDoctor" name='idDoctor' placeholder="Mã bác sĩ" value="<?=$id?>">
            </div>
            <div class="form-group">
                <label for="fullName">Họ và tên Bác sĩ</label>
                <input type="text" class="form-control" id="fullName" name='fullName' placeholder="Họ và tên" value="<?=$id?>">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Số điện thoại</label>
                <input type="text" class="form-control" id="phoneNumber" name='phoneNumber' placeholder="Số điện thoại" value="<?=$title?>">
            </div>
            <div class="form-group">
                <label for="workPlace">Nơi làm việc</label>
                <input type="text" class="form-control" id="workPlace" name='workPlace' placeholder="Nơi làm việc" value="<?=$title?>">
            </div>
            <div class="form-group">
                <label for="specialist">Chuyên khoa</label>
                <input type="text" class="form-control" id="specialist" name='specialist' placeholder="Chuyên khoa" value="<?=$title?>">
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ nhà</label>
                <input type="text" class="form-control" id="address" name='address' placeholder="Địa chỉ nhà" value="<?=$title?>">
            </div>
            <div class="form-group">
                <label for="keyword">Từ khóa tìm kiếm</label>
                <input type="text" class="form-control" id="keyword" name='keyword' placeholder="Từ khóa tìm kiếm" value="<?=$title?>">
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug" name='slug' placeholder="Slug" value="<?=$title?>">
            </div>
            <button type="submit" class="btn btn-primary">Tạo mới | Cập nhật</button>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php echo $mgs?>
        </div>
    </div>
</div>
