
#packfire.routing
Packfire's Routing package provides ease in managing URLs and access to controllers to your web application. All web requests to your application is routed through the `pRouter` class as Packfire uses the Front Controller Pattern. 

##Routing Defintitons

You can manage all your URL route definitions in the `routing.yml` configuration file found in the `config` folder of your application. An example of a route entry in the routing configuration file:

    themeSwitch:
      rewrite: "/theme/switch/{theme}"
      actual: "ThemeSwitch"
      method: "switch"
      params:
        theme: "([a-zA-Z0-9]+)"

- `themeSwitch`: this is the routing key, which uniquely identifies the routing entry.
- `rewrite`: the rewritten URL. You can enter parameters in the URL like templates.
- `actual`: The actual controller to be executed. You can leave out the "Controller" at the end of the class name.
- `method`: the action of the controller to call. If this is not entered, the index method will be called.
- `params`: The hash map of parameters with its regular expression.

The routing package in Packfire is powerful. Each parameter is parsed with regular expressions, which allows you to filter and validate your input data in the URL at the first stage.

##Creating URLs
You can easily create URLs in the controller by calling the `route()` method. 

###The YAML configuration

    postPage:
      rewrite: "/books/page/{postId}"
      actual: "Page"
      method: "switch"
      params:
        theme: "([a-zA-Z0-9]+)"

###Code in Controller


    $postUrl = $this->route('postPage', 
                   array('pageId' => $pageId)
               );
    $this->redirect($postUrl);

Updates to your URLs in the YAML configuration will also update all your URL generation. You do not need to hard code or worry about URL changes. Sweet!