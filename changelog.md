# Changes (Unkown Date)

 * Complete re-write of the code. Very complete re-write. Lots of bad code burned to the ground.

Specifically, the following were added 

 * More accurate conversion from bytes to MB/GB
 * Database Migrations Added
 * Routing Added
 * Twig Templates Added
 * Vue Frontend added
 * Removed dropzone.js (at the cost of support for IE < 9)
 * Validation Added
 * Controllers Added
 * Blend File Handlers Added
 * StackExchange API integration Added
 * Support for composer added.
 * Old user accounts removed. Only OAuth from now on.
 * Superior hashing algorithm used for IP addresses
 * Eloquent ORM integrated
 * Overhaulled app config
 * Moved application code out of webserver root ('bout time)
 * Added JSON API
 * Plans to support Unit tests.
 * Better handling of security in general.
 * Uploaded files are gziped to save space.
 * Removed Incrementing IDs in favor of random ids.

 # Hosting Changes (May 20th through June nth)

  * Changed most passwords (to very secure long hyper random non-memorable ones) on the host server, and some SSH keys.
