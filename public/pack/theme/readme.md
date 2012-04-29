#Application Theme Folder
###Packfire Framework for PHP

___

The theme folder contains all your AppTheme implementations. All application theme
definition will be contained in this folder.

---

In Packfire, you can define themes to help you switch between different kind of views quickly and easily. All definitions can be made in your theme classes' `render()` method using the `define()` method:

	class DefaultTheme extends AppTheme{
	
   	    public function render(){
    	    $this->define('color', 'black');
        	$this->define('maxWidth', '100%');
    	}
	
	}
	
You can retrieve these theme definitions in your template file by using `{theme.*}` template tags. For example if you defined `'color'` in your theme, you can write `{theme.color}` in your template to use the defined theme template tag.