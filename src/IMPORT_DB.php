<?php
if ((php_sapi_name() === 'cli')) {
    $location =  '/container/application/_ss_environment.php';
    
    require_once($location);
    $message   =  "Are you sure you want to do import a database [y/N]";
    print $message;
    $confirmation  =  trim( fgets( STDIN ) );
    if ( $confirmation !== 'y' ) {
       // The user did not say 'y'.
       exit (0);
    } else {   
        $command = 'mysql -u '.SS_DATABASE_USERNAME.' -p'.SS_DATABASE_PASSWORD.' -h '.SS_DATABASE_SERVER.' '.SS_DATABASE_NAME.' < /container/application/'.SS_DATABASE_NAME.'.sql';
        var_dump(shell_exec($command));
        if (file_exists('/container/application/'.SS_DATABASE_NAME.'.sql')) {
            echo '---------- DUMPED ------------';
        } else {
            echo '---------- FAILED ------------';
        }
        echo '
        ';
    }
}
