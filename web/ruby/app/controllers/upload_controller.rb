class UploadController < ApplicationController
  DOMAIN = 'aatest.qiniudn.com'

  def without_key
    policy = Qiniu::Rs::PutPolicy.new({ :scope => 'a',
                                        :return_url => 'http://localhost:3000/upload/done',
                                        :end_user => 'userId'})
    @token = policy.token()
  end

  def with_key
    policy = Qiniu::Rs::PutPolicy.new({ :scope => 'a',
                                        :return_url => 'http://localhost:3000/upload/done',
                                        :end_user => 'userId'})
    @token = policy.token()
  end

  def done
    @data = Qiniu::Utils.urlsafe_base64_decode params[:upload_ret] unless params[:upload_ret].nil?
    @data = params.to_s if params[:upload_ret].nil?

    k = Qiniu::Utils.safe_json_parse(@data) 

    policy = Qiniu::Rs::GetPolicy.new()
    @url = policy.make_request Qiniu::Rs.make_base_url(DOMAIN, k['key']), nil unless k['key'].nil?
  end
end
