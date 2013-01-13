#Packfire\IoC
To make dependency managable in a large application, the concept Inverse of Control (or IoC) is applied to Packfire Framework. 

In Packfire, we have normal classes we call Services that are added to Service Bucket (`ServiceBucket` class). All Service Bucket users (extended from `BucketUser` class) will have access to the service bucket. 

##Controllers and Views

`\Packfire\Controller\Controller` and `Packfire\View\View` classes are both extended from the `BucketUser` class, which means that they are both Service Bucket users and have access to the service bucket. 

You can access services through the `service()` method. For example:

    $this->service('debugger')->log('Breakpoint hit');

##Common Services

Here is a list of pre-defined list of services added to the service bucket by the application:

 - `config.app`: A Config class consisting of the application configuration.
 - `config.routing`: A Config class containing the routing information.
 - `debugger`: The Debugger service that provides access to debugging tools
 - `router`: The Router object that provides URL-controller routing services.
 - `session`: The Session object that provides session services
 - `database`: The Database or Schema object that provides access to the database. This entry is supplied in by the default key in the application configuration.
 - `database.driver`: The driver of the default database service.

Any `BucketUser` child class that is loaded from the service bucket will have its service bucket filled with the same services as well.