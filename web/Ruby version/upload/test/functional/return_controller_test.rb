require 'test_helper'

class ReturnControllerTest < ActionController::TestCase
  test "should get index" do
    get :index
    assert_response :success
  end

end
