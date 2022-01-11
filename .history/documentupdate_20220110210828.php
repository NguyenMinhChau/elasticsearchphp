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
    //lấy dữ liệu theo id document
    $params = [
        'index' => 'healthcare_new',
        'type' => '_doc',
        'id' => $id
    ];
    $response = $client->get($params);
    $id = $response['_source']['id'];
    $fullName = $response['_source']['name'];
    $phoneNumber = $response['_source']['phoneNumber'];
    $workPlace = $response['_source']['workPlace'];
    $specialist = $response['_source']['specialist'];
    $address = $response['_source']['address'];
    $keywords = $response['_source']['keyWords'];
    $slug = $response['_source']['slug'];
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Cập nhật Document trong Elasticsearch</h5>
    <div class='card-body'>
    <form action='#' method='POST' autocomplete="off">
        <div class='form-group'>
            <label for='id'>ID</label>
            <select class='form-control' id="id" name='id'>
                <option value=''>Chọn ID</option>
                <?php
                    foreach($listId as $item){
                        echo "<option value='$item' ".($item == $id ? 'selected' : '').">$item</option>";
                    }
                ?>
            </select>
        </div>
        <div class='form-group'>
            <label for='idDoctor'>ID Doctor</label>
            <select class='form-control' id='idDoctor' name='idDoctor'>
                <option value=''>Chọn ID Doctor</option>
                <?php
                    foreach($listIdDoctor as $item){
                        echo "<option value='$item' ".($item == $idDoctor ? 'selected' : '').">$item</option>";
                    }
                ?>
            </select>
        </div>
        
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

