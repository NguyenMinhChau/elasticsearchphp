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
                            'title' => new stdClass(),
                            'content' => new stdClass(),
                            'keyword' => new stdClass()
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
                echo 'Thời gian tìm kiếm: '.$result['took'].'ms | ';
                echo 'Số lượng: '.count($result['hits']['hits']) . ' tài liệu ';
                echo '<table class="table table-bordered">';
                echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">ID</th>';
                echo '<th scope="col">Tiêu đề</th>';
                echo '<th scope="col">Nội dung</th>';
                echo '<th scope="col">Từ khóa</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach($result['hits']['hits'] as $item){
                    echo '<tr>';
                    echo '<td>'.$item['_id'].'</td>';
                    echo '<td>'.$item['_source']['title'].'</td>';
                    echo '<td>'.$item['_source']['content'].'</td>';
                    echo '<td>'.implode(', ', $item['_source']['keyword']).'</td>';
                    echo '</tr>';
                    if(isset($item['highlight']['title'])){
                        echo '<tr>';
                        echo '<td colspan="4">';
                        echo '<div class="alert alert-warning">';
                        echo '<h5>Tiêu đề</h5>';
                        echo '<p>'.implode('<br>', $item['highlight']['title']).'</p>';
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    if(isset($item['highlight']['content'])){
                        echo '<tr>';
                        echo '<td colspan="4">';
                        echo '<div class="alert alert-warning">';
                        echo '<h5>Nội dung</h5>';
                        echo '<p>'.implode('<br>', $item['highlight']['content']).'</p>';
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    if(isset($item['highlight']['keyword'])){
                        echo '<tr>';
                        echo '<td colspan="4">';
                        echo '<div class="alert alert-warning">';
                        echo '<h5>Từ khóa</h5>';
                        echo '<p>'.implode('<br>', $item['highlight']['keyword']).'</p>';
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
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
