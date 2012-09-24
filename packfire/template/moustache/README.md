![Packfire Moustache](http://i.imgur.com/HQo3a.png)

Packfire Moustache is a lightweight Mustache implementation for Packfire. The logic-less template rendering engine is originally named "[Mustache](http://mustache.github.com/)", whereas [Packfire's](http://github.com/packfire/packfire-moustache) is distinct by the 'o' (British spelling). 

Prior to Mustache, Packfire has adopted a very simple token-replace templating engine that uses single curly braces to indicate tokens.

    <h1>
      {title}
    </h1>
    <p>{message}</p>

However, the simple templating parser is way too primitive and does not support nesting and listing like Mustache does. Hence, Packfire adopted the Mustache templating engine as its default templating engine.

However, we found the [PHP Mustache implementation](https://github.com/bobthecow/mustache.php) by [bobthecow](https://github.com/bobthecow) to be too heavy and thus decided to come up with our own from ground zero. Moustache has been tested against the provided spec tests.

Packfire Moustache uses double curly braces to indicate tokens, supports escaping by default and supports nested block tokens:

    Hello {{name}}
    You have just won ${{value}}!
    {{#in_ca}}
        Well, ${{taxed_value}}, after taxes.
    {{/in_ca}}

To find out more about how Packfire Moustache works you can refer to the [original Mustache manual](http://mustache.github.com/mustache.5.html) as Mustache is designed to be platform-independent and cross-platform compatible.

##Usage

A quick example:

    include('pMoustache.php');
    $m = new pMoustache('Hello {{planet}}');
    echo $m->parameters(array('planet' => 'World!'))->render();
    // "Hello World!"

And a more in-depth example--this is the canonical Moustache template:

    Hello {{name}}
    You have just won ${{value}}!
    {{#in_ca}}
        Well, ${{taxed_value}}, after taxes.
    {{/in_ca}}

Along with the associated `pMoustache` class:

    class Chris extends pMoustache {
        public $name = "Chris";
        public $value = 10000;
        
        public function taxed_value() {
            return $this->value - ($this->value * 0.4);
        }
        
        public $in_ca = true;
    }


Render it like so:

    $chris = new Chris;
    echo $chris->template($template)->render();

Here's the same thing, a different way:

Create a view object--which could also be an associative array, but those don't do functions quite as well:

    class Chris {
        public $name = "Chris";
        public $value = 10000;
        
        public function taxed_value() {
            return $this->value - ($this->value * 0.4);
        }
        
        public $in_ca = true;
    }


And render it:

    $chris = new Chris();
    $m = new pMoustache();
    echo $m->template($template)->parameters($chris)->render();
