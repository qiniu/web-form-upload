class DownloadController < ApplicationController
  def index
  	bucket=params[:bucketname] # bucket name i.e. 空间名
  	key=params[:fileKey] # 存储于空间上文件的key
  	downloadToken=Qiniu::RS.generate_download_token #使用七牛Ruby-SDK生成download token
  	@imageURL= "http://#{bucket}.qiniudn.com/#{key}?token=#{downloadToken}" #组织下载连接格式：http://<bucket>.qiniudn.com/<key>?token=<downloadToken>
  end
end
