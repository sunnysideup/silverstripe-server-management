<?php

//!!!!!!!!!! WARNING THIS DOES NOT YET ACCOUNT FOR REPOS WITHOUT TAGS!!!!!!!!!!!!!!!!!!!
if ((php_sapi_name() === 'cli')) {
    //Check for user version tag argument
    require_once('dirs.php');

    $userDefinedTag = '';

    //Name of file that checkout logs will be dumped into.
    $logFileName = '_git_log.txt';


    //If present sets the desired tag version from commandline
    if ($argc > 1) {
        $userDefinedTag = $argv[1];
    }



    //!!!!!! UNCOMENT FOR LIVE ENVIRONMENT !!!!!!
    chdir($publicDir);

    //Get the most recent details from the repo then
    //Fetch the tag details and turn them into array.
    $pwd = trim(shell_exec('pwd'));
    if ($pwd === $publicDir) {
        echo 'We are now working on '.$publicDir. PHP_EOL;
    } else {
        echo 'You are not in the right directory: '.$publicDir. PHP_EOL;
        exit();
    }
    shell_exec('git fetch --tags');
    shell_exec('git fetch --all');

    //what tag are we getting ....
    $list = (shell_exec('git tag'));
    $splitList = explode("\n", $list);
    array_pop($splitList);
    $tagToPull = '';
    if (count($splitList) === 0) {
        $tagToPull = 'master';
    }

    //Check if the user specified tag is present otherwise
    //pull the most recent.
    if ($tagToPull === 'master') {
        echo 'No tags available, using: ' . $tagToPull . PHP_EOL;
    //do nothing
    } elseif (in_array($userDefinedTag, $splitList)) {
        $tagToPull = $userDefinedTag;
        echo 'Tag found, using specified tag: ' . $tagToPull. PHP_EOL;
    } else {
        $tagToPull = end($splitList);
        echo 'Requested tag not found, using latest tag: ' . $tagToPull . ' instead'.PHP_EOL;
    }

    //Proceed to checkout the last
    $checkoutResult = shell_exec('git checkout '. $tagToPull);

    //Write to the log
    $logFile = $safeDir.'/'.$logFileName;
    if(file_exists($logFile)) {
        $oldFileContent = file_get_contents($logFile);
    } else {
        $oldFileContent = '';   
    }
    
    $newFileContent = $oldFileContent . PHP_EOL . ' - '. $tagToPull . ' - '. date('Y-m-d H:i');
    if($checkoutResult == null){
		$newFileContent = $oldFileContent . PHP_EOL . ' - FAILED TO CHECKOUT TAG: '. $tagToPull . ' - '. date('Y-m-d H:i');
		echo PHP_EOL . "------------- GIT CHECKOUT FAILED !!!!! --------------------" . PHP_EOL;
	} 
    file_put_contents($logFile, $newFileContent);

    //Composer install all the correct dependancies.
    shell_exec('composer install --no-dev --prefer-dist');

    //just in case ...

    //remove _dev folder
    shell_exec('rm _dev -rf');
    //remove READMEs
    shell_exec('rm README.md');
    //remove databases
    shell_exec('find . -name "*.sql.gz" -exec rm "{}" \;');
    shell_exec('find . -name "*.sql" -exec rm "{}" \;');

    echo '

========================
You are now at '.shell_exec('git describe --tags ').'
========================

';
} else {
    die('Please access from command line ...');
}
