# securexhrservices.eu
* if `MYSAPSSO2` cookie is copied manually via DevTools from a valid session, `login out` button does not work. 

### steps
* login normally
* copy `MYSAPSSO2` cookie to clean browser

### expected
User is logged out, login screen appears.

### actual
After a series of redirects, the user is still logged in.
