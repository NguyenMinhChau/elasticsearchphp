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
        <form action='#' method='POST' autocomplete="off">
            <div class="form-group">
                <label for="search">Nội dung tìm kiếm</label>
                <input type="text" class="form-control" id="search" name='search' placeholder="Nhập nội dung tìm kiếm" value="<?=$search?>">
            </div>
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
    </div>
    <h5 class='card-header text-center'>Kết quả tìm kiếm</h5>
    <div class='card-body'>
        <?php if(isset($result)): ?>
            <?php if(isset($result['hits']['hits'])): ?>
                <?php foreach($result['hits']['hits'] as $hit): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?=$hit['_source']['title']?></h5>
                            <p class="card-text"><?=$hit['_source']['content']?></p>
                            <p class="card-text"><small class="text-muted">Keyword: <?=$hit['_source']['keyword']?></small></p>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <div class="alert alert-danger">Không tìm thấy kết quả</div>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>
