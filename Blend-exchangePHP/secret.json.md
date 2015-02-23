# Secret.json

There is a missing file, and it is missing for good reason, since it contains sensitive information. it is called `secret.json` and should be here in this directory

This is how it should look:

```json
{
    "key": "[google drive api key]",
    "secret": "[google drive client secret]",
    "cid": "[google drive client id]",
    "accessToken": "[google drive access token]",
    "refreshToken": "[google drive refresh token]",
    "mysql": {
        "host": "localhost",
        "user": "blend-exchange",
        "password": "[MYSQL database password]",
        "database": "blend-exchange"
    }
}
```

