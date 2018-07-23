<?php

//!!!!!!!!!! WARNING THIS DOES NOT YET ACCOUNT FOR REPOS WITHOUT TAGS!!!!!!!!!!!!!!!!!!!
if ((php_sapi_name() === 'cli')) {
    //Check for user version tag argument
    $userDefinedTag = '';

    //find the safe dir ...
    $safeDir = dirname(realpath($argv[0]));
    $found = false;
    while ($found === false && file_exists($safeDir)) {
        if (file_exists('.htaccess')) {
            //we are in public territory... NOT GOOD
        } elseif (file_exists($safeDir.'/_ss_environment.php')) {
            $found = true;
        } elseif (file_exists($safeDir.'/.env')) {
            $found = true;
        }
        if ($found === false) {
            $safeDir = dirname($safeDir);
        }
    }

    //now find the public directory ...
    $options = [
        'public',
        'wwww',
        'public_html'
    ];

    foreach ($options as $option) {
        if (file_exists($safeDir.'/'.$option)) {
            $publicDir = $safeDir.'/'.$option;
            break;
        }
    }


    //Name of file that checkout logs will be dumped into.
    $logFileName = '_git_log.txt';


    //If present sets the desired tag version from commandline
    if ($argc > 1) {
        $userDefinedTag = $argv[1];
    }

    ########## ########## ##########
    ########## START ACTION
    ########## ########## ##########
    echo '========================'. PHP_EOL;
    echo 'SAFE DIR: '.$safeDir. PHP_EOL;
    echo 'PUBLIC DIR: '.$publicDir. PHP_EOL;
    echo '========================'. PHP_EOL;

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
    $oldFileContent = file_get_contents($safeDir.'/'.$logFileName);
    
    $newFileContent = $oldFileContent . PHP_EOL . ' - '. $tagToPull . ' - '. date('Y-m-d H:i');
    if($checkoutResult == null){
		$newFileContent = $oldFileContent . PHP_EOL . ' - FAILED TO CHECKOUT TAG: '. $tagToPull . ' - '. date('Y-m-d H:i');
		echo "------------- GIT CHECKOUT FAILED !!!!! --------------------";
	} 
    file_put_contents($safeDir.'/'.$logFileName, $newFileContent);

    //Composer install all the correct dependancies.
    shell_exec('composer install --no-dev');

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
}
