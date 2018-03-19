<?php
//!!!!!!!!!! WARNING THIS DOES NOT YET ACCOUNT FOR REPOS WITHOUT TAGS!!!!!!!!!!!!!!!!!!!

//Check for user version tag argument
$userDefinedTag = "";

//Name of file that checkout logs will be dumped into.
$logFileName = "/container/application/gitCheckoutLog.txt";

//!!!!!! UNCOMENT FOR LIVE ENVIRONMENT !!!!!!
chdir('/container/application/public');

//If present sets the desired tag version from commandline
if($argc > 1){
    $userDefinedTag = $argv[1];
}

//Get the most recent details from the repo then
//Fetch the tag details and turn them into array.
echo shell_exec('pwd');
shell_exec('git fetch --all');

$list = (shell_exec('git tag'));
$splitList = explode("\n",$list);

//var_dump($splitList); for debugging

array_pop($splitList);

//Check if the user specified tag is present otherwise
//pull the most recent.
$tagToPull;
if(in_array($userDefinedTag, $splitList)){
    $tagToPull = $userDefinedTag;
    echo 'Tag found, using specified tag: ' . $tagToPull ." \n";
} else {
    $tagToPull = end($splitList);
    echo 'Requested tag not found, using latest tag: ' . $tagToPull . " instead. \n";
}

//Proceed to checkout the last
shell_exec('git checkout '. $tagToPull);

//Composer install all the correct dependancies.
shell_exec('composer install --no-dev');

//Write to the log
$oldFileContent = file_get_contents($logFileName);
$newFileContent = $oldFileContent . "\nChecked out tag: ". $tagToPull . " at ". date("Y-m-d H:i:s");
file_put_contents($logFileName, $newFileContent);
