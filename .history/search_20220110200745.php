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
    $search = $_POST['search'] ?? null;
    if($search != ''){
        $params = [
            'index' => 'healthcare_new',
            'type' => '_doc',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            ['match' => ['name' => $search]],
                            ['match' => ['keyWords' => $search]],
                            ['match' => ['specialist' => $search]]
                        ]
                    ]
                        ],
                    'highlight' => [
                        'pre_tags' => ['<strong class="text-danger">'],
                        'post_tags' => ['</strong>'],
                        'fields' => [
                            'name' => new stdClass(),
                            'keyWords' => new stdClass(),
                            'specialist' => new stdClass()
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
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
    </div>
    <h5 class='card-header text-center'>Kết quả tìm kiếm</h5>
    <div class='card-body'>
        <?php
            if($search != '' && count($result['hits']['hits']) > 0){
                echo 'Có '.$result['hits']['total']['value'].' kết quả được tìm thấy | ';
                echo 'Thời gian tìm kiếm: '.$result['took'].'ms | ';
                echo 'Top '.count($result['hits']['hits']) . ' tài liệu có độ tin cậy cao nhất';
                echo '<table class="table table-bordered">';
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
                    echo '<td>'.$item['highlight']['name'][0] ?? $item['_source']['name'].'</td>';
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
