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
        <form action='documentdelete.php' method='post'>
            <div class='form-group'>
                <label for='id'>ID</label>
                <select class='form-control' name='id' id='id'>
                    <option value='' selected disabled>Chọn ID</option>
                    <?php
                        foreach($listId as $item){
                            echo "<option value='$item'>$item</option>";
                        }
                    ?>
                </select>
            </div>
            <div class='form-group'>
                <label for='idDoctor'>ID Doctor</label>
                <select class='form-control' name='idDoctor' id='idDoctor'>
                    <option value='' selected disabled>Chọn ID Doctor</option>
                    <?php
                        foreach($listIdDoctor as $item){
                            echo "<option value='$item'>$item</option>";
                        }
                    ?>
                </select>
            </div>
            <div class='form-group'>
                <label for='fullName'>Full Name</label>
                <input type='text' class='form-control' name='fullName' id='fullName' value='<?php echo $fullName; ?>'>
            </div>
            <div class='form-group'>
                <label for='phoneNumber'>Phone Number</label>
                <input type='text' class='form-control' name='phoneNumber' id='phoneNumber' value='<?php echo $phoneNumber; ?>'>
            </div>
            <div class='form-group'>
                <label for='workPlace'>Work Place</label>
                <input type='text' class='form-control' name='workPlace' id='workPlace' value='<?php echo $workPlace; ?>'>
            </div>
            <div class='form-group'>
                <label for='specialist'>Specialist</label>
                <input type='text' class='form-control' name='specialist' id='specialist' value='<?php echo $specialist; ?>'>
            </div>
            <div class='form-group'>
                <label for='address'>Address</label>
                <input type='text' class='form-control' name='address' id='address' value='<?php echo $address; ?>'>
            </div>
            <div class='form-group'>
                <label for='keywords'>Keywords</label>
                <input type='text' class='form-control' name='keywords' id='keywords' value='<?php echo $keywords; ?>'>
            </div>
            <div class='form-group'>
                <label for='slug'>Slug</label>
                <input type='text' class='form-control' name='slug' id='slug' value='<?php echo $slug; ?>'>
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

