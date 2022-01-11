<?php
    $page = $_GET['page'] ?? '';
    $menuitems = [
        'managerindex' => 'Quản lý chỉ mục',
        'document' => 'Quản lý tài liệu',
        'search' => 'Tìm kiếm tài liệu',
        'delete' => 'Xóa bác sĩ theo ID tài liệu'
    ];
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
    // kiểm tra tất cả index có tồn tại hay không
    $exists = $client->indices()->exists(['index' => 'healthcare_new']);
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
    <script src="https://kit.fontawesome.com/a7b0f58175.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <div id='app'>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">ElasticSearch</a>
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
                            <a class="nav-link" href="/?page=<?= $key ?>"><?= $label ?></a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </nav>
        <?php if($page !== ''):?>
                <?php
                    include $page . '.php';
                ?>
            <?php else: ?>
                <h3 class="mt-2 text-center">ElasticSearch dùng để truy vấn danh bạ y tế 2022</h3>
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