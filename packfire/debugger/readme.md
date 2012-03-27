#packfire.debugger
Packfire makes debugging easy for developers. Using the Debugger package in Packfire, you will be able to solve bugs quickly and break down problems within the application easily.

##Setting Debugging Mode

By default, the `local` environment is set to enable debugging. Debugging can be turned on and off in the `app.yml` through the `app.debug` setting.

##Setting your output

Debugger allows you to set which output channel to use. By setting the IDebugOutput channel to use in the IoC configuration file, you can set it use the following channels:

- `pConsoleDebugOutput` - output to the HTML GUI
- `pLogDebugOutput` - output to a log file

##Variable Inspection (Dump)

The `var_dump` function in PHP has been a great help since the beginning of time. Debugger provides the `dump()` method to allow you to inspect your variables. 

    $var = new MyObject();
    $this->service('debugger')->dump($var);

##Logging

You can also write your log messages that will write to the debugger's output by calling the `log()` method.

    $this->service('debugger')->log('Reached this line');

##Exceptions

In the Packfire's application design, all unhanded exceptions are handled by the application method `handleException()`. If debugging mode is enabled, all unhanded exceptions that are passed to the method will also be logged into the debugger. 

On your own, you can also log exceptions like the following:

    try{
        $this->workAction();
    } catch(Exception $ex) {
        $this->service('debugger')->exception($ex);
    }

##Time checks

Simple profiling and performance test for your application is also available in the Debugger. The `timeCheck()` method allows you to check the amount of time taken from the application load till the line when the method is called.

    $this->service('debugger')->timeCheck();