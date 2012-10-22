#packfire.ioc
To make dependency managable in a large application, the concept Inverse of Control (or IoC) is applied to Packfire Framework. 

In Packfire, we have normal classes we call Services that are added to Service Bucket (`pServiceBucket` class). All Service Bucket users (extended from `pBucketUser` class) will have access to the service bucket. 

##Controllers and Views

By default, `pController` and `pView` classes are both extended from the `pBucketUser` class, which means that they are both Service Bucket users and have access to the service bucket. 

You can access services through the `service()` method. For example:

    $this->service('debugger')->log('Breakpoint hit');

##Common Services

Here is a list of pre-defined list of services added to the service bucket by the application:

 - `config.app`: A pConfig class consisting of the application configuration.
 - `config.routing`: A pConfig class containing the routing information.
 - `exception.handler`: A pExceptionHandler object that handles exception
 - `debugger`: The pDebugger service that provides access to debugging tools
 - `router`: The pRouter object that provides URL-controller routing services.
 - `session`: The pSession object that provides session services
 - `database`: The pDatabase or pDbSchema object that provides access to the database. This entry is supplied in by the default key in the application configuration.
 - `database.driver`: The driver of the default database service.

Any `pBucketUser` child class that is loaded from the service bucket will have its service bucket filled with the same services as well.