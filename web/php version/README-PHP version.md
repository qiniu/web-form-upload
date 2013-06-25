PHP-example
===========

a small instance using PHP-SDK
This instance shows how to build a small web-upload.
本文使用Mac OS X内置的Apache和PHP。主要内容包括：

启动Apache

[使用七牛提供的PHP-SDK](https://github.com/qiniu/php5.3-sdk) 创建上传以及下载页面

访问网页

##启动Apache
如果之前已经启动并配置过Apache和PHP可以跳过本段。

打开 Terminal

输入以下下命令：再输入帐号密码
  
     sudo apachectl start
     
运行：可以看到版本号：

     sudo apachectl -v
     
在浏览器中输入"http://localhost",可以看到显示一个内容为 "It works"的页面。 它位于"/Library/WebServer/Documents"下

在Terminal中运行:

     sudo vi/etc/apache2/httpd.conf
     
找到"#LoadModule php5_module libexec/apache2/libphp5.so"，把前面的＃号去掉，保存（命令行输入:w）并退出vi(命令行输入:q)

运行：重启 Apache，这样PHP就可以用了。

      sudo apachectl restart
      
##使用七牛PHP-SDK

下载[七牛PHP-SDK](https://github.com/qiniu/php5.3-sdk)
将其中的qbox文件夹置于与upload.php同一层目录

找到并修改SDK目录中名为config.php的文件，修改如下两行代码：
      
    const ACCESS_KEY = '<Apply your Access Key>';
    const SECRET_KEY = '<Apply your Secret Key>';
    
[到开发者平台查看Access Key & Secrect Key](https://dev.qiniutek.com/account/keys)
    
#####注意：以下所有样例均默认已经下载并修改好SDK

###新建空间(i.e bucket)-样例

本例展示如何新建一个空间(既bucket)。


本例中的项目位于Apache的默认路径："/Library/WebServer/Documents"下，当然也可以修改Apache的默认路径。

让用户输入希望新建的空间(bucket/space)名，即可创建一个新的空间并返回成功或失败信息。


创建名为 "makebucket.php"的文件,内容如下：
	
  
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
	
	
###显示所有空间名

本例展示如何获取已经建立好的所有空间的名字。

新建一个php文件，“ShowBuckets.php”,内容如下：

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
	
然后在同一目录下新建一个HTML文件名为"buckets.html"：写入以下内容：

    <html>
     <body>
       <form action="ShowBuckets.php", method="post">
       <input type="submit" value="Show Buckets"><br>

     </body>
    </html>
    
启动Apache后，访问localhost/buckets 然后点击按钮"Show Buckets"获得所有已有的空间的名字
	

###上传文件－样例
在此目录下创建一个新文件：upload.php


然后修改upload.php文件内容如下：

	<html>
	 <body>
	 <!-- PHP code to generate the download token-->
	 <!--**********************************************************************-->
	<?php
	require_once('qbox/authtoken.php');
	require_once('qbox/client/rs.php');
	
	$upToken = QBox\MakeAuthToken(array('scope'=>'wyangspace','expiresIn' => 3600)); //Generate the download token
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
这样开启Apache后，访问 http://localhost/upload 便可上传文件了。

同样的可以新建一个用于download图片的页面。需要制定文件所在的bucket,文件名以及希望保存成的文件名。
###下载文件－样例
新建名为download.php的文件内容如下：


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
	
	
#访问网页
现在我们可以访问 http://localhost/upload 或 http://localhost/download 来访问我们刚才写好的上传图片的网页。请确保Apache已经开启以及PHP可以使用。