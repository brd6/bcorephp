<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 28/07/2016
 * Time: 22:53
 */

namespace bcorephp;

class Database
{
    /**
     * Unique instance of Database class
     * @var
     */
    private static $instance = null;
    /**
     * PDO Object
     * @var
     */
    private $pdo = null;

    /**
     * Database configuration
     * @var
     */
    private $config;

    private function __construct(array $config = array())
    {
        $this->config = $config;
        $this->connect();
    }

    public static function getInstance(array $config = array())
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    private function __clone()
    {

    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    private function connect()
    {
        $this->config['port'] = isset($this->config['port']) ? $this->config['port'] : 3306;
        $this->pdo = new \PDO("mysql:host=".$this->config['host'].";dbname=".$this->config['dbname'].";port=".$this->config['port'].";charset=utf8",
            $this->config['user'],
            $this->config['password'],
            array(
                \PDO::ATTR_PERSISTENT => true
            ));
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Custom PDO Query
     * @param $query
     * @param array $params
     * @param $fetchmode
     * @return mixed $ret
     */
    public function query($query, Array $params = array(), $fetchmode = \PDO::FETCH_ASSOC)
    {
        if (!$this->isConnected())
            return (-1);

        $currentQuery = $this->pdo->prepare($query);
        $currentQuery->execute($params);

        $reqContainer = strtolower(explode(" ", $query)[0]);
        if (in_array($reqContainer, array("select", "show")))
        {
            return $currentQuery->fetchAll($fetchmode);
        }
        return $currentQuery->rowCount();
    }

    /**
     * Check if the pdo is connected to the database
     * @return bool
     */
    public function isConnected()
    {
        return ($this->pdo != null);
    }

    /**
     * Disconnect PDO
     */
    public function disconnect()
    {
        $this->pdo = null;
    }

    /**
     * @return mixed
     */
    public function getPdo()
    {
        return $this->pdo;
    }

}