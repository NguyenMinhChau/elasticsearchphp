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
$search = $_POST['search'] ?? null;
$mgs = '';
if ($search != '') {
    $params = [
        'index' => 'healthcare_new',
        'type' => '_doc',
        'id' => $search,
    ];
    $client->delete($params);
    $mgs = 'Xóa thành công bác sĩ có id tài liệu = ' . $search;
}
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Xóa bác sĩ theo ID tài liệu trong Elasticsearch</h5>
    <div class='card-body'>
        <form method='POST' autocomplete="off">
            <div class="form-group">
                <label for="search">Nhập ID tài liệu muốn xóa</label>
                <input type="text" class="form-control" id="search" name='search' placeholder="Nhập ID tài liệu" value="<?= $search ?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class='bx bx-folder-minus'></i> Xóa tài liệu</button>
        </form>
        <div class='alert alert-primary mt-2'>
            <?php
            if ($mgs) {
                echo $mgs;
            } else {
                echo '<p class="text-center"><span class="year"></span> &copy; Copyright all rights reserved.';
            }
            ?>
        </div>
    </div>
</div>