Ruby-example
============
本例演示一个网页上传的样例
#搭建Ruby On Rails开发环境
从此处下载 http://rubygems.org/pages/download RubyGems，然后解压并设置路径到下载好的文件，运行下面命令：

    ruby setup.rb
    
当RubyGems安装完后，使用命令：

    gem install rails
    
安装Rails。


#通过Rails创建应用程序
在Terminal运行下面命令：

    rails new uolpad
    
这将在当前目录创建一个upload工程

    $ cd upload
    
我们转到upload目录内
接下来我们安装需要的gems

    bundle install
    
#下载并配置Ruby-SDK
[在此处下载七牛Ruby-SDK](https://github.com/qiniu/ruby-sdk)置于upload目录内。

在upload文件目录下找到Gemfile文件，添加如下代码：

    gem 'qiniu-rs'
    
然后在程序所在的目录下，运行bundle安装依赖包：

    $ bundle
或者可以使用gem进行安装：

    $ gem install qiniu-rs
    
然后在在此应用程序目录中新建一个文件：“YOUR_RAILS_APP”/config/initializers/qiniu-rs.rb 然后添加如下代码：
    
    Qiniu::RS.establish_connection! :access_key => YOUR_APP_ACCESS_KEY,
                                    :secret_key => YOUR_APP_SECRET_KEY
                                    
#创建上传页面
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

     Blog::Application.routes.draw do
         match 'upload' => 'home#index'
     end 
     
当访问 localhost:3000/upload 时将会显示 app/views/home/index.html.erb 的内容。
#生成uploadtoken
  在controller文件夹中打开并配置 home_contrlloer.rb 代码如下：
  
    class HomeController < ApplicationController
      def index
        scope="YOUR_SPACE_NAME"
        @token=Qiniu::RS.generate_upload_token :scope => "YOUR_SPACE_NAME",
                                          
      end
     end

这将生成上传授权凭证(uploadToken),调用SDK提供的 Qiniu::RS.generate_upload_token函数来获取一个用于上传的 upload_token

#运行服务器

 先转到在应用程序目录下($ cd upload)，运行以下命令:
   
    rails s
    
 然后访问 localhost:3000/upload
