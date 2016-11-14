<?php
require_once 'vendor/autoload.php';
if(!session_id()){
    session_start();
}

//$_SERVER["PHP_SELF"];


/**
 * Form as common
 */
$postForm = file_get_contents(__DIR__ . '/post-form.html');
echo $postForm;

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fb = new Facebook\Facebook([
        'app_id' => '1282309335173913',
        'app_secret' => 'd27cdfa5fcb1c92a079552d878bc3dae',
        'default_graph_version' => 'v2.8'
    ]);
    $accessToken =        'EAASOQOhqSxkBAIh26JIYRZCXLdAbzYNJkZCFeMK00ZCF8NtUUxiZCLhUlKXGt2QfwVgtjIg1WsqZBPB6DaU8f1iQNyaNEowVOk9Rigiq0FYPfessrtncQJCJZCWi9Da6kZBdsS2gvxfF6RlkPK5V2ZCCIeQ6178hJNayQ68bcNCPRwZDZD';
    $fb->setDefaultAccessToken($accessToken);

    try{
        $message = $_POST['message'];
        if(empty($message)){
            echo "Please submit message to post";
            exit;
        }
        $uploadFileTmp = $_FILES['fileUpload'];

        // var_dump($uploadFileTmp); die;

        $data = [
            'message' => $message
        ];

        $postUrl = '/1582722098684919/feed';

        /**
         * Case post with image
         * Save to photo
         */
        if(!empty($uploadFileTmp['tmp_name'])){
            echo "build data source";
            $data['source'] = $fb->fileToUpload($uploadFileTmp['tmp_name']);
            echo "fb fileToUpload work!";
            $postUrl = '/me/photos';
        }

        $response = $fb->post($postUrl, $data);
        var_dump($response);
        echo "fb post work!";

        $graphNode = $response->getGraphNode();
        echo "<hr>";
        echo "New post with id: {$graphNode->getField('id')}";

        echo "<hr>";
        $response = $fb->get('/1582722098684919/feed');
        $graphEdge = $response->getGraphEdge();
        $items = $graphEdge->all();

        echo "<h1>Review post</h1>";
        foreach($items as $graphNode){
            if(!empty($graphNode->getField('message'))){
                echo "<li>{$graphNode->getField('message')}</li>";
            }
        }
        echo "<li>...</li>";
        exit;
    }catch(\Exception $e){
        var_dump($e);
        echo $e->getMessage();
        exit;
    }
}

