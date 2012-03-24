#Application Configuration Folder
###Packfire Framework for PHP

___

The configuration folder contains all the configuration file that will be loaded
by the application.

Configuration files can be of the following formats:

- YAML (*.yml, *.yaml)
- INI Configuration File Format (*.ini)

##app.yml

`app.yml` configuration file contains the application configuration settings. 

Note how we have another application configuration file called `app.local.yml`. This `local` file is loaded instead of the default one if the environment of the application is set to `local`. This applies to all the configuration file: if the environment-specific version of the configuration file exists, that file will be loaded. Otherwise the default file is loaded.

##ioc.yml

The `ioc.yml` configuration file sets all the services that will be loaded into the IoC Service Bucket whenever the application runs. These services will be accessible to all your controllers and classes use the IoC service bucket.

Each entry in the IoC configuration file is defined as:

	serviceName:
	  class: packageClassPath
	  parameters:
	    - param1
	    - param2

The service can then be loaded from the service bucket through `$this->service('serviceName')`. Any service loaded into the bucket that requires the use of the bucket will be provided the access to do so.

##routing.yml
You can manage all your URL route definitions in the `routing.yml` configuration file. An example of a route entry in the routing configuration file:

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
