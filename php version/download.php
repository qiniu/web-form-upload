<html>
<body>
  <form action="#">
  	  Bucket name: <input type="text" name="bucket" value=""><br> 
      Filekey download from cloud storage: <input type="text" name="fileKey" value=""><br>
      Filename saving as: <input type="text" name="fileName" value=""><br>
      <input type="submit" value="Download">
      <p><a href="/upload">Back to uploadWithkeyAndCustomField</a><br>
<!--The embeded php code to get the input from html form and generate the download url -->
<!-- ************************************************************************************-->  
<?php 
//import Qiniu PHP-SDK
require_once('qbox/rs.php');
require_once('qbox/client/rs.php');
//getting all the parameters needed to generate the download url
$client = QBox\OAuth2\NewClient();
$bucket =$_GET['bucket'];
$rs = QBox\RS\NewService($client, $bucket);
$fileKey = $_GET['fileKey'];
$saveAsFriendlyName =$_GET['fileName'];
//generating the download url
list($result, $code, $error) = $rs->Get($fileKey, $saveAsFriendlyName);
echo "===> Get $key result:\n";
if ($code == 200) {
    var_dump($result);
} else {
    $msg = QBox\ErrorMessage($code, $error);
    die("Get failed: $code - $msg\n");
}
?>
<!-- ************************************************************************************-->   
    <p><img src="<?php echo $result["url"]; ?>" />
 </body>
</html>



