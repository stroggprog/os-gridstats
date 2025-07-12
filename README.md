# os-gridstats
returns status info for the current grid in a variety of formats

**Version**: `1.0.4`
**WARNING**: Requires backend access to the grid database!

## About
Fetches data about the current status of the grid and stats from the last 30 days. By default it will return the data in JSON format, but can return the data in a variety of formats:
- json
- xml
- text
- wtext (has CRLF line endings)
- html
- table (an html table)
- wiki (a DokuWiki/MediaWiki formatted table in plain text)

There is a proxy script provided to act as a pass-through from an external website to your internal website.

## Configuration
Edit `gridstats.php` and change the define()s at the top of the file to suit your purposes.
This file and the `lib` folder should be on the same server as your ROBUST database.

Edit `lib/db_params.php' to reflect your own database parameters. Change the secret to something unique. If you don't wish to use a secret, set it thus:
```php
define( "SECRET", "" );
```

If you are using the proxy script on an external website, edit it to change the address it will call. The proxy script also requires `lib/params.php` and `lib/sendMessage.php`.

## Sample Output
This sample is in JSON format.
```
{
    "error": 0,
    "version": "1.0.3",
    "date-time": "2025-07-12 15:45:54",
    "regions": 11,
    "single_regions": 9,
    "var_regions": 2,
    "total_size_sq_meters": 23658496,
    "total_size_sq_km": 4.864,
    "hg_visitors_last_30_days": 23,
    "hg_visitors_online_now": 0,
    "registered_users": 9,
    "local_users_last_30_days": 4,
    "local_users_online_now": 0,
    "total_active_last_30_days": 27,
    "total_active_online_now": 0,
    "login_url": "http:\/\/moss.mossgrid.uk:8002",
    "website": "https:\/mossgrid.uk",
    "login_screen": "https:\/\/mossgrid.uk\/welcome",
    "grid_status": true
}
```
