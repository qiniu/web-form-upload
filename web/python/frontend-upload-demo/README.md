##Design

    http://127.0.0.1:5000/

    | 1. GET                             ^
    v                                    | 4. POST

    <form><input type="file">       <form><input type="text" name="cdn_resource_url">

    | 2. $.ajax (FormData, IE 10+)       ^
    v                                    | 3. response

    http://upload.qiniu.com

##Installation

    (env)$ pip install -r requirements.txt
    (env)$ python app.py
     * Running on http://127.0.0.1:5000/
     ...

##Notes

1. For "best practice" of a larger Flask project, please google `fbone`, `flaskbb`, etc..
2. I'm using html5 [FormData](http://stackoverflow.com/a/5976031/707580). It's claimed that only supported by IE 10+. Who cares?
