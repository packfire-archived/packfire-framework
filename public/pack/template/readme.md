#Application Template Folder
###Packfire Framework for PHP

___

The template folder contains all your HTML, Markdown, Smarty, etc. templates. They are loaded accordingly
by the AppTemplate class.

Using the default templating system provided by Packfire, you can define template tags (e.g. `{tag}`, `{title}`, {login}) in your HTML files that will be replaced by the template engine with your actual content. All tags are defined by the opening and closing curly braces.

To set content to a template tag in your view class, simply call the `define()` method.

    $this->define('postBody', $post->body());