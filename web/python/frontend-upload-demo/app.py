from flask import Flask, render_template, current_app, request


class Config:
    QINIU_ACCESS_TOKEN = 'iN7NgwM31j4-BZacMjPrOQBs34UG1maYCAQmhdCV'
    QINIU_SECRET_TOKEN = '6QTOr2Jg1gcZEWDQXKOGZh5PziC2MCV5KsntT70j'
    QINIU_DOMAIN = "aatest.qiniudn.com"
    QINIU_BUCKET = 'a'


app = Flask(__name__)
app.config.from_object(Config)


@app.route('/', methods=['GET', 'POST'])
def index():
    import qiniu.conf
    qiniu.conf.ACCESS_KEY = current_app.config['QINIU_ACCESS_TOKEN']
    qiniu.conf.SECRET_KEY = current_app.config['QINIU_SECRET_TOKEN']

    import qiniu.rs
    p_policy = qiniu.rs.PutPolicy(current_app.config['QINIU_BUCKET'])
    upload_token = p_policy.token()

    import uuid
    key = 'user-avatar-' + str(uuid.uuid1())

    context = {
        'key': key,
        'upload_token': upload_token,
    }

    if request.method == 'POST':
        nickname = request.form['nickname']
        avatar = request.form['avatar']
        # save to database
        context['nickname'] = nickname

        base_url = qiniu.rs.make_base_url(current_app.config['QINIU_DOMAIN'], avatar)
        g_policy = qiniu.rs.GetPolicy()
        private_url = g_policy.make_request(base_url)

        context['avatar'] = private_url

    return render_template('index.html', **context)


if __name__ == '__main__':
    app.debug = True
    app.run()
