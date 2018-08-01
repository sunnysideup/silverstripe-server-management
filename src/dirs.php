<?php

//find the safe dir ...
$safeDir = dirname(realpath($argv[0]));
$found = false;
$x = 0;
while ($found === false && file_exists($safeDir) && $x < 99) {
    $x++;
    if (file_exists('.htaccess')) {
        //we are in public territory... NOT GOOD
    } elseif (file_exists($safeDir.'/_ss_environment.php')) {
        require_once($safeDir.'/_ss_environment.php');
        $found = true;
    } elseif (file_exists($safeDir.'/.env')) {
        die('please create an _ss_environment.php copy of your .env file. A .env reader will be included in the future.');
        $found = true;
    }
    if ($found === false) {
        $safeDir = dirname($safeDir);
    }
}
$cwd = $safeDir.'/';


//now find the public directory ...
$options = [
    'public',
    'wwww',
    'public_html'
];

$publicDir = $safeDir;

foreach ($options as $option) {
    if (file_exists($safeDir.'/'.$option)) {
        $publicDir = $safeDir.'/'.$option;
        break;
    }
}

########## ########## ##########
########## START ACTION
########## ########## ##########
echo '========================'. PHP_EOL;
echo 'SAFE DIR: '.$safeDir. PHP_EOL;
echo 'PUBLIC DIR: '.$publicDir. PHP_EOL;
echo '========================'. PHP_EOL;
