#Python Example
===============
本Python网页上传工具通过Django框架搭建并使用七牛云存储提供的Python-SDK演示了如何使用Python和Python-SDK开发一个简单的web版文件上传工具样例。
主要内容如下：

安装Django

下载并配置七牛Python-SDK

创建Django项目

#安装Django
下载[Django-1.5.1.tar.gz](www.djangoproject.com/download)到本地 运行以下命令执行安装：

     tar xzvf Django-1.5.1.tar.gz
     cd Django-1.5.1
     sudo python setup.py install
#下载并配置Python-SDK
下载[七牛Python－SDK](https://github.com/qiniu/python-sdk)保存在本地。
进入Python-SDK目录，找到并修改config.py
       
       ACCESS_KEY = 'YOUR_ACCESS_KEY'
       SECRET_KEY = 'YOUR_SECRET_KEY'
       
 
#通过Django创建项目
在下面的操作中将会创建一个名为 djproject的项目：
        
        django-admin.py startproject djproject
该语句将在django-admin.py所在的目录中创建一个名为jdproject的目录，进入该项目，可以看到如下几个文件： 

manage.py: 可以使网站管理员来管理Django项目

setting.py: 此Django项目配置文件

urls.py: 包含URL配置文件，用户访问Django应用的方式

###创建一个上传页面
使用manage.py 创建一个名为uploader的应用:
        
        manage.py startapp uploader

使用startapp命令后，将会在djproject目录下生成一个uploader目录(如果生成的项目没有位于当前目录下，请将此项目置于与manage.py同一目录下)。可一个看到uploader目录下包含：

models.py: 定义数据模型相关信息

tests.py: 该应用的测试文件

views.py: 包含视图相关的信息
这个应用将用于显示上传文件的页面。



###URL设计
在urls.py文件中定义URL。向urls.py中添加如下代码:
            
    urlpatterns = patterns('',

    url(r'^uploader/', 'uploader.views.uploadWithKeyAndCustomField', name='uploadWithKeyAndCustomField'),
    
    
当访问localhost:8000/uploader时，将调用uploader目录下views.py文件中的uploadWithKeyAndCustomField()函数。
###创建视图

配置uploader目录下views.py文件的代码如下：
	
	# Create your views here.
	import sys
	sys.path.append('/Users/yangwang/python-sdk-3.0.0/qbox')
	import uptoken
	                             
	from django.http import HttpResponse
	from django.http import HttpResponseRedirect
	
	def uploadWithKeyAndCustomField(request):
	    tokenObj=uptoken.UploadToken(scope="wyangspace")
	    token=tokenObj.generate_token()
	    htmlStr='''<html>
	 <body>
	  <form method="post" action="http://up.qiniu.com/" enctype="multipart/form-data">
	   <input name="token" type="hidden" value="%s">
	   <input name="x:custom_field_name" value="x:me">
	   Image key in qiniu cloud storage: <input name="key" value="foo bar.jpg"><br>
	   Image to upload: <input name="file" type="file"/>
	   <input type="submit" value="Upload">
	  </form>
	 </body>
	</html>'''
	    
	    return HttpResponse(htmlStr %(token))


    
    

这段代码样例，可以指定文件存储到space的文件名，以及用户自定义的custom_field_name. 

七牛API中关于custom_field_name的说明如下：
自定义变量，必须以 x: 开头命名，不限个数。可以在 uploadToken 的 callbackBody 选项中使用 $(x:custom_field_name) 求值。

views.py中的uploadWithKeyAndCustomField()函数，将会生成uploadtoken。文件上传成功后显示up.qiniu.com返回的信息。

    
这样上传文件页面都已经配置完毕。
###启动服务器
   在命令行中，切换到djproject目录，输入如下命令：
         
      manage.py runserver
      
然后访问 http://localhost:8000/uploader/ 

###创建一个下载页面

刚才我们完成了一个上传文件的页面。接下来我们创建一个下载图片文件的页面。其中即将用于视图HTML代码如下：

	   <html>
	 <body>
	 <form action="/download/" method="get">
	      Bucket name: <input type="text" name="bucketname" value=""><br> 
	      Filekey download from cloud storage: <input type="text" name="fileKey" value=""><br>
	      Filename saving as: <input type="text" name="fileName" value=""><br>
	      <input type="submit" value="Download">
	  <p>ImageDownloadUrl: %s
	  <p><a href="/Users">Back to uploadWithKey</a>
	  <p><img src="%s">
	 </body>
	</html>
	
	
这个页面可以让用户指定所要获取文件的空间名(bucketname),所要获取的文件名(fileKey)，以及所要另存为的文件名(fileName)。除了fileName外，其他不可为空。

同样的使用命令行创建一个名为"download"的项目：
     
     manage.py startapp download
     
     
 同样的向刚才的urls.py文件中再添加以下内容：
 
    url(r'^download/', 'download.views.download', name='download')
    
 这样当访问localhost:8000/download 时将会调用位于名为"download"文件夹下的views.py文件中的download()函数。
 
 接下来配置download目录下的view.py文件内容如下：
 
 
	import sys
	sys.path.append('/Users/yangwang/python-sdk-3.0.0/qbox')
	import digestoauth
	import rs
	
	                             
	                             
	from django.http import HttpResponse
	from django.http import HttpResponseRedirect
	#用于让用户提交需要下载文件的bucket，文件key，以及希望保存成的文件名。如果时图片的话将会显示图片。
	htmlStr='''<html>
	 <body>
	 <form action="/download/" method="get">
	      Bucket name: <input type="text" name="bucketname" value=""><br> 
	      Filekey download from cloud storage: <input type="text" name="fileKey" value=""><br>
	      Filename saving as: <input type="text" name="fileName" value=""><br>
	      <input type="submit" value="Download">
	  <p>ImageDownloadUrl: %s
	  <p><a href="/Users">Back to uploadWithKey</a>
	  <p><img src="%s">
	 </body>
	</html>'''
	
	
	def download(request):
	      bucket='' #bucket既空间名
	      key=''    #文件的key
	      saveAs='' #存为的文件名（可以为空）
	      src=''    #生成的下载URL
	
	      if 'bucketname' in request.GET and request.GET['bucketname']:
	            bucket=request.GET['bucketname']
	
	      if 'fileKey' in request.GET and request.GET['fileKey']:
	            key=request.GET['fileKey']
	
	      if 'fileName' in request.GET and request.GET['fileName']:
	            saveAs=request.GET['fileName']
	      #调用Python-SDK获取下载URL
	      try:
	            
	            client=digestoauth.Client()
	            resp=rs.Service(client,bucket)
	            dwnfile=resp.Get(key,saveAs)
	            src=dwnfile['url']
	      except:
	            pass
	            src='unkown'
	    
	      
	    
	      return HttpResponse(htmlStr % (src,src))
	 
    
     
     
 然后启动服务器，访问localhost:8000/download 即可使用此web下载工具。

   