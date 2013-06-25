<html>
<body>
  <form action="#">
  	  Type in your new bucket name: <input type="text" name="bucket" value="">
      <input type="submit" value="Make New Bucket"><br>

      =====================================================================================<br>

<!--The embeded php code will generate a bucket(i.e. space) in Qiniu Cloud Storage -->
<!-- ************************************************************************************-->  
<?php 

require('qbox/rs.php');
require('qbox/client/rs.php');
//首先初始化一个OAuth Client对象
$client = QBox\OAuth2\NewClient();
//然后实例化一个 QBox\RS\NewService() 对象
$bucket =$_GET['bucket'];
$rs = QBox\RS\NewService($client, $bucket);
//建立Bucket(i.e Space)
list($code, $error) = $rs->Mkbucket($bucket); 
$t=time();
echo (date("D F d Y",$t)) . " ===> Mkbucket result:";
if ($code == 200) {
    echo "Mkbucket Success!<br/>";
} else {
    $msg = QBox\ErrorMessage($code, $error);
    echo "Buckets failed: $code - $msg<br/>";  
} 

?>
<!-- ************************************************************************************-->   

 </body>
</html>
