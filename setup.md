# Setup Details

## Environment

You will need the following installed

* PHP 7.2.6+
* Apache webserver
* MySQL 5.7.14+
* Node JS 8+

## Installation

### Webserver

The webserver should have the following configuration setup:

* `mod_php` for the appropriate php version should be enabled
* `mod_rewrite` should be enabled.
*   The root of the domain used for Blend-Exchange should be configured to serve the contents of the `/public` folder.
    Example Apache configuration:

    ```text
    <VirtualHost *:80>
        DocumentRoot "C:\Blend-Exchange\public"
        ServerName blend-exchange.localhost
        <Directory "C:\Blend-Exchange\public">
            Order allow,deny
            Allow from all
            Require all granted
        </Directory>
    </VirtualHost>
    ```

### Packages/Build

Run `composer install` to install. To setup the database run `vendor/bin/phinx migrate -e dev`. If you have any issue installing stuff, please post an issue so it can be added to the requirements/instructions.

To build the front end, run `npm install` and then `npm run build` to watch the frontend files for changes.

## Environment File
Copy `.env.example` to a file called `.env` and populate the missing values.

### Stack Exchange Authentication

Needed tokens can be obtained by registering an application here https://stackapps.com/apps/oauth/register

### Google Drive Authentication

#### Getting OAuth Credentials

Use the [Google Developer Console](https://console.developers.google.com) to create a new project. Under OAuth consent screen, set the project name. Then under APIs/Services > Credentials, create a new OAuth client ID. Choose Web Client. At this point it should give you the Client ID and Client secret. They respectively are the values for `GOOGLE_DRIVE_CLIENT_ID` and `GOOGLE_DRIVE_CLIENT_SECRET` in the `.env`. Under authorized redirect URIs put

```
https://developers.google.com/oauthplayground 
```

To use the oauthplayground to retrieve the refresh token as explained in the next section.


#### Getting A Refresh Token for your Google Drive Account

To get the OAuth Keys for the google drive account, go to https://developers.google.com/oauthplayground/

Click on the settings icon (cog in upper right hand corner), and check *Use your own Oauth credentials*. Fill in your Client Id and Client Secret.

On the left hand panel, input the following scopes:

```
https://www.googleapis.com/auth/drive
```

Exchange for codes. You will now have the `GOOGLE_DRIVE_REFRESH_TOKEN` and `GOOGLE_DRIVE_ACCESS_TOKEN` needed by the .env file.

### Backblaze Cloud Storage

Create a backblaze cloud storage account, and create a new bucket.
Copy the master key, and the master key id into the ACCOUNT_ID and APPLICATION_KEY configuration items. 

Create a bucket in backblaze, and copy the name of that bucket into the BUCKET_NAME configuration item.

## Site Commands

Some additional useful commands can be accessed by running `php site`. in the terminal.
