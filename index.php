<?php
    // require_once './index.php';
    $page = $_GET['page'] ?? '';
    $menuitems = [
        'managerindex' => '<i class="bx bx-list-check"></i> Quản lý chỉ mục elasticsearch',
        'document' => '<i class="bx bx-file"></i> Quản lý tài liệu healthcare_new',
        'search' => '<i class="bx bx-file-find"></i> Tìm kiếm tài liệu healthcare_new',
        'delete' => '<i class="bx bx-folder-minus"></i> Xóa bác sĩ theo ID tài liệu healthcare_new'
    ];
    use Elasticsearch\ClientBuilder; 
    require "vendor/autoload.php";
    //kiểm tra trạng thái URL bằng curl https
    $url9299 = 'http://localhost:9299';
    $url9200 = 'http://localhost:9200';
    $curl9299 = curl_init($url9299);
    $curl9200 = curl_init($url9200);
    curl_setopt($curl9299, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl9299, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl9200, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl9200, CURLOPT_SSL_VERIFYPEER, false);
    $response9299 = curl_exec($curl9299);
    $response9200 = curl_exec($curl9200);
    $status9299 = curl_getinfo($curl9299, CURLINFO_HTTP_CODE);
    $status9200 = curl_getinfo($curl9200, CURLINFO_HTTP_CODE);
    curl_close($curl9299);
    curl_close($curl9200);
    if($status9299 != 200){
        $hosts = [
            [
                'host' => 'localhost',
                'port' => 9200,
                'scheme' => 'http',
            ]
        ];
    }
    if($status9200 != 200){
        $hosts = [
            [
                'host' => 'localhost',
                'port' => 9299,
                'scheme' => 'http',
            ]
        ];
    }
    if($status9299 == 500 && $status9200 == 500){
        $hosts = [
            [
                'host' => 'localhost',
                'port' => 9200,
                'scheme' => 'http',
            ]
        ];
    }
    if($status9299 != 200 && $status9200 != 200){
        echo '<h3>Error: Server chưa được kích hoạt. Không thể kết nối đến Elasticsearch</h3>';
        exit();
    }
    $client = ClientBuilder::create()->setHosts($hosts)->build();
    $result = $client->cat()->indices();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElasticSearch</title>
    <link rel='icon' href='https://huongdanjava.com/wp-content/uploads/2020/04/elasticsearch.png'/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <div id='app'>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="./"><i class='bx bx-bolt-circle'></i> ElasticSearch</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <?php foreach ($menuitems as $key => $label): ?>
                        <?php
                            $class= '';
                            if($page === $key) {
                                $class = 'active';
                            }
                        ?>
                        <li class="nav-item <?= $class ?>">
                            <a class="nav-link" href="./?page=<?= $key ?>"><?= $label ?></a>
                        </li>
                    <?php endforeach ?>
                    <li class="nav-item d-flex-nav">
                        <span class='datetime'></span>
                        <span class='timer'></span>
                    </li>
                </ul>
            </div>
        </nav>
        <?php if($page !== ''):?>
                <?php
                    include $page . '.php';
                ?>
            <?php else: ?>
                <h3 class="mt-2 text-center">ElasticSearch dùng để truy vấn danh bạ y tế 2022 : Port <?=$hosts[0]['port']?></h3>
                <?php
                    if($result){
                        echo '<div class="container-fluid">';
                        echo '<table class="table table-responsive table-bordered table-hover">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th scope="col">#</th>';
                        echo '<th scope="col">Health</th>';
                        echo '<th scope="col">Status</th>';
                        echo '<th scope="col">Index</th>';
                        echo '<th scope="col">Docs.Count</th>';
                        echo '<th scope="col">Docs.Deleted</th>';
                        echo '<th scope="col">Store.Size</th>';
                        echo '<th scope="col">Primary.Size</th>';
                        echo '<th scope="col">Primary.Shards</th>';
                        echo '<th scope="col">Primary.Replicas</th>';
                        echo '<th scope="col">UUID</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach($result as $key => $value){
                            echo '<tr>';
                            echo '<th scope="row">'.($key+1).'</th>';
                            echo '<td class="status" style=--color:'.$value['health'].'>'.$value['health'].'</td>';
                            echo '<td>'.$value['status'].'</td>';
                            echo '<td>'.$value['index'].'</td>';
                            echo '<td>'.$value['docs.count'].'</td>';
                            echo '<td>'.$value['docs.deleted'].'</td>';
                            echo '<td>'.$value['store.size'].'</td>';
                            echo '<td>'.$value['store.size'].'</td>';
                            echo '<td>'.$value['pri'].'</td>';
                            echo '<td>'.$value['rep'].'</td>';
                            echo '<td>'.$value['uuid'].'</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                    }
                ?>
        <?php endif ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src='./assets/js/index.js'></script>
</body>
</html>