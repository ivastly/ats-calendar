## required steps
* register Outlook app https://docs.microsoft.com/en-us/previous-versions/office/office-365-api/api/version-2.0/use-outlook-rest-api#register-and-authenticate-your-app
* configure _Authentication_ for the app as type = _Web_, url = _http://localhost:8080/sync.php_
* configure following API permissions for the app:
    - Calendars.ReadWrite
    - Calendars.ReadWrite.Shared
    - Tasks.ReadWrite.Shared
    - User.Read
    - offline_access
    - openid
 * start local web server
 ```bash
php -S localhost:8080
```
* go to [localhost:8080](http://localhost:8080]), authenticate
* save the token to .env variable
* finally, put the script to be run every day. It will export all vacations to your Outlook Calendar. 
