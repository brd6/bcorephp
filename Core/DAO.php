<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 29/07/2016
 * Time: 01:07
 */

namespace bcorephp;


abstract class DAO
{
    /**
     * Db instance
     * @var
     */
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // crud
    protected abstract function create();
    protected abstract function update();
    protected abstract function delete();
}