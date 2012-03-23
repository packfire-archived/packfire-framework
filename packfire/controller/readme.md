#packfire.controller
The controller is the heart of your pages. You can use the routing functionalities to direct URL requests to controllers and its actions.

Controller is where you interact with your application models, database, authenticate users, set data to views and implement functionalities for your application.

##Controller Actions

Methods that you create with names that start `do` are designated as controller actions. 

	class PostController extends AppController{
	
		public function doCreate(){
	    	$this->filters('title', PostFilter::title());
	    	$this->filters('body', PostFilter::body());
	    	$this->service('database')
				 ->table('posts')
				 ->insert(array(
						'title' => $this->params['title'],
						'body' => $this->params['body']
					));
    		$this->redirect($this->route('post.view',
					 array('postId' => $this->service('database.driver')->lastInsertId())
				));
		}

		public function doDelete(){
			$this->service('database')
				->table('posts')
				->delete(array('postId' => $this->params['id']));
    		$this->redirect($this->route('post.list'));
		}

	}

In the `PostController` example above, `create` and `delete` are both actions of the controller. You can specify restful actions that target specific HTTP methods i.e. `getMessage` is accessed via `GET`, `postMessage` is accessed via `POST`, `deleteMessage` is accessed via `DELETE`.

##Filtering input parameters
In any application, validation of input to the application is very important as they might cause all sorts of errors. Packfire makes validation easy for you by allowing you to specify filters.

Filters will go through your input variables to check if they pass the validation. To set the filters for your parameters, call the `filter()` method:

    $this->filter('theme', array(
                      'trim',
                       new pValidationFilter(new pMatchValidator(array('dark', 'light', 'blue', 'red', 'gloss'))
                 )));

The method accepts a single filter (or function) or an array of filters. 

##Performing URL Redirection

Redirection can be performed through the `redirect($url)` method in the controller.

    $this->redirect($this->route('home'));

##Forwarding Requests

In your controller, it is possible to forward your request to another controller for further processing using the `forward()` method.

    $this->forward('app.controller.RegistrationController', 'validate');

##Setting View in the Controller
In the controller, you can set which view to use. To do that, call the `render($view)` method at the end of your action method. You can use any view classes for rendering in your application.

    $this->render(new HomeView($this->state));