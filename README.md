# dropbox_proxy
Make files accessible with simple HTTP(s) calls. Making relative paths in HTML files work too. Just like the 'Public' folder used to work.

Place this folder on a Apache/PHP server and fill in the gaps in the code. Then your server should be proxying the files from your dropbox using the dropbox API.

No caching is implemented.

Not tested on 2GB+ files.

Tested on PHP Version 5.6.40 and 7.4.33
Formatted with PSR-2

Fixed some issues with SonarCloud
Has been slightly pentested.

If you spot bugs, please let me know here: contact@emilesonneveld.be
