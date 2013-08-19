require 'test_helper'

class UploadControllerTest < ActionController::TestCase
  test "should get without_key" do
    get :without_key
    assert_response :success
  end

end
