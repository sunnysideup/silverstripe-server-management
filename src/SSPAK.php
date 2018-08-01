<?php

if ((php_sapi_name() === 'cli')) {

    require_once('dirs.php');

    $command = 'mysqldump -u '.SS_DATABASE_USERNAME.' -p'.SS_DATABASE_PASSWORD.' -h '.SS_DATABASE_SERVER.' '.SS_DATABASE_NAME.' > '.$publicDir.'/database.sql.sql';
    echo '# '.$command.PHP_EOL;
    var_dump(shell_exec($command));
    if (file_exists('/container/application/database.sql')) {
        echo '---------- DUMPED ------------';
    } else {
        echo '---------- FAILED ------------';
    }
    if(file_exists($publicDir.'/public/assets')) {
        $command = 'cd '.$publicDir.' && tar czf assets.tar.gz '.'/public/assets';
        echo '# '.$command.PHP_EOL;
        var_dump(shell_exec($command));
    }
    elseif(file_exists($publicDir.'/assets')) {
        $command = 'cd '.$publicDir.' && tar czf assets.tar.gz /assets';
        echo '# '.$command.PHP_EOL;
        var_dump(shell_exec($command));
    }

    $command = 'cd '.$publicDir.' && tar cf '.SS_DATABASE_NAME.'.sspak assets.tar.gz database.sql.gz';
    echo '# '.$command.PHP_EOL;
    var_dump(shell_exec($command));

    $command = 'rm '.$publicDir.'/database.sql.sql';
    echo '# '.$command.PHP_EOL;
    var_dump(shell_exec($command));

    $command = 'rm '.$publicDir.'/assets.tar.gz';
    echo '# '.$command.PHP_EOL;
    var_dump(shell_exec($command));
    echo '
    ';
} else {
    die('Please access from command line ...');
}
