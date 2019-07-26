<?php

// Go to https://www.dropbox.com/developers/documentation/http/documentation#file_requests-get
// And click <get acces token> and paste the result here
// Keep this secret!
$authorisation_header_string = 'Authorization: Bearer XXXXXXXXXXXXXXXXXXXXXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';


// Here you can modify what files whould be accesible from the proxy
// Most code here refers to my Dropbox files and folders. I left them as a stub

if (
       (strpos($path, 'notes.txt') !== false)
    || (strpos($path, 'notes.md') !== false)
    ){

    // On my website, I have WordPress installed and I use their authentication system to protect some files
    // By default, the WordPress session cookie is HTTP only and so is safe against session stealing
	require_once($_SERVER['DOCUMENT_ROOT']."/wordpress/wp-load.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/wordpress/wp-includes/pluggable.php");
	if(!is_user_logged_in() || !current_user_can('administrator')) DieNoAcces("Not logged as admin in with wordpress.");

}else if(
       (strpos($path, '/Public') !== false)
    || (strpos($path, '/jnm_public/') !== false)
    || (strpos($path, '/dev_freetime/') !== false)
    ){
	// ok. 
}else{
    DieNoAcces("We only serve a select number of files for now.");
}

if(endsWith($path, "/")){
    if( (strpos($path, '/ctt-editor-files') !== false)){
        // ok
    }else{
        DieNoAcces("Directory listing is not possible everywhere.");
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST") {
    if( (strpos($path, '/ctt-editor-files') !== false) ){
        // ok
    }else{
        DieNoAcces("Changing files is not permitted everywhere.");
    }
}

?>