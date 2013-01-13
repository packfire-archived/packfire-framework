
#packfire.routing
Packfire's Routing package provides ease in managing URLs and access to controllers to your web application. All web requests to your application is routed through the `Router` class as Packfire uses the Front Controller Pattern. 

##Routing Defintitons

You can manage all your URL route definitions in the `routing.yml` configuration file found in the `config` folder of your application. An example of a route entry in the routing configuration file:

    home: 
      rewrite: "/"
      actual: "Home"
    themeSwitch:
      rewrite: "/theme/switch/{theme}"
      actual: "ThemeSwitch:switch"
      method: 
	- get
        - post
      params:
        theme: "([a-zA-Z0-9]+)"

- `themeSwitch`: this is the routing key, which uniquely identifies the routing entry.
- `rewrite`: the rewritten URL. You can enter parameters in the URL like templates.
- `actual`: The actual controller and action to be executed. You can leave out the "Controller" at the end of the class name. The `:` separates the class name and the action name. The action name is optional and if left out the default action name "index" is used.
- `method`: The HTTP method that the route is catered for. 
- `params`: The hash map of parameters with its regular expression.

The routing package in Packfire is powerful. Each parameter is parsed with regular expressions, which allows you to filter and validate your input data in the URL at the first stage.

##Creating URLs
You can easily create URLs in the controller by calling the `route()` method. 

###The YAML configuration

    viewBookPage:
      rewrite: "/books/{bookId}-{slug}"
      actual: "BookController:view"
      method: "get"
      params:
        bookId: "int"
        slug: any

###Code in Controller and View classes

To create URLs from the routing key, you can call the `route()` method in both `Controller` and `View` classes, like this:

    $url = $this->route('viewBookPage', 
                   array('bookId' => $bookId, 'slug' => Text::slugify($bookName))
               );
    $this->redirect($url);

Updates to your URLs in the YAML configuration will also update all your URL generation. You do not need to hard code or worry about URL changes. Sweet!