<?php

if ((php_sapi_name() === 'cli')) {

    require_once('dirs.php');

    $command = 'mysqldump -u '.SS_DATABASE_USERNAME.' -p'.SS_DATABASE_PASSWORD.' -h '.SS_DATABASE_SERVER.' '.SS_DATABASE_NAME.' > '.$cwd.'database.sql.sql';
    echo '# '.$command.PHP_EOL;
    var_dump(shell_exec($command));
    if (file_exists('/container/application/database.sql')) {
        echo '---------- DUMPED ------------';
    } else {
        echo '---------- FAILED ------------';
    }
    if(file_exists($publicDir.'/public/assets')) {
        $command = 'tar czf '.$cwd.'assets.tar.gz '.$publicDir.'/public/assets';
        echo '# '.$command.PHP_EOL;
        var_dump(shell_exec($command));
    }
    elseif(file_exists($publicDir.'/assets')) {
        $command = 'tar czf '.$cwd.'assets.tar.gz '.$publicDir.'/assets';
        echo '# '.$command.PHP_EOL;
        var_dump(shell_exec($command));
    }

    $command = 'tar cf '.$cwd.''.SS_DATABASE_NAME.'.sspak '.$cwd.'assets.tar.gz '.$cwd.'database.sql.gz';
    echo '# '.$command.PHP_EOL;
    var_dump(shell_exec($command));
    echo '
    ';
} else {
    die('Please access from command line ...');
}
