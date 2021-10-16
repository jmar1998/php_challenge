#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';
require 'init.php';
// require 
use ASPTest\commands\UserCreateCommand;
use ASPTest\commands\UserPasswordCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new UserCreateCommand());
$application->add(new UserPasswordCommand());
$application->run();