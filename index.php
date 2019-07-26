<?php

// Put $debug on false as soon as you are set up! Otherwise hackers can gain information trough debug messages.
$debug = true; 
if($debug){
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

// Important to give not much info in the error messages.
function DieNoAcces($reason)
{
    global $debug;
    header('HTTP/1.0 401 Unauthorized');
    if($debug)
        die("No access. ".$reason); //Only give reason while debugging.
    else
        die("No access.");
}

// Example url: http://my-cool-website.be/dropbox_proxy/Public/vub_notes.txt
$path = false;
if (isset($_SERVER['ORIG_PATH_INFO']))
    $path = $_SERVER['ORIG_PATH_INFO'];
if (isset($_SERVER['PATH_INFO']))
    $path = $_SERVER['PATH_INFO'];
if ($path == false){
    //$path = "/";
    DieNoAcces("No path");
}

//$path = substr($path, 1);
if ((strpos($path, '..') !== false) 
    || (strpos($path, '"') !== false)
    || (strpos($path, '\'') !== false)
    || (strpos($path, '*') !== false)
) {
    DieNoAcces("Ignore shady paths");
}
if (strlen($path) == 0 || $path[0] !== "/") {
    DieNoAcces("Ignore shady paths");
}

require_once("settings.php");

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}


function filelist_dropbox_to_emile_format($dropboxJson)
{
    $dropbox = json_decode($dropboxJson);
    $emile = array();
    //return json_encode($dropbox->entries);
    foreach ($dropbox->entries as $key => $value) {
        $obj = array();
        $obj["name"] = $value->name;
        array_push($emile, $obj);
    }

    return json_encode($emile);
}

$headers = [
    $authorisation_header_string,
];


// No need to json_encode() here?
$ch = null;
if(endsWith($path, "/"))
{
    // List folder content
    $ch = curl_init("https://api.dropboxapi.com/2/files/list_folder");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"path\": \"/".$path."\",\"recursive\": false,\"include_media_info\": false,\"include_deleted\": false,\"include_has_explicit_shared_members\": false,\"include_mounted_folders\": true}");
    array_push($headers, 'Content-Type: application/json');
}
else if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ch = curl_init("https://content.dropboxapi.com/2/files/upload");
    array_push($headers, 'Dropbox-API-Arg: {"path": "'.$path.'", "mode":"overwrite"}');
    //die("Temporary block!");
    // Seems impossible to get custom headers in PHP7 or so
    //$reqHeaders = getallheaders(); // alias: getallheaders 
    //$file_content = urldecode($reqHeaders["file_content"]); // Name 'file_content' choosen by convention
    $file_content = file_get_contents('php://input');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $file_content);
    array_push($headers, 'Content-Type: application/octet-stream');
}
else
{
    $ch = curl_init("https://content.dropboxapi.com/2/files/download");
    array_push($headers, 'Dropbox-API-Arg: {"path": "'.$path.'"}');
    array_push($headers, 'Content-Type: text/plain');
}

//curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//curl_setopt($ch, CURLOPT_POST, 1); When commenting this out, it will add "Content-Length:-1" when requesting from tools.jnm.be and this crashes dropbox.
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)


// Avoid "SSL certificate problem: unable to get local issuer certificate" on localhost
if($debug)
{
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1); 
}


$output = curl_exec($ch);
if (curl_error($ch)) {
	//echo curl_getinfo($ch, CURLINFO_HEADER_OUT);
    DieNoAcces("Curl error: ".curl_error($ch));
}
curl_close($ch);

function mime_content_type_custom($filename) {

    $mime_types = array(
        'sublime' => 'application/xml',
        'srt' => 'text/plain',
        'htaccess' => 'text/plain',
        'md' => 'text/plain',
        'bib' => 'text/plain',
        'tex' => 'text/plain',
        'owl' => 'application/xml',
        'bak' => 'text/plain',
        'csv' => 'text/plain',
        'R' => 'text/plain',
        'cpp' => 'text/plain',
        'c' => 'text/plain',
        'h' => 'text/plain',
        'cs' => 'text/plain',
        'meta' => 'text/plain',
        'asset' => 'text/plain',
        'anim' => 'text/plain',
        'controller' => 'text/plain',
        'prefab' => 'text/plain',

        '1' => 'text/plain',
        '2' => 'text/plain',
        '3' => 'text/plain',
        '4' => 'text/plain',
        '5' => 'text/plain',
        '6' => 'text/plain',
        '7' => 'text/plain',
        '8' => 'text/plain',
        '9' => 'text/plain',
        '10' => 'text/plain',
        '11' => 'text/plain',
        '12' => 'text/plain',
        '13' => 'text/plain',
        '14' => 'text/plain',
        '15' => 'text/plain',
        '16' => 'text/plain',
        '17' => 'text/plain',
        '18' => 'text/plain',
        '19' => 'text/plain',

        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    $exp = explode('.', $filename);
    $ext = strtolower(array_pop($exp));
    if (array_key_exists($ext, $mime_types)) {
        return $mime_types[$ext];
    }
    elseif (function_exists('finfo_open') && file_exists($filename)) {
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $filename);
        finfo_close($finfo);
        return $mimetype;
    }
    else {
        return 'application/octet-stream';
    }
}

if(endsWith($path, "/")){
    $output = filelist_dropbox_to_emile_format($output);
    header('Content-Type: application/json');
}
else if($_SERVER['REQUEST_METHOD'] == "POST"){
    $output = "This PHP-proxy thinks the file has been written.";
    header('Content-Type: text/plain');
}else
header('Content-Type: '.mime_content_type_custom($path));

echo $output;
?>