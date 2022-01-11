<?php
    use Elasticsearch\ClientBuilder;
    require "vendor/autoload.php";
    $hosts = [
        [
            'host' => 'localhost',
            'port' => 9200,
            'scheme' => 'http',
        ]
    ];
    $client = ClientBuilder::create()->setHosts($hosts)->build();
    // kiểm tra index có tồn tại hay không
    $exists = $client->indices()->exists(['index' => 'article']);

    if(!$exists){
        throw new Exception('Chỉ mục bài viết không tồn tại');
    }
    $search = $_POST['search'] ?? null;
    if($search != ''){
        $params = [
            'index' => 'article',
            'type' => 'article_type',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            ['match' => ['title' => $search]],
                            ['match' => ['content' => $search]],
                            ['match' => ['keyword' => $search]]
                        ]
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
        <form action='#' method='POST' autocomplete="off">
            <div class="form-group">
                <label for="search">Nội dung tìm kiếm</label>
                <input type="text" class="form-control" id="search" name='search' placeholder="Nhập nội dung tìm kiếm">
            </div>
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
    </div>
    <h5 class='card-header text-center'>Kết quả tìm kiếm</h5>
    <div class='card-body'>
        <?php
            if($search != '' && $result['hits']['total'] > 0){
                echo 'Thời gian tìm kiếm: '.$result['took'].'ms';
                echo 'Số lượng documented: '.count($result['hits']['hits']);
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
                }
                echo '</tbody>';
                echo '</table>';
            }
        ?>
</div>
