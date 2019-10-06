# Rationale
Surprised to see your key colleague ran out for vacation again?
Yes, *there was an email about it 2 months ago*..
Usually we forget about it same minute, and realize the fact when it is too late.
You don't want another vacation to ruin your sprint again, do you?

Then welcome a solution to **sync all vacations from any HR system to any Calendar**, so you always know who is going to be AFK!

# How To Start
* install node and puppeteer
```bash
npm install @nesk/puphpeteer
```

* install dependencies
```bash
composer install
```
* create and fill `app/config/config.php` file
```bash
cp app/config/config.example.php app/config/config.php
nano app/config/config.php
```
* finally, put the script to be run every minute. It will export all vacations to your Outlook Calendar. 
```php
php sync.php
```

# Security concerns

### Anything -> MS Exchange integration
MS Exchange API (known as Exchange Web Services) requires plain-text user password to be specified.
To workaround this, the password is requested when the script starts. Thus, it is never stored on disk or github as plain text.
It makes the solution is enterprise ready and 100% IT-security compliant.  

# Known Limitations
 * only [Securex HR Online](https://www.securex.lu/en/our-it-tool-hronline/9) -> MS Exchange Calendar is supported
 * please be patient, local Outlook client needs some time to synchronize calendar events (< 1 min)

# TODO
* delete vacation events from Calendar if they disappear from Securex
* support custom observable periods (currently hardcoded to 1 month)
* if a vacation occupies more than 1 week, it is represented as multiple Calendar events - fix it
* support more Calendars - Google Calendar, Zoho Calendar, etc.
* support more ATS - BambooHR, Recruitee, Manatal, Oracle Taleo, etc.
* dockerize
* tests

# LICENSE
See LICENSE file
