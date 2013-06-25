Blog::Application.routes.draw do
  

  get "download/index"

  match 'upload' => 'home#index'
  match 'download' => 'download#index'
end
