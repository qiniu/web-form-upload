Ruby-example
============
本例演示一个网页上传以及下载的样例
#搭建Ruby On Rails开发环境
从此处下载 http://rubygems.org/pages/download RubyGems，然后解压并设置路径到下载好的文件，运行下面命令：

    ruby setup.rb
    
当RubyGems安装完后，使用命令：

    gem install rails
    
安装Rails。


#通过Rails创建应用程序
在Terminal运行下面命令：

    rails new uolpad
    
这将在当前目录创建一个名为WebService工程

    $ cd WebService
    
我们转到WebService目录内
接下来我们安装需要的gems

    bundle install
    
#下载并配置Ruby-SDK
[在此处下载七牛Ruby-SDK](https://github.com/qiniu/ruby-sdk)置于WebService目录内。

在WebService文件目录下找到Gemfile文件，添加如下代码：

    gem 'qiniu-rs'
    
然后在程序所在的目录下，运行bundle安装依赖包：

    $ bundle
或者可以使用gem进行安装：

    $ gem install qiniu-rs
    
然后在在此应用程序目录中新建一个文件：.../WebService/config/initializers/qiniu-rs.rb 然后添加如下代码：
    
    Qiniu::RS.establish_connection! :access_key => YOUR_APP_ACCESS_KEY,
                                    :secret_key => YOUR_APP_SECRET_KEY
                                    
###创建上传页面
在Terminal中输入如下命令创建一个视图：

    $ rails g controller home index
    $ rm public/index.html
    
 
    
rails将为你创建多个文件，其中包括 app/views/home/index.html.erb, 这是一个用于显示视图的模版，打开该文件，写入以下代码：

     <html>
      <body>
       <form method="post" action="http://up.qiniu.com/" enctype="multipart/form-data">
        <% value=@token %>
        <input name="token" type="hidden" value=<%="#{value}"%>>
        <input name="x:custom_field_name" value="x:me">
        Image key in qiniu cloud storage: <input name="key" value="foo bar.jpg"><br>
        Image to upload: <input name="file" type="file"/>
        <input type="submit" value="Upload">
       </form>
      </body>
     </html>
     <p>Find me in app/views/home/index.html.erb </p>
     
这是一个嵌入ruby代码的html，token值将在home_controller.rb中生成并传送到html页面中，下面会详细叙述。然后你需要告诉rails你的实际首页在什么位置，打开并修改 config/router.rb 如下：

     WebService::Application.routes.draw do
         match 'upload' => 'home#index'
     end 
     
当访问 localhost:3000/upload 时将会显示 app/views/home/index.html.erb 的内容。
####生成uploadtoken
  在controller文件夹中打开并配置 home_contrlloer.rb 代码如下：
  
    class HomeController < ApplicationController
      def index
        scope="YOUR_SPACE_NAME"
        @token=Qiniu::RS.generate_upload_token :scope => "YOUR_SPACE_NAME",
                                          
      end
     end

这将生成上传授权凭证(uploadToken),调用SDK提供的 Qiniu::RS.generate_upload_token函数来获取一个用于上传的 upload_token

####运行服务器

 先转到在应用程序目录下($ cd upload)，运行以下命令:
   
    rails s
    
 然后访问 localhost:3000/upload 即可使用我们创建好的web上传工具了。
 
###创建下载应用

 接下来，继续创建一个下载文件的项目：先进入我们的WebService文件目录：
 
    $ cd WebService
 
 创建一个名为“download”的项目：
 
    $ rails g controller download index
 
 并向config/router.rb再添加一行代码，使其内容如下：
 
	WebService::Application.routes.draw do
	  
	
	  get "download/index"
	
	  match 'upload' => 'home#index'
	  match 'download' => 'download#index'
	end
	
	
 这样当访问 loclhost:3000/download时，将会显示位于/views/download目录下的index.html.erb的内容。
 
 接下来修改/views/download/index.html.erb 内容如下：
 
	   <h1>Download#index</h1>
		<html>
		 <body>
		 <form action="/download/" method="get">
		 	<% src=@imageURL %>
		      Bucket name: <input type="text" name="bucketname" value=""><br> 
		      Filekey download from cloud storage: <input type="text" name="fileKey" value=""><br>
		      <input type="submit" value="Download">
		  <p>ImageDownloadUrl: <%="#{src}"%>
		  <p><a href="/Users">Back to uploadWithKey</a>
		  <p><img src=<%="#{src}"%>>
		 </body>
		</html>
		<p>Find me in app/views/download/index.html.erb</p>
		
		
 以上用HTML表单，将输入的bucket和需要下载文件的key的内容传递给rails的controller.接下来就需要修改controller.
 
####生成Download Token
 
 找到位于controllers目录下名为 download_controller.rb 添加内容如下：
 
 
   
	   class DownloadController < ApplicationController
		  def index
		  	bucket=params[:bucketname] # bucket name i.e. 空间名
		  	key=params[:fileKey] # 存储于空间上文件的key
		  	downloadToken=Qiniu::RS.generate_download_token #使用七牛Ruby-SDK生成download token
		  	@imageURL= "http://#{bucket}.qiniudn.com/#{key}?token=#{downloadToken}" #组织下载连接格式：http://<bucket>.qiniudn.com/<key>?token=<downloadToken>
		  end
		end
	
 这样当获得从表单提交的bucket名以及key后，就会生成一个用于下载文件的download token，用这个download token再按照七牛Ruby-SDK所述的下载连接格式组成download URL即可。
 
 然后访问localhost:3000/download即可


     
 
