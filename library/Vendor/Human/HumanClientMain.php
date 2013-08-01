<?php

if (!defined('HUMAN_ROOT')) {
    define('HUMAN_ROOT', dirname(__FILE__));
    require(HUMAN_ROOT . DIRECTORY_SEPARATOR . 'util' . DIRECTORY_SEPARATOR . 'HumanAutoloader.php');
    HumanAutoloader::register();
}