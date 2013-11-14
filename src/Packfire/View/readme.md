#packfire.view
To help you manage your application's view classes easily, Packfire has included functionalities that allow you to create and reuse view. The `pView` abstract class prepares the data loaded by the controller for the template to parse.

The View component of Packfire separates your View manipulation logic and HTML code, which allows web designers and developers to work on front-end development with more ease and haste as PHP view formatting codes are isolated from the HTML code.

##Using pView

`pView` defines several methods which are useful when you are developing your application:

- `define($key, $value)` - Defines a field for the template to replace.
- `route($key, $params = null)` - Creates a URL from the routing key and parameters
- `template($template)` - Set template to be used. If not set, the template with the same name as the view class will be loaded.

##Preparing the View
To prepare the view, implement the `create()` method in your View class and you can write your template field definition in the method. 

    public function create(){
        $this->template('userpage');
        $this->define('homeUrl', $this->route('home'));
        // ...
    }

