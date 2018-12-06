<?php

if ((php_sapi_name() === 'cli')) {
    //Check for user version tag argument
    require_once('dirs.php');

    //Name of file that checkout logs will be dumped into.
    $logFileName = '_git_log.txt';


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

    //Proceed to checkout the last
    $checkoutResult = shell_exec('git pull origin master');

    //Write to the log
    $logFile = $safeDir.'/'.$logFileName;
    if(file_exists($logFile)) {
        $oldFileContent = file_get_contents($logFile);
    } else {
        $oldFileContent = '';
    }

    $newFileContent = $oldFileContent . PHP_EOL . ' - master- '. date('Y-m-d H:i');
    if($checkoutResult == null){
        $newFileContent = $oldFileContent . PHP_EOL . ' - FAILED TO CHECKOUT: master - '. date('Y-m-d H:i');
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
You are now at the lastest master
========================

';
} else {
    die('Please access from command line ...');
}
