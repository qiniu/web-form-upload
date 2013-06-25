# Create your views here.
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
