<?php
error_reporting(E_ALL);
ini_set("display_errors",1);
$config = dirname(__FILE__).'/config/main.php';
require_once dirname(__FILE__).'/../WBase/framework/WB.php';
$app = WB::CreateApplication($config);
$app->RunApplication();
