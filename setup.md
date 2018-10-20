# Setup Details

## Environment

You will need the following installed

* PHP 7.2.6+
* Apache webserver
* MySQL 5.7.14+
* Node JS 8+

## Installation

Run `composer install` to install. To setup the database run `vendor/bin/phinx migrate -e dev`. If you have any issue installing stuff, please post an issue so it can be added to the requirements/instructions.

To build the front end, run `npm install` and then `npm run build` to watch the frontend files for changes.

## ENVIRONMENT FILE
Copy `example.env` to a file called `.env` and populate the missing values.

### Stack Exchange Authentication

Needed tokens can be obtained by registering an application here https://stackapps.com/apps/oauth/register

### Google Drive Authentication

#### Getting Oauth Credientails

Go to:
https://console.developers.google.com
and register an application with the Google Drive V3 enabled. Copy the Client ID and Client Secret. They respectively are the values for `GOOGLE_DRIVE_CLIENT_ID` and `GOOGLE_DRIVE_CLIENT_SECRET` in the .env

#### Getting A Refresh Token for your Google Drive Account

To get the Oauth Keys for the google drive account, go to 
https://developers.google.com/oauthplayground/

Click on the settings icon (cog in upper right hand corner), and check *Use your own Oauth credentials*. Fill in your Client Id and Client Secret

On the left hand panel, input the following scopes:

```
https://www.googleapi.com/auth/drive
```

Exchange for codes. You will now have the `GOOGLE_DRIVE_REFRESH_TOKEN` and `GOOGLE_DRIVE_ACCESS_TOKEN` needed by the .env file.

## Site Commands

Some additionally useful commands can be accessed by running `php site`. in the terminal.