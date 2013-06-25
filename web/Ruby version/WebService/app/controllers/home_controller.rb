class HomeController < ApplicationController
  def index
  	scope="wyangspace"
    @token=Qiniu::RS.generate_upload_token :scope => "wyangspace"
                                          
  end
end
