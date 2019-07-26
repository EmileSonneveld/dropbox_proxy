# dropbox_proxy
Make files accessible from the internet. Making relative paths in HTML files work too. Just like the 'Public' folder used to work.

Place this folder on a Apache/PHP server and fill in the gaps in the code. Then your server should be proxying the files from your dropbox using the dropbox API.

No caching is implemented.

Not tested on 2GB+ files.

Tested on PHP Version 5.6.40

Pentested by[`@samvdkris`](https://github.com/samvdkris)

If you spot bugs, please let me know here: contact@emilesonneveld.be

## Examples:
- https://emilesonneveld.be/dropbox_proxy/slimmerWorden/2018-2019-Semester1/CMDM/PROJECT/CTT-editor/www/index.html
- https://emilesonneveld.be/dropbox_proxy/slimmerWorden/2018-2019-Semester2/THESIS/common_lines.html
- https://emilesonneveld.be/dropbox_proxy/dev_freetime/wirehack2019/wirehack2019_build/index.html
