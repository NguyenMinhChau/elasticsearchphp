<?php

use Elasticsearch\ClientBuilder;

require "vendor/autoload.php";
//kiểm tra trạng thái URL bằng curl https
$url9200 = 'http://localhost:9200';
$curl9200 = curl_init($url9200);
curl_setopt($curl9200, CURLOPT_RETURNTRANSFER, true);
$response9200 = curl_exec($curl9200);
$status9200 = curl_getinfo($curl9200, CURLINFO_HTTP_CODE);
curl_close($curl9200);

if ($status9200 == 200) {
    $hosts = [
        [
            'host' => 'localhost',
            'port' => 9200,
            'scheme' => 'http',
        ]
    ];
}
if ($status9200 != 200) {
    echo '<h3 style="background-color: #f8d7da; padding: 15px; border-radius: 8px; text-align: center; font-size: 15px; color: #975057;">Error: Server chưa được kích hoạt. Không thể kết nối đến Elasticsearch</h3>';
    exit();
}
$client = ClientBuilder::create()->setHosts($hosts)->build();
// kiểm tra index có tồn tại hay không
$exists = $client->indices()->exists(['index' => 'healthcare_new']);

if (!$exists) {
    echo '<h5>Error: Chỉ mục healthcare_new không tồn tại</h3>';
    exit();
}
$id = $_POST['id'] ?? null;
$idUpdate = $_POST['idUpdate'] ?? null;
$idUpdateSelect = $_POST['idUpdateSelect'] ?? null;
$idDoctor = $_POST['idDoctor'] ?? null;
$fullName = $_POST['fullName'] ?? null;
$phoneNumber = $_POST['phoneNumber'] ?? null;
$workPlace = $_POST['workPlace'] ?? null;
$specialist = $_POST['specialist'] ?? null;
$address = $_POST['address'] ?? null;
$keywords = $_POST['keywords'] ?? null;
$slug = $_POST['slug'] ?? null;
$toggleSlug = $_POST['toggleSlug'] ?? null;
$readonlySlug = false;
$readonlyId = false;
//kiểm tra input id=slug có readonlySlug hay không
if ($toggleSlug) {
    $readonlySlug = true;
}
if ($id) {
    $readonlyId = true;
}

$mgs = '';
$mgsGet = '';
if ($id != null && $idDoctor != null && $fullName != null && $phoneNumber != null && $workPlace != null && $specialist != null && $address != null && $slug != null && $keywords != null) {
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
            'slug' => $readonlySlug ? ucfirst($slug) : ucfirst($slug) . '_' . $idDoctor,
        ]
    ];
    $client->index($params);
    $mgs = 'Tạo mới/Cập nhật thành công cho document có id = ' . $id;
    $id = $idDoctor = $fullName = $phoneNumber = $workPlace = $specialist = $address = $keywords = $slug = null;
}
//lấy list tất cả id
$params = [
    'index' => 'healthcare_new',
    'type' => '_doc',
    'body' => [
        'query' => [
            'match_all' => (object)[]
        ],
        'size' => 10000
    ]
];
$response = $client->search($params);
$listId = array();
foreach ($response['hits']['hits'] as $hit) {
    $listId[] = $hit;
}
//lấy tài liệu theo $idUpdate
if ($idUpdate != null) {
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
    $workPlace = $document['_source']['officeAddress'] ? $document['_source']['officeAddress'] : $document['_source']['workPlace'];
    $specialist = $document['_source']['specialist'];
    $address = $document['_source']['address'];
    $keywords = $document['_source']['keyWords'];
    $slug = $document['_source']['slug'];
    $mgsGet = 'Lấy dữ liệu thành công cho document có id = ' . $idUpdate;
}
?>
<div class='card m-4'>
    <div class='card-header text-center d-flex'>
        <button class="btn btn-primary btnUpdate"><i class='bx bx-target-lock'></i> Lấy dữ liệu</button>
        <h5 style='margin: auto'>Lấy Document theo ID để cập nhật trong Elasticsearch</h5>
    </div>
    <div class='card-body'>
        <form method='POST' autocomplete='off' id='form-1' name='form-1'>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="idUpdate">ID Tài liệu cập nhật || DoctorID</label>
                        <input type="text" class="form-control" id="idUpdate" name='idUpdate' value='<?= $idUpdate ?>' placeholder='Enter...' />
                    </div>
                </div>
            </div>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php
            if ($mgsGet != '') {
                echo $mgsGet;
            } else {
                echo '<p class="text-center"><span class="year"></span> &copy; Copyright all rights reserved.</p>';
            }
            ?>
        </div>
    </div>
</div>
<div class='card m-4'>
    <div class='card-header text-center d-flex'>
        <button class="btn btn-primary btnRandom"><i class='bx bx-transfer-alt'></i> Random ID</button>
        <h5 style='margin: auto'>Tạo mới/Cập nhật Document trong Elasticsearch</h5>
    </div>
    <div class='card-body'>
        <form method='POST' autocomplete="off" id='form-2' name='form-2'>
            <div class="form-group">
                <label for="id">ID Tài liệu</label>
                <input type="text" class="form-control" id="id" name='id' value='<?= $idUpdate ?>' placeholder="Fullfilled..." readonly>
            </div>
            <div class="form-group">
                <input type="checkbox" id="toggleIdDocument" name="toggleIdDocument" /> Tạo ID theo ý muốn
            </div>
            <div class="form-group">
                <label for="idDoctor">Mã bác sĩ</label>
                <input type="text" class="form-control" id="idDoctor" name='idDoctor' value='<?= $idDoctor ?>' placeholder="Fullfilled..." readonly>
            </div>
            <div class="form-group">
                <label for="fullName">Họ và tên Bác sĩ</label>
                <input type="text" class="form-control" id="fullName" name='fullName' placeholder="Eg: Nguyễn Văn A" value="<?= $fullName ?>">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Số điện thoại</label>
                <input type="text" class="form-control" id="phoneNumber" name='phoneNumber' placeholder="Eg: 0398365404" value="<?= $phoneNumber ?>">
            </div>
            <div class="form-group">
                <label for="workPlace">Nơi làm việc</label>
                <input type="text" class="form-control" id="workPlace" name='workPlace' placeholder="Eg: 235/3 An Dương Vương, Phường 4, Quận 5, TPHCM" value="<?= $workPlace ?>">
            </div>
            <div class="form-group">
                <label for="specialist">Chuyên khoa</label>
                <input type="text" class="form-control" id="specialist" name='specialist' placeholder="Eg: Khoa nội" value="<?= $specialist ?>">
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ nhà</label>
                <input type="text" class="form-control" id="address" name='address' placeholder="Eg: 235/3 An Dương Vương, Phường 4, Quận 5, TPHCM" value="<?= $address ?>">
            </div>
            <div class="form-group">
                <label for="keywords">Từ khóa tìm kiếm</label>
                <input type="text" class="form-control" id="keywords" name='keywords' placeholder="Eg: Nguyễn Văn A, Khoa nội" value="<?= $keywords ?>">
            </div>
            <div class="form-group">
                <label for="slug">
                    Slug
                </label>
                <input type="text" class="form-control" id="slug" name='slug' value="<?= $slug ?>" placeholder="Fullfilled..." readonly>
            </div>
            <div class="form-group">
                <input type="checkbox" id="toggleSlug" name="toggleSlug" /> Chỉnh sửa slug theo ý muốn (Mặc định: Ho_Va_Ten_ID)
            </div>
            <button type="submit" class="btn btn-primary btnCreateUpdate"><i class='bx bx-folder-plus'></i> Tạo mới | Cập nhật</button>
            <button type="reset" class="btn btn-danger btnReset"><i class='bx bx-reset'></i> Reset Form</button>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php
            if ($mgs != '') {
                echo $mgs;
            } else {
                echo '<p class="text-center"><span class="year"></span> &copy; Copyright all rights reserved.</p>';
            }
            ?>
        </div>
    </div>
</div>