<?php

require_once('/container/application/_ss_environment.php');

$command = 'mysqldump -u '.SS_DATABASE_USERNAME.' -p'.SS_DATABASE_PASSWORD.' -h '.SS_DATABASE_SERVER.' '.SS_DATABASE_NAME.' > /container/application/'.SS_DATABASE_NAME.'.sql';
var_dump(shell_exec($command));
if(file_exists('/container/application/'.SS_DATABASE_NAME.'.sql')) {
    echo '---------- DUMPED ------------';
} else {
    echo '---------- FAILED ------------';
}

echo '
';