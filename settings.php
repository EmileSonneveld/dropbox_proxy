<?php

// Go to https://www.dropbox.com/developers/documentation/http/documentation#file_requests-get
// And click <get acces token> and paste the result here
// Keep this secret!
$authorisation_header_string =
    "Authorization: Bearer XXXXXXXXXXXXXXXXXXXXXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

// Here you can modify what files whould be accesible from the proxy
// Most code here refers to my Dropbox files and folders. I left them as a stub

if (strpos($path, "notes.txt") !== false || strpos($path, "notes.md") !== false) {
    // Require authentication with the wordpress instance that is running on the same website
    // By default, the WordPress session cookie is HTTP only and so is safe against session stealing
    require_once $_SERVER["DOCUMENT_ROOT"] . "/wordpress/wp-load.php";
    include_once $_SERVER["DOCUMENT_ROOT"] .
        "/wordpress/wp-includes/pluggable.php";
    if (!is_user_logged_in() || !current_user_can("administrator")) {
        dieNoAcces("Not logged as admin in with wordpress.");
    }
} elseif (
    strpos($path, "/Public") !== false
) {
    // ok.
} else {
    dieNoAcces("We only serve a select number of files for now.");
}

if (endsWith($path, "/")) {
    if (strpos($path, "/example-directory-where-files-may-be-listed") !== false) {
        // ok
    } else {
        dieNoAcces("Directory listing is not possible everywhere.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (strpos($path, "/example-directory-where-one-can-post-files") !== false) {
        // ok
    } else {
        dieNoAcces("Changing files is not permitted everywhere.");
    }
}
