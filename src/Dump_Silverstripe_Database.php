<?php

if ((php_sapi_name() === 'cli')) {
    //find the safe dir ...
    require_once('dirs.php');

    $command = 'mysqldump -u '.SS_DATABASE_USERNAME.' -p'.SS_DATABASE_PASSWORD.' -h '.SS_DATABASE_SERVER.' '.SS_DATABASE_NAME.' > '.$cwd.SS_DATABASE_NAME.'.sql';
    var_dump(shell_exec($command));
    if (file_exists($cwd.SS_DATABASE_NAME.'.sql')) {
        echo '---------- DUMPED ------------';
    } else {
        echo '---------- FAILED ------------';
    }

    echo '
    ';
} else {
    die('Please access from command line ...');
}
