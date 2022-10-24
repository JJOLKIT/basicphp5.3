<?php
header('Content-Type: application/json');
$base = 'images/';
$root = realpath(dirname(__FILE__) .'/..').'/images/';
$relpath = isset($_REQUEST['path']) ?  $_REQUEST['path'] : ''; // Use options.uploader.pathVariableName

$path = $root;



// Do not give the file to load into the category that is lower than the root
if (realpath($root.$relpath) && is_dir(realpath($root.$relpath)) && strpos(realpath($root.$relpath).'/', $root) !== false) {
    $path = realpath($root.$relpath).'/';
}



$errors = [
    'There is no error, the file uploaded with success',
    'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    'The uploaded file was only partially uploaded',
    'No file was uploaded',
    'Missing a temporary folder',
    'Failed to write file to disk.',
    'A PHP extension stopped the file upload.',
];

// Black and white list
$config = [
    'white_extensions' => ['png', 'jpeg', 'gif', 'jpg'],
    'black_extensions' => ['php', 'exe', 'phtml'],
];

// function for creating safe name of file
function makeSafe($file) {
    $file = rtrim($file, '.');
    $regex = ['#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#'];
    return trim(preg_replace($regex, '', $file));
}

$result = (object)['error'=> 0, 'msg'=>[], 'files'=> []];

function warning_handler($errno, $errstr) {
    global $result;
    $result->error = $errno;
    $result->msg[] = $errstr;
    exit(json_encode($result));
}

set_error_handler('warning_handler', E_ALL);

//Here 'images' is options.uploader.filesVariableName
if (isset($_FILES['images']) 
    and is_array($_FILES['images']) 
    and isset($_FILES['images']['name']) 
    and is_array($_FILES['images']['name']) 
    and count($_FILES['images']['name'])
) {
    foreach ($_FILES['images']['name'] as $i=>$file) {
        if ($_FILES['images']['error'][$i]) {
            trigger_error(isset($errors[$_FILES['images']['error'][$i]]) ? $errors[$_FILES['images']['error'][$i]] : 'Error', E_USER_WARNING);
            continue;
        }
        $tmp_name = $_FILES['images']['tmp_name'][$i];
        if (move_uploaded_file($tmp_name, $file = $path.makeSafe($_FILES['images']['name'][$i]))) {
            $info = pathinfo($file);
            // check whether the file extension is included in the whitelist
            if (isset($config['white_extensions']) and count($config['white_extensions'])) {
                if (!in_array(strtolower($info['extension']), $config['white_extensions'])) {
                    unlink($file);
                    trigger_error('File type not in white list', E_USER_WARNING);
                    continue;
                }
            }
            //check whether the file extension is included in the black list
            if (isset($config['black_extensions']) and count($config['black_extensions'])) {
                if (in_array(strtolower($info['extension']), $config['black_extensions'])) {
                    unlink($file);
                    trigger_error('File type in black list', E_USER_WARNING);
                    continue;
                }
            }
            $result->msg[] = 'File '.$_FILES['images']['name'][$i].' was upload';
            $result->images[] = $base.basename($file);
        } else {
            $result->error = 5;
            if (!is_writable($path)) {
                trigger_error('Destination directory is not writeble', E_USER_WARNING);
            } else {
                trigger_error('No images have been uploaded', E_USER_WARNING);
            }
        }
    }
};

if (!$result->error and !count($result->files)) {
    $result->error = 5;
    trigger_error('No files have been uploaded', E_USER_WARNING);
}

exit(json_encode($result));

?>