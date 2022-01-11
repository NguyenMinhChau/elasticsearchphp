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
    $idUpdate = $_POST['idUpdate'] ?? null;
    $idDoctor = $_POST['idDoctor'] ?? null;
    $fullName = $_POST['fullName'] ?? null;
    $phoneNumber = $_POST['phoneNumber'] ?? null;
    $workPlace = $_POST['workPlace'] ?? null;
    $specialist = $_POST['specialist'] ?? null;
    $address = $_POST['address'] ?? null;
    $keywords = $_POST['keywords'] ?? null;
    $slug = $_POST['slug'] ?? null;
    $mgs = ''; $mgsGet= '';
    if($id != null && $idDoctor != null && $fullName != null && $phoneNumber != null && $workPlace != null && $specialist != null && $address != null && $slug != null && $keywords != null){
        //Cập nhật/Tạo mới document
        $params = [
            'index' => 'healthcare_new',
            'type' => '_doc',
            'id' => $id,
            'body' => [
                'id' => $idDoctor,
                'name' => trim(ucfirst($fullName)),
                'phoneNumber' => $phoneNumber,
                'workPlace' => trim(ucfirst($workPlace)),
                'specialist' => trim(ucfirst($specialist)),
                'address' => trim(ucfirst($address)),
                'keyWords' => trim(ucfirst($keywords)),
                'slug' => ucfirst($slug) . '_' . $idDoctor,
            ]
        ];
        $client->index($params);
        $mgs = 'Tạo mới/Cập nhật thành công cho document có id = '.$id;
        $id = $idDoctor = $fullName = $phoneNumber = $workPlace = $specialist = $address = $keywords = $slug = null;
    }
    //lấy tài liệu theo $idUpdate
    if($idUpdate != null){
        $params = [
            'index' => 'healthcare_new',
            'type' => '_doc',
            'id' => $idUpdate,
        ];
        $document = $client->get($params);
        $id = $document['_source']['id'];
        $idDoctor = $document['_source']['id'];
        $fullName = $document['_source']['name'];
        $phoneNumber = $document['_source']['phoneNumber'];
        $workPlace = $document['_source']['workPlace'];
        $specialist = $document['_source']['specialist'];
        $address = $document['_source']['address'];
        $keywords = $document['_source']['keyWords'];
        $slug = $document['_source']['slug'];
        $mgsGet = 'Lấy dữ liệu thành công cho document có id = '.$id;
    }
?>
<div class='card m-4'>
    <div class='card-header text-center d-flex'>
        <button class="btn btn-primary btnUpdate">Lấy dữ liệu</button>
        <h5 style='margin: auto'>Lấy Document theo ID trong Elasticsearch</h5>
    </div>
    <div class='card-body'>
        <form method='POST' autocomplete='off' id='form-1' name='form-1'>
            <div class="form-group">
                <label for="id">ID Tài liệu cập nhật</label>
                <input type="text" class="form-control" id="idUpdate" name='idUpdate' value='<?=$idUpdate?>' placeholder='Enter...'/>
            </div>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php 
                if($mgsGet != ''){
                    echo $mgsGet;
                }else{
                    echo '<p class="text-center">Copyright &copy; <span class="year"></span> Elasticsearch.</p>';
                }
            ?>
        </div>
    </div>
</div>
<div class='card m-4'>
    <div class='card-header text-center d-flex'>
        <button class="btn btn-primary btnRandom">Random ID</button>
        <h5 style='margin: auto'>Tạo mới/Cập nhật Document trong Elasticsearch</h5>
    </div>
    <div class='card-body'>
        <form action='#' method='POST' autocomplete="off" id='form-2' name='form-2'>
            <div class="form-group">
                <label for="id">ID Tài liệu</label>
                <input type="text" class="form-control" id="id" name='id' value='<?=$id?>' placeholder="Fullfluid...">
            </div>
            <div class="form-group">
                <label for="idDoctor">Mã bác sĩ</label>
                <input type="text" class="form-control" id="idDoctor" name='idDoctor' value='<?=$idDoctor?>' placeholder="Fullfluid...">
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
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug" name='slug' value="<?=$slug?>"  placeholder="Fullfluid...">
            </div>
            <button type="submit" class="btn btn-primary btnCreateUpdate">Tạo mới | Cập nhật</button>
            <button type="reset" class="btn btn-danger btnReset">Reset Form</button>
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

