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
        $mgs = 'Tạo mới thành công cho document có id = '.$id;
        $id = $idDoctor = $fullName = $phoneNumber = $workPlace = $specialist = $address = $keywords = $slug = null;
    }
    //lấy list tất cả id của document
    $params = [
        'index' => 'healthcare_new',
        'type' => '_doc',
        'body' => [
            'query' => [
                'match_all' => new stdClass()
            ],
            'size' => 10000
        ]
    ];
    $response = $client->search($params);
    $listId = [];
    $listIdDoctor = [];
    foreach($response['hits']['hits'] as $item){
        $listId[] = $item['_id'];
    }
    foreach($response['hits']['hits'] as $item){
        $listIdDoctor[] = $item['_source']['id'];
    }
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Cập nhật Document trong Elasticsearch</h5>
    <div class='card-body'>
        <form action='#' method='POST' autocomplete="off">
            <div class="form-group">
                <label for="id">ID Tài liệu</label>
                <select class="form-control" id="id" name="id">
                    <?php
                        foreach($listId as $item){
                            echo "<option value='$item'>$item</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="idDoctor">Mã bác sĩ</label>
                <select class="form-control" id="idDoctor" name="idDoctor">
                    <?php
                        foreach($listIdDoctor as $item){
                            echo "<option value='$item'>$item</option>";
                        }
                    ?>
                </select>
            </div>
            <?php
                foreach($response['hits']['hits'] as $item){
                    if($item['_id'] == $item['_source']['id']){
                        $fullName = $item['_source']['name'];
                        $phoneNumber = $item['_source']['phoneNumber'];
                        $workPlace = $item['_source']['workPlace'];
                        $specialist = $item['_source']['specialist'];
                        $address = $item['_source']['address'];
                        $keywords = $item['_source']['keyWords'];
                        $slug = $item['_source']['slug'];
                    }
                }
            ?>
            <div class="form-group">
                <label for="fullName">Tên bác sĩ</label>
                <input type="text" class="form-control" id="fullName" name="fullName" value="<?=$fullName?>">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Số điện thoại</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?=$phoneNumber?>">
            </div>
            <div class="form-group">
                <label for="workPlace">Nơi làm việc</label>
                <input type="text" class="form-control" id="workPlace" name="workPlace" value="<?=$workPlace?>">
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
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

