#packfire.security
Security in any information system is very crucial as attacks today do not limit to only information theft. In Packfire, we follow the AAA security model to help you develop and secure your web application quickly. AAA stands for **Authentication, Authorization and Accounting**. 

In order to implement such security into your application, you will need to utilize the `AppSecurityModule` class in your application and configure it as a service in the `ioc.yml` configuration file.

##AppSecurityModule

The security module class provides several methods to help you implement the AAA security model.

- `identity($newIdentity = null)` - Set or retrieve the identity of the user.
- `authenticate($token)` - Authenticate the identity with a token (password, Secret Key etc)
- `authorize($route)` - Authorize to see if the user has access to the route entry.

All you need to do is to override each method to fit your security requirements. You can also make `AppSecurityModule` extend from our pre-defined classes such as `pDatabaseUserSecurityModule`.