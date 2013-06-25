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



