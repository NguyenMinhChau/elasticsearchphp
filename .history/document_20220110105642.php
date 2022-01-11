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
    $idDoctor = $_POST['idDoctor'] ?? null;
    $fullName = $_POST['fullName'] ?? null;
    $phoneNumber = $_POST['phoneNumber'] ?? null;
    $workPlace = $_POST['workPlace'] ?? null;
    $specialist = $_POST['specialist'] ?? null;
    $address = $_POST['address'] ?? null;
    $keywords = $_POST['keywords'] ?? null;
    $slug = $_POST['slug'] ?? null;
    $mgs = '';
    if($id != null && $idDoctor != null && $fullName != null && $phoneNumber != null && $workPlace != null && $specialist != null && $address != null && $slug != null && $keywords != null){
        //Cập nhật/Tạo mới document
        $params = [
            'index' => 'healthcare_new',
            'type' => '_doc',
            'id' => $id,
            'body' => [
                'id' => $idDoctor,
                'name' => $fullName,
                'phoneNumber' => $phoneNumber,
                'workPlace' => $workPlace,
                'specialist' => $specialist,
                'address' => $address,
                'keyWords' => $keywords,
                'slug' => str_replace(' ', '_', $slug),
            ]
        ];
        $client->index($params);
        $mgs = 'Tạo mới/Cập nhật thành công cho document có id = '.$id;
        $id = $title = $content = $keyword = null;
    }
?>
<style>
    #slug{
        text-transform: capitalize;
    }
</style>
<script>
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var result = '';
    for (var i = 0; i < Math.floor(Math.random() * characters.length); i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    document.getElementById('id').value = result;
</script>
<div class='card m-4'>
    <h5 class='card-header text-center'>Tạo mới/Cập nhật Document trong Elasticsearch</h5>
    <div class='card-body'>
        <form action='#' method='POST' autocomplete="off">
            <div class="form-group">
                <label for="id">ID Tài liệu</label>
                <input type="text" class="form-control" id="id" name='id' placeholder="ID">
            </div>
            <div class="form-group">
                <label for="idDoctor">Mã bác sĩ</label>
                <input type="text" class="form-control" id="idDoctor" name='idDoctor' placeholder="Mã bác sĩ" value="<?=$idDoctor?>">
            </div>
            <div class="form-group">
                <label for="fullName">Họ và tên Bác sĩ</label>
                <input type="text" class="form-control" id="fullName" name='fullName' placeholder="Họ và tên" value="<?=$fullName?>">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Số điện thoại</label>
                <input type="text" class="form-control" id="phoneNumber" name='phoneNumber' placeholder="Số điện thoại" value="<?=$phoneNumber?>">
            </div>
            <div class="form-group">
                <label for="workPlace">Nơi làm việc</label>
                <input type="text" class="form-control" id="workPlace" name='workPlace' placeholder="Nơi làm việc" value="<?=$workPlace?>">
            </div>
            <div class="form-group">
                <label for="specialist">Chuyên khoa</label>
                <input type="text" class="form-control" id="specialist" name='specialist' placeholder="Chuyên khoa" value="<?=$specialist?>">
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ nhà</label>
                <input type="text" class="form-control" id="address" name='address' placeholder="Địa chỉ nhà" value="<?=$address?>">
            </div>
            <div class="form-group">
                <label for="keywords">Từ khóa tìm kiếm</label>
                <input type="text" class="form-control" id="keywords" name='keywords' placeholder="Từ khóa tìm kiếm" value="<?=$keywords?>">
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug" name='slug' placeholder="Slug" value="<?=$slug?>">
            </div>
            <button type="submit" class="btn btn-primary">Tạo mới | Cập nhật</button>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php echo $mgs?>
        </div>
    </div>
</div>
