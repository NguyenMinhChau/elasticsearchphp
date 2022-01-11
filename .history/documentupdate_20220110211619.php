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
    $id = $_GET['id'] ?? null;
    $idDoctor = $_GET['idDoctor'] ?? null;
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
    //lấy document theo idDoctor
    $paramsDC = [
        'index' => 'healthcare_new',
        'type' => '_doc',
        'id' => $idDoctor,
    ];
    $document = $client->get($paramsDC);
    var_dump($document);
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Cập nhật Document trong Elasticsearch</h5>
    <div class='card-body'>
        <div class='form-group'>
            <label for="idDoctor">ID</label>
            <input type="text" class="form-control" id="id" name='id' placeholder="Nhập ID Doctor" value="<?=$id?>">
        </div>
        <div class='form-group'>
            <label for="idDoctor">ID Doctor</label>
            <input type="text" class="form-control" id="idDoctor" name='idDoctor' placeholder="Nhập ID Doctor" value="<?=$idDoctor?>">
        </div>
        <form action='#' method='POST' autocomplete="off">v>
            
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

