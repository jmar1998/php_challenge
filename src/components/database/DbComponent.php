<?php
namespace ASPTest\components\database;

use PDO;

class DbComponent
{
    public $configFile = 'config.php';
    /** @var PDO */
    public $PDO = null;
    public function __construct($config = [])
    {
        $this->configFile = $config['configFile'] ?? $this->configFile;
        $appConfig = include ROOT_PATH . "/{$this->configFile}";
        $this->PDO = new PDO($appConfig['db']['connectionString'], $appConfig['db']['user'], $appConfig['db']['password']);
    }
}
