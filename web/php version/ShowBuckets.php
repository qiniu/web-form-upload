<?php 

require('qbox/rs.php');
require('qbox/client/rs.php');

$client = QBox\OAuth2\NewClient();
$rs = QBox\RS\NewService($client, $bucket);

list($result, $code, $error) = $rs->Buckets();
echo (date("D F d Y",$t)) . " ===> Bucukets result:";
if ($code == 200) {
    var_dump($result);
} else {
    $msg = QBox\ErrorMessage($code, $error);
    echo "Buckets failed: $code - $msg<br/>";  
}

?>