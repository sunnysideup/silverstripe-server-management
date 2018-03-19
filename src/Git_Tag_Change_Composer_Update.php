<?php
//!!!!!!!!!! WARNING THIS DOES NOT YET ACCOUNT FOR REPOS WITHOUT TAGS!!!!!!!!!!!!!!!!!!!

//Check for user version tag argument
$userDefinedTag = "";

$dir = '/container/application/public';

//Name of file that checkout logs will be dumped into.
$logFileName = "/container/application/gitCheckoutLog.txt";

//!!!!!! UNCOMENT FOR LIVE ENVIRONMENT !!!!!!
chdir($dir);

//If present sets the desired tag version from commandline
if($argc > 1){
    $userDefinedTag = $argv[1];
}

//Get the most recent details from the repo then
//Fetch the tag details and turn them into array.
$pwd = shell_exec('pwd');
if($pwd !== $dir) {
    echo 'You are not in the right directory: '.$dir."\n";
    exit();
}
shell_exec('git fetch --all');

$list = (shell_exec('git tag'));
$splitList = explode("\n",$list);

//var_dump($splitList); for debugging

array_pop($splitList);

$tagToPull = '';
if(count($splitList) === 0) {
    $tagToPull = 'master';   
}

//Check if the user specified tag is present otherwise
//pull the most recent.
if($tagToPull === 'master') {
     echo 'No tags available, using: ' . $tagToPull . "\n";
    //do nothing
}
elseif(in_array($userDefinedTag, $splitList)){
    $tagToPull = $userDefinedTag;
    echo 'Tag found, using specified tag: ' . $tagToPull ."\n";
} else {
    $tagToPull = end($splitList);
    echo 'Requested tag not found, using latest tag: ' . $tagToPull . " instead.\n";
}

//Proceed to checkout the last
shell_exec('git checkout '. $tagToPull);

//Composer install all the correct dependancies.
shell_exec('composer install --no-dev');

//just in case ...

//remove _dev folder
shell_exec('rm _dev -rf');
//remove databases
shell_exec('find . -name "*.sql.gz" -exec rm "{}" \;');
shell_exec('find . -name "*.sql" -exec rm "{}" \;');

//Write to the log
$oldFileContent = file_get_contents($logFileName);
$newFileContent = $oldFileContent . "\nChecked out tag: ". $tagToPull . " at ". date("Y-m-d H:i");
file_put_contents($logFileName, $newFileContent);

echo '
========================
You are now at '.shell_exec('git describe --tags ').'
========================
';
