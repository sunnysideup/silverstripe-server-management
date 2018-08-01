<?php
if ((php_sapi_name() === 'cli')) {
    $location =  '/container/application/_ss_environment.php';
    
    require_once($location);
    $command = 'mysql -u '.SS_DATABASE_USERNAME.' -p\''.SS_DATABASE_PASSWORD.'\' -h '.SS_DATABASE_SERVER.' '.SS_DATABASE_NAME.' < /container/application/'.SS_DATABASE_NAME.'.sql';
    $message   =  "Are you sure you want to do import a database [y/N]";
    print PHP_EOL;
    print PHP_EOL;
    print '# '.$command;
    print PHP_EOL;
    print PHP_EOL;
    print $message;
    print PHP_EOL;
    print PHP_EOL;
    $confirmation  =  trim( fgets( STDIN ) );
    if ( $confirmation !== 'y' ) {
       // The user did not say 'y'.
       exit (0);
    } else {   
        var_dump(shell_exec($command));
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
    }
}
