<?php
	require "../bootstrap.php";
	$token = "";
?>
<html>
 <body>
  <form method="post" action="http://up.qiniu.com/" enctype="multipart/form-data">
   <input name="token" type="hidden" value="<?php echo $token ?>">
   Album belonged to: <input name="x:album" value="albumId"><br>
   Image key in qiniu cloud storage: <input name="key" value="foo bar.jpg"><br>
   Image to upload: <input name="file" type="file"/><br>
   <input type="submit" value="Upload">
  </form>
 </body>
</html>

