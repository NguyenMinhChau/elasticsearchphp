<?php
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
    if($status9299 == 200 && $status9200 == 200){
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
    // kiểm tra index có tồn tại hay không
    $exists = $client->indices()->exists(['index' => 'healthcare_new']);

    if(!$exists){
        echo '<h5>Error: Chỉ mục healthcare_new không tồn tại</h3>';
        exit();
    }
    $search = $_POST['search'] ?? null;
    if($search != ''){
        $params = [
            'index' => 'healthcare_new',
            'type' => '_doc',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            ['match' => ['keyWords' => $search]]
                        ]
                    ]
                        ],
                    'highlight' => [
                        'pre_tags' => ['<strong class="text-danger">'],
                        'post_tags' => ['</strong>'],
                        'fields' => [
                            'keyWords' => new stdClass()
                        ]
                    ]
            ]
        ];
        $result = $client->search($params);
    }
    /** */
?>
<div class='card m-4'>
    <h5 class='card-header text-center'>Tìm kiếm Document trong Elasticsearch</h5>
    <div class='card-body'>
        <form method='POST' autocomplete="off">
            <div class="form-group">
                <label for="search">Nội dung tìm kiếm</label>
                <input type="text" class="form-control" id="search" name='search' placeholder="Nhập nội dung tìm kiếm" value="<?=$search?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class='bx bx-file-find'></i> Tìm kiếm</button>
        </form>
    </div>
    <h5 class='card-header text-center'>Kết quả tìm kiếm</h5>
    <div class='card-body'>
        <?php
            if($search != '' && count($result['hits']['hits']) > 0){
                echo 'Có '.$result['hits']['total']['value'].' kết quả được tìm thấy | ';
                echo 'Thời gian tìm kiếm: '.$result['took'].'ms | ';
                echo 'Top '.count($result['hits']['hits']) . ' tài liệu có độ tin cậy cao nhất';
                echo '<table class="table table-responsive table-bordered table-hover">';
                echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">Độ tin cậy</th>';
                echo '<th scope="col">ID Tài liệu</th>';
                echo '<th scope="col">Mã bác sĩ</th>';
                echo '<th scope="col">Họ tên</th>';
                echo '<th scope="col">Từ khóa</th>';
                echo '<th scope="col">Chuyên khoa</th>';
                echo '<th scope="col">Slug</th>';
                echo '<th scope="col">Địa chỉ làm việc</th>';
                echo '<th scope="col">Địa chỉ nhà</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach($result['hits']['hits'] as $item){
                    echo '<tr>';
                    echo '<td>'.number_format($item['_score'],3,'.','').'</td>';
                    echo '<td>'.$item['_id'].'</td>';
                    echo '<td>'.$item['_source']['id'].'</td>';
                    echo '<td>'.$item['_source']['name'].'</td>';
                    echo '<td>'.$item['highlight']['keyWords'][0] ?? $item['_source']['keyWords'].'</td>';
                    echo '<td>'.$item['_source']['specialist'].'</td>';
                    echo '<td>'.$item['_source']['slug'].'</td>';
                    echo '<td>'.$item['_source']['workPlace'].'</td>';
                    echo '<td>'.$item['_source']['address'].'</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            }
            else{
                echo '<div class="alert alert-danger text-center">Không tìm thấy kết quả</div>';
            }
        ?>
    </div>
</div>
