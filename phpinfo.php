<?php phpinfo();
//$logFile = fopen('file1.log', 'w');
//fwrite($logFile, 'Can root:www have permission to write file???');
//fclose($logFile);
$payload = file_get_contents('php://input');

try{
    $payloadObj = json_decode($payload, true);
}catch(\Exception $e){
    echo "Not payload from Github\n";
    echo $payload;
    die;
}

$repositoryName = $payloadObj['repository']['name'];

if($repositoryName != "laravel-smu-confession"){
    echo "Not handle\n";
    echo "repositoryName: {$repositoryName}";
    die;
}

echo shell_exec("whoami");
echo shell_exec("git pull origin master");
