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
                'name' => ucfirst($fullName),
                'phoneNumber' => $phoneNumber,
                'workPlace' => ucfirst($workPlace),
                'specialist' => ucfirst($specialist),
                'address' => ucfirst($address),
                'keyWords' => ucfirst($keywords),
                'slug' => str_replace(' ', '_', ucfirst($slug)) . '_' . $idDoctor,
            ]
        ];
        $client->index($params);
        $mgs = 'Tạo mới/Cập nhật thành công cho document có id = '.$id;
        $id = $idDoctor = $fullName = $phoneNumber = $workPlace = $specialist = $address = $keywords = $slug = null;
    }
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Tạo mới/Cập nhật Document trong Elasticsearch</h5>
    <div class='card-body'>
        <form action='#' method='POST' autocomplete='off'>
            <div class="form-group">
                <label for="id">ID Tài liệu</label>
                <input type="text" class="form-control" id="idUpdate" name='idUpdate'>
            </div>
            <button type="button" class="btn btn-primary">Lấy dữ liệu</button>
        </form>
        <hr/>
        <form action='#' method='POST' autocomplete="off">
            <div class="form-group">
                <label for="id">ID Tài liệu</label>
                <input type="text" class="form-control" id="id" name='id'>
            </div>
            <div class="form-group">
                <label for="idDoctor">Mã bác sĩ</label>
                <input type="text" class="form-control" id="idDoctor" name='idDoctor'>
            </div>
            <div class="form-group">
                <label for="fullName">Họ và tên Bác sĩ</label>
                <input type="text" class="form-control" id="fullName" name='fullName' placeholder="Eg: Nguyễn Văn A" value="<?=$fullName?>">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Số điện thoại</label>
                <input type="text" class="form-control" id="phoneNumber" name='phoneNumber' placeholder="Eg: 0398365404" value="<?=$phoneNumber?>">
            </div>
            <div class="form-group">
                <label for="workPlace">Nơi làm việc</label>
                <input type="text" class="form-control" id="workPlace" name='workPlace' placeholder="Eg: 235/3 An Dương Vương, Phường 4, Quận 5, TPHCM" value="<?=$workPlace?>">
            </div>
            <div class="form-group">
                <label for="specialist">Chuyên khoa</label>
                <input type="text" class="form-control" id="specialist" name='specialist' placeholder="Eg: Khoa nội" value="<?=$specialist?>">
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ nhà</label>
                <input type="text" class="form-control" id="address" name='address' placeholder="Eg: 235/3 An Dương Vương, Phường 4, Quận 5, TPHCM" value="<?=$address?>">
            </div>
            <div class="form-group">
                <label for="keywords">Từ khóa tìm kiếm</label>
                <input type="text" class="form-control" id="keywords" name='keywords' placeholder="Eg: Nguyễn Văn A, Khoa nội" value="<?=$keywords?>">
            </div>
            <div class="form-group">
                <label for="slug">Slug [Không dấu]</label>
                <input type="text" class="form-control" id="slug" name='slug' placeholder="Eg: Nguyen Van A" value="<?=$slug?>">
            </div>
            <button type="submit" class="btn btn-primary">Tạo mới | Cập nhật</button>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php 
                if($mgs != ''){
                    echo $mgs;
                }else{
                    echo '<p class="text-center">Copyright &copy; <span id="year"></span> Elasticsearch.</p>';
                }
            ?>
        </div>
    </div>
</div>

