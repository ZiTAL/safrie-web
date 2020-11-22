<?php
function getUrl($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

$q = $_GET['q'];

$json_01 = getUrl("http://zital-pi.no-ip.org:3001/blocks");
$json_02 = getUrl("http://zital-pi.no-ip.org:3002/blocks");

$array_01 = json_decode($json_01, true);
$array_02 = json_decode($json_02, true);

$result_01 = array();
foreach($array_01 as $a)
{
    $tmp = json_encode($a['data']);
    $r = preg_quote($q, '/');
    if(preg_match("/".$q."/", $tmp, $m))
        $result_01[] = $a;
}

$result_02 = array();
foreach($array_02 as $a)
{
    $tmp = json_encode($a['data']);
    $r = preg_quote($q, '/');
    if(preg_match("/".$q."/", $tmp, $m))
        $result_02[] = $a;
}

$result = array
(
    'blockchain-01' => $result_01,
    'blockchain-02' => $result_02
);

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);