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