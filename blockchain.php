<?php
function getUrl($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function save2blockchain($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array
    (
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close ($ch);    
}

$json = file_get_contents("php://input");
if($json!=='')
{
    // curl -H "Content-type:application/json" --data '{"data" : "froga-06"}' http://zital-pi.no-ip.org:3002/mineBlock"
    $url = $_GET['url'];
    save2blockchain($url, $json);
}
elseif(isset($_GET['url']))
{
    $url = $_GET['url'];
    $json = getUrl($url);
}

if(isset($json))
{
    header('Content-Type: application/json');
    $array = json_decode($json, true);
    echo json_encode($array, JSON_PRETTY_PRINT);
}