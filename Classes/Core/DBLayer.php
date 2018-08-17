<?php

namespace Bednarik\Cooluri\Core;

use Doctrine\DBAL\Driver\Statement;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of CoolUri.
 *
 * CoolUri is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CoolUri is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CoolUri. If not, see <http://www.gnu.org/licenses/>.
 */
class DBLayer
{
    /**
     * Database connections
     *
     * @var Connection
     */
    protected $connections = [];
    /**
     * Singleton instance
     *
     * @var DBLayer
     */
    protected static $_instance = null;

    protected function __construct()
    {

    }

    /**
     * Create and return a database connection
     *
     * @param string $table Table name
     *
     * @return Connection
     */
    public function getConnection(string $table)
    {
        if (empty($this->connections[$table])) {
            $this->connections[$table] = GeneralUtility::makeInstance(ConnectionPool::class)
                                                       ->getConnectionForTable($table);
        }

        return $this->connections[$table];
    }

    /**
     * Create and return a singleton instance
     *
     * @return DBLayer Singleton DB Layer instance
     */
    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Run a database query by statement
     *
     * @param string $stmt Statement
     * @param string $tp   Table prefix
     *
     * @return Statement Query result
     * @throws \Doctrine\DBAL\DBALException
     */
    public function query($stmt, $tp = 'link_')
    {
        $connection = $this->getConnection($tp.'cache');

        return $connection->query($stmt);
//        return $GLOBALS['TYPO3_DB']->sql_query($stmt);
    }

    /**
     * Fetch a row from a query result
     *
     * @param string $res Query statement
     * @param string $tp  Table prefix
     *
     * @return array Database row
     * @throws \Doctrine\DBAL\DBALException
     */
    public function queryAndFetchStatement(string $stmt, $tp = '_link')
    {
        $connection = $this->getConnection($tp.'cache');

        return $connection->fetchAssoc($stmt);
    }

    /**
     * Run an oldschool select query and return the first row
     *
     * @param $select_fields
     * @param $from_table
     * @param $where_clause
     * @param string $groupBy
     * @param string $orderBy
     * @param string $limit
     *
     * @return array Database record
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function selectAndFetch(
        $select_fields,
        $from_table,
        $where_clause,
        $groupBy = '',
        $orderBy = '',
        $limit = ''
    ) {
        $statement    = 'SELECT '.$select_fields.' FROM '.$from_table;
        $where_clause = trim($where_clause);
        if (strlen($where_clause)) {
            $statement .= ' WHERE '.$where_clause;
        }
        $groupBy = trim($groupBy);
        if (strlen($groupBy)) {
            $statement .= ' GROUP BY '.$groupBy;
        }
        $orderBy = trim($orderBy);
        if (strlen($orderBy)) {
            $statement .= ' ORDER BY '.$orderBy;
        }
        $limit = trim($limit);
        if (strlen($limit)) {
            $limit .= ' LIMIT '.$limit;
        }

        return self::getInstance()->queryAndFetchStatement($statement, $from_table);
    }

    /**
     * Fetch a row from a query result
     *
     * @param $res
     *
     * @return mixed
     * @deprecated
     */
    public function fetch($res)
    {
        try {
        	throw new \Exception;
        } catch (\Exception $e) {
        	echo $e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
        }
        return $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
    }

    public function fetch_row($res)
    {
        return $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
    }

    /**
     * Quote a string
     *
     * @param string $string String
     * @param string $table  Table name
     *
     * @return string Quoted string
     */
    public static function escape($string, $table = 'link_cache')
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);

        return $connection->quote($string);
//        return $GLOBALS['TYPO3_DB']->fullQuoteStr($string, $tp.'cache');
    }

    public function error()
    {
        try {
        	throw new \Exception;
        } catch (\Exception $e) {
        	echo $e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
        }
        return $GLOBALS['TYPO3_DB']->sql_error();
    }

    public function num_rows($res)
    {
        try {
            throw new \Exception;
        } catch (\Exception $e) {
            echo $e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
        }

        return $GLOBALS['TYPO3_DB']->sql_num_rows($res);
    }

    public function affected_rows()
    {
        return $GLOBALS['TYPO3_DB']->sql_affected_rows();
    }
}
