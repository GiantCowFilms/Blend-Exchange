# Blend-Exchange 2.0

Completely re-written because the old version was a hacky weekend project that out-grew its very humble beginnings.

## About blend exchange. 

http://blend-exchange.giantcowfilms.com/about

## Installation

see [Setup.md](setup.md).

## Help, something is broke!

If you are having trouble modifying blend-exchange, please <a href='http://giantcowfilms.com/contact'>contact me</a>. 

## About the codebase

### Overall Structure

This codebase is split between a front end app, and API and a backend app. Basically, a page is either delivered via vue + json api endpoint or a twig template. Routes to API end points and twig templates are defined in `src/Routes.php`. Router to pages shown via vue are in `client/router.js`. 

#### Backend Lifecycle

All code enters through either the `/site` file or the `/public/index.php` which only contain code to call a function from `src/Bootstrap.php`. `Bootstrap.php` in turn calls `Dependencies.php`.

If the application is entered via the webserver, `Bootstrap.php` calls `/Kernel/Http.php` which calls into the controller defined for the route in `src/Routes.php`. Controllers return a `Response`. 

If the application is entered via the command line, `Bootstrap.php` calls into a Symfony command application. 


### Migrations

Note, there are some very odd migrations that do not rollback correctly that are used to update a legacy compatible version of the database. They shouldn't really be an issue if you are starting fresh.

Migrations are done using Phinx, and can be found in the `migrations/` folder. 

### Design files

Any image that is not served by the webserver can be found in `resources/`.

Inside resources there is the `resources/Originals/` folder. This is where you can put any editor files/files that are used to edit the images on the site. SVGs only belong in `Originals/` if they are used to render other image formats that are used by the application. If used by directly, simply place them where they application expects to find them.

Additional, rendered png for ads go into `resources/Ads`. Fonts go into `resources/Fonts`.