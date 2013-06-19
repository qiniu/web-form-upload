<html>
 <body>
 <!-- PHP code to generate the download token-->
 <!--**********************************************************************-->
<?php
require_once('qbox/authtoken.php');
require_once('qbox/client/rs.php');
//Generate the download token. 
$upToken = QBox\MakeAuthToken(array('scope'=>'APPLY YOUR BUCKET NAME HERE','expiresIn' => 3600)); 
$key='my_test.jpg' // set a default key for the uploading picture
?>

 <!--**********************************************************************-->

   <form method="post" action="http://up.qiniu.com/" enctype="multipart/form-data">
   <input name="token" type="hidden" value="<?php echo $upToken?>">
   <input name="x:custom_field_name" value="x:mypic">
   Image key in qiniu cloud storage: <input name="key" value="<?php echo $key?>"><br>
   Image to upload: <input name="file" type="file"/>
   <input type="submit" value="Upload">
  </form>

 </body>
</html>