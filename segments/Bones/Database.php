<?php

namespace Bones;

use JollyException\MultiplePrimaryDBFound;
use JollyException\DatabaseException;
use JollyException\BadMethodException;

class Database
{

    /**
     * Static instance of self
     *
     * @var Database
     */
    protected static $_instance;

    /**
     * Table prefix
     *
     * @var string
     */
    public static $prefix = '';

    /**
     * MySQLi instances
     *
     * @var mysqli[]
     */
    protected static $_mysqli = array();

    /**
     * The SQL query to be prepared and executed
     *
     * @var string
     */
    protected static $_query;

    /**
     * The previously executed SQL query
     *
     * @var string
     */
    protected static $_lastQuery;

    /**
     * The SQL query options required after SELECT, INSERT, UPDATE or DELETE
     *
     * @var array
     */
    protected static $_queryOptions = array();

    /**
     * An array that holds where joins
     *
     * @var array
     */
    protected static $_join = array();

    /**
     * An array that holds where conditions
     *
     * @var array
     */
    protected static $_where = array();

    /**
     * An array that holds where join ands
     *
     * @var array
     */
    protected static $_joinAnd = array();

    /**
     * An array that holds having conditions
     *
     * @var array
     */
    protected static $_having = array();

    /**
     * Dynamic type list for order by condition value
     *
     * @var array
     */
    protected static $_orderBy = array();

    /**
     * Dynamic type list for group by condition value
     *
     * @var array
     */
    protected static $_groupBy = array();

    /**
     * Dynamic type list for limit on query
     *
     * @var array
     */
    protected static $_limitAttrs = array();

    /**
     * Dynamic type list for temporary locking tables.
     *
     * @var array
     */
    protected static $_tableLocks = array();

    /**
     * Variable which holds the current table lock method.
     *
     * @var string
     */
    protected static $_tableLockMethod = "READ";

    /**
     * Dynamic array that holds a combination of where condition/table data value types and parameter references
     *
     * @var array
     */
    protected static $_bindParams = array(''); // Create the empty 0 index

    /**
     * Variable which holds an amount of returned rows during get/getOne/select queries
     *
     * @var string
     */
    public static $count = 0;

    /**
     * Variable which holds an amount of returned rows during get/getOne/select queries with withTotalCount()
     *
     * @var string
     */
    public static $totalCount = 0;

    /**
     * Variable which holds last statement error
     *
     * @var string
     */
    protected static $_stmtError;

    /**
     * Variable which holds last statement error code
     *
     * @var int
     */
    protected static $_stmtErrno;

    /**
     * Is Subquery object
     *
     * @var bool
     */
    protected static $isSubQuery = false;

    /**
     * Name of the auto increment column
     *
     * @var int
     */
    protected static $_lastInsertId = null;

    /**
     * Column names for update when using onDuplicate method
     *
     * @var array
     */
    protected static $_updateColumns = null;

    /**
     * Return type: 'array' to return results as array, 'object' as object
     * 'json' as json string
     *
     * @var string
     */
    public static $returnType = 'object';

    /**
     * Should join() results be nested by table
     *
     * @var bool
     */
    protected static $_nestJoin = false;

    /**
     * Table name (with prefix, if used)
     *
     * @var string
     */
    protected static $_tableName = '';

    /**
     * FOR UPDATE flag
     *
     * @var bool
     */
    protected static $_forUpdate = false;

    /**
     * LOCK IN SHARE MODE flag
     *
     * @var bool
     */
    protected static $_lockInShareMode = false;

    /**
     * Key field for Map()'ed result array
     *
     * @var string
     */
    protected static $_mapKey = null;

    /**
     * Variables for query execution tracing
     */
    protected static $traceStartQ;
    protected static $traceEnabled;
    protected static $traceStripPrefix;
    public static $trace = array();

    /**
     * Per page limit for pagination
     *
     * @var int
     */

    public static $pageLimit = 20;
    /**
     * Variable that holds total pages count of last paginate() query
     *
     * @var int
     */
    public static $totalPages = 0;

    /**
     * @var array connections settings [profile_name=>[same_as_contruct_args]]
     */
    protected static $connectionsSettings = array();
    /**
     * @var string the name of a default (main) mysqli connection
     */
    public static $defConnectionName = 'default';

    public static $autoReconnect = true;
    protected static $autoReconnectCount = 0;

    /**
     * @var bool Operations in transaction indicator
     */
    protected static $_transaction_in_progress = false;

    public static $__current_database = null;

    protected static $_where_group_index = 0;
    protected static $_has_where_group = false;
    protected static $_where_group_base_cond = null;

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $db
     * @param int    $port
     * @param string $charset
     * @param string $socket
     */
    public function __construct($host = null, $username = null, $password = null, $db = null, $port = null, $charset = 'utf8', $socket = null)
    {
        $isSubQuery = false;

        if (empty($host)) {
            $host = $this->primaryDB();
            if (empty($host)) {
                throw new DatabaseException('Database connection info not provided');
            }
            $this->addConnection($host['db'], $host);
        } else if (gettype($host) == 'string') {
            $host = $this->findSettings($host);
            $this->addConnection($host['db'], $host);
        }

        // if params were passed as array
        if (is_array($host)) {
            foreach ($host as $key => $val) {
                $$key = $val;
            }
        }

        $this->addConnection('default', array(
            'host' => $host,
            'username' => $username,
            'password' => $password,
            'db' => $db,
            'port' => $port,
            'socket' => $socket,
            'charset' => $charset
        ));

        if ($isSubQuery) {
            $this->isSubQuery = true;
            return;
        }

        if (isset($prefix)) {
            $this->setPrefix($prefix);
        }

        self::$_instance = $this;
    }

    /**
     * A method to connect to the database
     *
     * @param null|string $connectionName
     *
     * @throws Exception
     * @return void
     */
    public static function connect($connectionName = 'default')
    {
        if (!isset(self::$connectionsSettings[$connectionName]))
            throw new DatabaseException('Connection profile not set');

        $pro = self::$connectionsSettings[$connectionName];
        $params = array_values($pro);
        $charset = array_pop($params);

        if (self::$isSubQuery) {
            return;
        }

        if (empty($pro['host']) && empty($pro['socket'])) {
            throw new DatabaseException('MySQL host or socket is not set');
        }

        $mysqlic = new \ReflectionClass('mysqli');
        $mysqli = $mysqlic->newInstanceArgs($params);

        self::$__current_database = self::$prefix . $pro['db'];

        if ($mysqli->connect_error) {
            throw new DatabaseException('Connect Error ' . $mysqli->connect_errno . ': ' . $mysqli->connect_error, $mysqli->connect_errno);
        }

        if (!empty($charset)) {
            $mysqli->set_charset($charset);
        }
        self::$_mysqli[$connectionName] = $mysqli;
    }

    /**
     * @throws Exception
     */
    public static function disconnectAll()
    {
        foreach (array_keys(self::$_mysqli) as $k) {
            self::disconnect($k);
        }
    }

    /**
     * Set the connection name to use in the next query
     *
     * @param string $name
     *
     * @return $this
     * @throws Exception
     */
    public static function connection($name)
    {
        if (!isset(self::$connectionsSettings[$name]))
            throw new DatabaseException('Connection ' . $name . ' was not added.');

        self::$defConnectionName = $name;
        return self::$_instance;
    }

    /**
     * A method to disconnect from the database
     *
     * @params string $connection connection name to disconnect
     *
     * @param string $connection
     *
     * @return void
     */
    public static function disconnect($connection = 'default')
    {
        if (!isset(self::$_mysqli[$connection]))
            return;

        self::$_mysqli[$connection]->close();
        unset(self::$_mysqli[$connection]);
    }

    /**
     * Create & store at _mysqli new mysqli instance
     *
     * @param string $name
     * @param array  $params
     *
     * @return $this
     */
    public static function addConnection($name, array $params)
    {
        self::$connectionsSettings[$name] = array();
        foreach (array('host', 'username', 'password', 'db', 'port', 'socket', 'charset') as $k) {
            $prm = isset($params[$k]) ? $params[$k] : null;

            if ($k == 'host') {
                if (is_object($prm))
                    self::$_mysqli[$name] = $prm;

                if (!is_string($prm))
                    $prm = null;
            }
            self::$connectionsSettings[$name][$k] = $prm;
        }
        return self::$_instance;
    }

    /**
     * A method to get mysqli object or create it in case needed
     *
     * @return mysqli
     * @throws Exception
     */
    public static function mysqli()
    {
        if (!isset(self::$_mysqli[self::$defConnectionName])) {
            self::connect(self::$defConnectionName);
        }
        return self::$_mysqli[self::$defConnectionName];
    }

    /**
     * A method of returning the static instance to allow access to the
     * instantiated object from within another class.
     * Inheriting this class would require reloading connection info.
     *
     * @uses $db = Database::getInstance();
     *
     * @return Database Returns the current instance.
     */
    public static function getInstance()
    {
        return self::$_instance;
    }

    /**
     * Reset states after an execution
     *
     * @return Database Returns the current instance.
     */
    protected static function reset()
    {
        if (self::$traceEnabled) {
            self::$trace[] = array(self::$_lastQuery, (microtime(true) - self::$traceStartQ), self::_traceGetCaller());
        }

        self::$_where = array();
        self::$_having = array();
        self::$_join = array();
        self::$_joinAnd = array();
        self::$_orderBy = array();
        self::$_groupBy = array();
        self::$_bindParams = array(''); // Create the empty 0 index
        self::$_query = null;
        self::$_queryOptions = array();
        self::$returnType = 'object';
        self::$_nestJoin = false;
        self::$_forUpdate = false;
        self::$_lockInShareMode = false;
        self::$_tableName = '';
        self::$_lastInsertId = null;
        self::$_updateColumns = null;
        self::$_mapKey = null;
        if (!self::$_transaction_in_progress) {
            self::$defConnectionName = 'default';
        }
        self::$autoReconnectCount = 0;
        return self::$_instance;
    }

    /**
     * Helper function to create dbObject with JSON return type
     *
     * @return Database
     */
    public static function jsonBuilder()
    {
        self::$returnType = 'json';
        return self::$_instance;
    }

    /**
     * Helper function to create dbObject with array return type
     * Added for consistency as that's default output type
     *
     * @return Database
     */
    public static function arrayBuilder()
    {
        self::$returnType = 'array';
        return self::$_instance;
    }

    /**
     * Helper function to create dbObject with object return type.
     *
     * @return Database
     */
    public static function objectBuilder()
    {
        self::$returnType = 'object';
        return self::$_instance;
    }

    /**
     * Method to set a prefix
     *
     * @param string $prefix Contains a table prefix
     *
     * @return Database
     */
    public static function setPrefix($prefix = '')
    {
        self::$prefix = $prefix;
        return self::$_instance;
    }

    /**
     * Pushes a unprepared statement to the mysqli stack.
     * WARNING: Use with caution.
     * This method does not escape strings by default so make sure you'll never use it in production.
     *
     * @author Jonas Barascu
     *
     * @param  [[Type]] $query [[Description]]
     *
     * @return bool|mysqli_result
     * @throws Exception
     */
    private static function queryUnprepared($query)
    {
        // Execute query
        $stmt = self::mysqli()->query($query);

        // Failed?
        if ($stmt !== false)
            return $stmt;

        if (self::mysqli()->errno === 2006 && self::$autoReconnect === true && self::$autoReconnectCount === 0) {
            self::connect(self::$defConnectionName);
            self::$autoReconnectCount++;
            return self::queryUnprepared($query);
        }

        throw new DatabaseException(sprintf('Unprepared Query Failed, ERRNO: %u (%s)', self::mysqli()->errno, self::mysqli()->error), self::mysqli()->errno);
    }

    /**
     * Prefix add raw SQL query.
     *
     * @author Emre Emir <https://github.com/bejutassle>
     * @param string $query      User-provided query to execute.
     * @return string Contains the returned rows from the query.
     */
    public static function rawAddPrefix($query)
    {
        $query = str_replace(PHP_EOL, '', $query);
        $query = preg_replace('/\s+/', ' ', $query);
        preg_match_all("/(from|into|update|join) [\\'\\´]?([a-zA-Z0-9_-]+)[\\'\\´]?/i", $query, $matches);
        list($from_table, $from, $table) = $matches;
        $table[0] = !empty($table[0]) ? $table[0] : '';
        return str_replace($table[0], self::$prefix . $table[0], $query);
    }

    /**
     * Execute raw SQL query.
     *
     * @param string $query      User-provided query to execute.
     * @param array  $bindParams Variables array to bind to the SQL statement.
     *
     * @return array Contains the returned rows from the query.
     * @throws Exception
     */
    public static function __rawQuery($query, $bindParams = null)
    {
        $query = self::rawAddPrefix($query);
        $params = array(''); // Create the empty 0 index
        self::$_query = $query;
        $stmt = self::_prepareQuery();

        if (is_array($bindParams) === true) {
            foreach ($bindParams as $prop => $val) {
                $params[0] .= self::_determineType($val);
                array_push($params, $bindParams[$prop]);
            }

            call_user_func_array(array($stmt, 'bind_param'), self::refValues($params));
        }

        $stmt->execute();
        self::$count = $stmt->affected_rows;
        self::$_stmtError = $stmt->error;
        self::$_stmtErrno = $stmt->errno;
        self::$_lastQuery = self::replacePlaceHolders(self::$_query, $params);
        $res = self::_dynamicBindResults($stmt);
        self::reset();

        return $res;
    }

    /**
     * Helper function to execute raw SQL query and return only 1 row of results.
     * Note that function do not add 'limit 1' to the query by itself
     * Same idea as __getOne()
     *
     * @param string $query      User-provided query to execute.
     * @param array  $bindParams Variables array to bind to the SQL statement.
     *
     * @return array|null Contains the returned row from the query.
     * @throws Exception
     */
    public static function __rawQueryOne($query, $bindParams = null)
    {
        $res = self::__rawQuery($query, $bindParams);
        if (is_array($res) && isset($res[0])) {
            return $res[0];
        }

        return null;
    }

    /**
     * Helper function to execute raw SQL query and return only 1 column of results.
     * If 'limit 1' will be found, then string will be returned instead of array
     * Same idea as getValue()
     *
     * @param string $query      User-provided query to execute.
     * @param array  $bindParams Variables array to bind to the SQL statement.
     *
     * @return Contains the returned rows from the query.
     * @throws Exception
     */
    public static function __rawQueryValue($query, $bindParams = null)
    {
        $res = self::__rawQuery($query, $bindParams);
        if (!$res) {
            return null;
        }

        $limit = preg_match('/limit\s+1;?$/i', $query);
        $key = key($res[0]);
        if (isset($res[0][$key]) && $limit == true) {
            return $res[0][$key];
        }

        $newRes = array();
        for ($i = 0; $i < self::$count; $i++) {
            $newRes[] = $res[$i][$key];
        }
        return $newRes;
    }

    /**
     * A method to perform select query
     *
     * @param string    $query   Contains a user-provided select query.
     * @param int|array $numRows Array to define SQL limit in format Array ($offset, $count)
     *
     * @return array Contains the returned rows from the query.
     * @throws Exception
     */
    public static function query($query, $numRows = null)
    {
        self::$_query = $query;
        $stmt = self::_buildQuery($numRows);
        $stmt->execute();
        self::$_stmtError = $stmt->error;
        self::$_stmtErrno = $stmt->errno;
        $res = self::_dynamicBindResults($stmt);
        self::reset();

        return $res;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) options for SQL queries.
     *
     * @uses $Database->setQueryOption('name');
     *
     * @param string|array $options The options name of the query.
     *
     * @throws Exception
     * @return Database
     */
    public static function setQueryOption($options)
    {
        $allowedOptions = array(
            'ALL', 'DISTINCT', 'DISTINCTROW', 'HIGH_PRIORITY', 'STRAIGHT_JOIN', 'SQL_SMALL_RESULT',
            'SQL_BIG_RESULT', 'SQL_BUFFER_RESULT', 'SQL_CACHE', 'SQL_NO_CACHE', 'SQL_CALC_FOUND_ROWS',
            'LOW_PRIORITY', 'IGNORE', 'QUICK', 'MYSQLI_NESTJOIN', 'FOR UPDATE', 'LOCK IN SHARE MODE'
        );

        if (!is_array($options)) {
            $options = array($options);
        }

        foreach ($options as $option) {
            $option = strtoupper($option);
            if (!in_array($option, $allowedOptions)) {
                throw new DatabaseException('Wrong query option: ' . $option);
            }

            if ($option == 'MYSQLI_NESTJOIN') {
                self::$_nestJoin = true;
            } elseif ($option == 'FOR UPDATE') {
                self::$_forUpdate = true;
            } elseif ($option == 'LOCK IN SHARE MODE') {
                self::$_lockInShareMode = true;
            } else {
                self::$_queryOptions[] = $option;
            }
        }

        return self::$_instance;
    }

    /**
     * Function to enable SQL_CALC_FOUND_ROWS in the get queries
     *
     * @return Database
     * @throws Exception
     */
    public static function withTotalCount()
    {
        self::setQueryOption('SQL_CALC_FOUND_ROWS');
        return self::$_instance;
    }

    /**
     * A convenient SELECT * function.
     *
     * @param int|array $numRows     Array to define SQL limit in format Array ($offset, $count)
     *                               or only $count
     * @param string    $columns     Desired columns
     * @param string    $tableName   The name of the database table to work with.
     *
     * @return array|Database Contains the returned rows from the select query.
     * @throws Exception
     */
    public static function __getRows($numRows = null, $columns = '*', $tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;

        if (empty($columns)) {
            $columns = '*';
        }

        $column = is_array($columns) ? implode(', ', $columns) : $columns;

        if (strpos($tableName, '.') === false) {
            self::$_tableName = self::$prefix . $tableName;
        } else {
            self::$_tableName = $tableName;
        }

        self::$_query = 'SELECT ' . implode(' ', self::$_queryOptions) . ' ' .
            $column . " FROM " . self::$_tableName;
        $stmt = self::_buildQuery($numRows);

        if (self::$isSubQuery) {
            return self::$_instance;
        }

        $stmt->execute();
        self::$_stmtError = $stmt->error;
        self::$_stmtErrno = $stmt->errno;
        $res = self::_dynamicBindResults($stmt);
        self::reset();

        return $res;
    }

    /**
     * A convenient SELECT * function to get one record.
     *
     * @param string $columns   Desired columns
     * @param string $tableName The name of the database table to work with.
     *
     * @return Contains the returned rows from the select query.
     * @throws Exception
     */
    public static function __getOne($columns = '*', $tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;

        $res = self::__getRows(1, $columns, $tableName);

        if ($res instanceof Database) {
            return $res;
        } elseif (is_array($res) && isset($res[0])) {
            return $res[0];
        } elseif ($res) {
            return $res;
        }

        return null;
    }

    /**
     * A convenient SELECT COLUMN function to get a single column value from one row
     *
     * @param string $column    The desired column
     * @param int    $limit     Limit of rows to select. Use null for unlimited..1 by default
     * @param string $tableName The name of the database table to work with.
     *
     * @return Contains the value of a returned column / array of values
     * @throws Exception
     */
    public static function getValue($column, $limit = 1, $tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;
        $res = self::ObjectBuilder()->__getRows($limit, "{$column} AS retval", $tableName);

        if (!$res) {
            return null;
        }

        if ($limit == 1) {
            if (isset($res[0]->retval)) {
                return $res[0]->retval;
            }
            return null;
        }

        $newRes = new \stdClass();
        for ($i = 0; $i < self::$count; $i++) {
            $newRes->$i = $res[$i]->retval;
        }
        return $newRes;
    }

    /**
     * A convenient SELECT COLUMNS function to get a single/multiple column values from row
     *
     * @param array $columns    The desired column
     * @param int    $limit     Limit of rows to select. Use null for unlimited..1 by default
     * @param string $tableName The name of the database table to work with.
     *
     * @return Contains the value of a returned column / array of values
     * @throws Exception
     */
    public static function __pluck($columns = ['*'], $tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;

        $res = self::__getRows(null, $columns, $tableName);

        if ($res instanceof Database) {
            return $res;
        } elseif (is_array($res) && isset($res[0])) {
            return $res;
        } elseif ($res) {
            return $res;
        }

        return null;
    }

    /**
     * Insert method to add new row
     *
     * @param array  $insertData Data containing information for inserting into the DB.
     * @param string $tableName  The name of the table.
     *
     * @return bool Boolean indicating whether the insert query was completed successfully.
     * @throws Exception
     */
    public static function __insert($insertData, $tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;
        return self::_buildInsert($insertData, 'INSERT', $tableName);
    }

    /**
     * Insert method to add several rows at once
     *
     * @param array  $multiInsertData Two-dimensional Data-array containing information for inserting into the DB.
     * @param array  $dataKeys        Optional Table Key names, if not set in insertDataSet.
     * @param string $tableName       The name of the table.
     *
     * @return bool|array Boolean indicating the insertion failed (false), else return id-array ([int])
     * @throws Exception
     */
    public static function __insertMulti(array $multiInsertData, array $dataKeys = null, $tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;

        if (count($multiInsertData) == count($multiInsertData, COUNT_RECURSIVE)) throw new DatabaseException('__insertMulti() must have type of multi-dimensional array for #1 parameter');

        // only auto-commit our inserts, if no transaction is currently running
        $autoCommit = (isset(self::$_transaction_in_progress) ? !self::$_transaction_in_progress : true);
        $ids = array();

        if ($autoCommit) {
            self::startTransaction();
        }

        foreach ($multiInsertData as $insertData) {
            if ($dataKeys !== null) {
                // apply column-names if given, else assume they're already given in the data
                $insertData = array_combine($dataKeys, $insertData);
            }

            $id = self::__insert($insertData, $tableName);
            if (!$id) {
                if ($autoCommit) {
                    self::rollback();
                }
                return false;
            }
            $ids[] = $id;
        }

        if ($autoCommit) {
            self::commit();
        }

        return $ids;
    }

    /**
     * Replace method to add new row
     *
     * @param array  $insertData Data containing information for inserting into the DB.
     * @param string $tableName  The name of the table.
     *
     * @return bool Boolean indicating whether the insert query was completed successfully.
     * @throws Exception
     */
    public static function replace($insertData, $tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;
        return self::_buildInsert($insertData, 'REPLACE', $tableName);
    }

    /**
     * A convenient function that returns TRUE if exists at least an element that
     * satisfy the where condition specified calling the "where" method before this one.
     *
     * @param string $tableName The name of the database table to work with.
     *
     * @return bool
     * @throws Exception
     */
    public static function has($tableName = '')
    {
        self::__getOne('1', $tableName);
        return self::$count >= 1;
    }

    /** 
     * Update query. Be sure to first call the "where" method.
     *
     * @param string $tableName The name of the database table to work with.
     * @param array  $tableData Array of data to update the desired row.
     * @param int    $numRows   Limit on the number of rows that can be updated.
     * @param string $tableName The name of the database table to work with.
     *
     * @return bool
     * @throws Exception
     */
    public static function __update($tableData, $numRows = null, $tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;

        if (self::$isSubQuery) {
            return;
        }

        self::$_query = "UPDATE " . self::$prefix . $tableName;

        $stmt = self::_buildQuery($numRows, $tableData);
        $status = $stmt->execute();
        self::reset();
        self::$_stmtError = $stmt->error;
        self::$_stmtErrno = $stmt->errno;
        self::$count = $stmt->affected_rows;

        return $status;
    }

    /**
     * Delete query. Call the "where" method first.
     *
     * @param int|array $numRows     Array to define SQL limit in format Array ($offset, $count)
     *                               or only $count
     * @param string    $tableName   The name of the database table to work with.
     *
     * @return bool Indicates success. 0 or 1.
     * @throws Exception
     */
    public static function __delete($numRows = null, $tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;

        if (self::$isSubQuery) {
            return;
        }

        $table = self::$prefix . $tableName;

        if (count(self::$_join)) {
            self::$_query = "DELETE " . preg_replace('/.* (.*)/', '$1', $table) . " FROM " . $table;
        } else {
            self::$_query = "DELETE FROM " . $table;
        }

        $stmt = self::_buildQuery($numRows);
        $stmt->execute();
        self::$_stmtError = $stmt->error;
        self::$_stmtErrno = $stmt->errno;
        self::$count = $stmt->affected_rows;
        self::reset();

        return ($stmt->affected_rows > -1);    //	-1 indicates that the query returned an error
    }

    /**
     * This method allows you to specify multiple (method chaining optional) AND WHERE statements for SQL queries.
     *
     * @uses $Database->where('id', 7)->where('title', 'MyTitle');
     *
     * @param string $whereProp  The name of the database field.
     * @param $whereValue The value of the database field.
     * @param string $operator   Comparison operator. Default is =
     * @param string $cond       Condition of where statement (OR, AND)
     *
     * @return Database
     */
    public static function __where($whereProp, $whereValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        if (count(self::$_where) == 0) {
            $cond = '';
        }

        if (is_callable($whereProp)) {
            self::$_where_group_base_cond = $cond;
            self::$_has_where_group = true;
            self::$_where_group_index++;
            self::$_instance = call_user_func_array($whereProp, [self::$_instance]);
            self::$_has_where_group = false;
            self::$_where_group_base_cond = null;
            return self::$_instance;
        }

        if (self::$_where_group_base_cond != null) {
            $cond = self::$_where_group_base_cond;
        }

        self::$_where[] = array($cond, $whereProp, $operator, $whereValue, self::$_where_group_index, self::$_has_where_group);
        self::$_where_group_base_cond = null;

        return self::$_instance;
    }

    public function __whereNull($whereProp, $cond = 'AND')
    {
        return $this->__where($whereProp, NULL, 'IS', $cond);
    }

    public function __orWhereNull($whereProp)
    {
        return $this->__where($whereProp, NULL, 'IS', 'OR');
    }

    public function __whereNotNull($whereProp, $cond = 'AND')
    {
        return $this->__where($whereProp, NULL, 'IS NOT', $cond);
    }

    public function __orWhereNotNull($whereProp)
    {
        return $this->__where($whereProp, NULL, 'IS NOT', 'OR');
    }

    public function __whereLike($whereProp, $whereValue = 'DBNULL', $cond = 'AND')
    {
        return $this->__where($whereProp, $whereValue, 'LIKE', $cond);
    }

    public function __orWhereLike($whereProp, $whereValue = 'DBNULL')
    {
        return $this->__where($whereProp, $whereValue, 'LIKE', 'OR');
    }

    public function __whereIn($whereProp, $rangeSet = [], $cond = 'AND')
    {
        return $this->__where($whereProp, $rangeSet, 'IN', $cond);
    }

    public function __orWhereIn($whereProp, $rangeSet = [])
    {
        return $this->__where($whereProp, $rangeSet, 'IN', 'OR');
    }

    public function __whereNotIn($whereProp, $rangeSet = [], $cond = 'AND')
    {
        return $this->__where($whereProp, $rangeSet, 'NOT IN', $cond);
    }

    public function __orWhereNotIn($whereProp, $rangeSet = [])
    {
        return $this->__where($whereProp, $rangeSet, 'NOT IN', 'OR');
    }

    public function __whereBetween($whereProp, $rangeSet = [], $cond = 'AND')
    {
        return $this->__where($whereProp, $rangeSet, 'BETWEEN', $cond);
    }

    public function __orWhereBetween($whereProp, $rangeSet = [])
    {
        return $this->__where($whereProp, $rangeSet, 'BETWEEN', 'OR');
    }

    public function __whereNotBetween($whereProp, $rangeSet = [], $cond = 'AND')
    {
        return $this->__where($whereProp, $rangeSet, 'NOT BETWEEN', $cond);
    }

    public function __orWhereNotBetween($whereProp, $rangeSet = [])
    {
        return $this->__where($whereProp, $rangeSet, 'NOT BETWEEN', 'OR');
    }

    public function __rightJoin($joinTable, $joinCondition)
    {
        return $this->__joinWhere($joinTable, $joinCondition, 'RIGHT');
    }

    public function __outerJoin($joinTable, $joinCondition)
    {
        return $this->__joinWhere($joinTable, $joinCondition, 'OUTER');
    }

    public function __innerJoin($joinTable, $joinCondition)
    {
        return $this->__joinWhere($joinTable, $joinCondition, 'INNER');
    }

    public function __leftOuterJoin($joinTable, $joinCondition)
    {
        return $this->__joinWhere($joinTable, $joinCondition, 'LEFT OUTER');
    }

    public function __rightOuterJoin($joinTable, $joinCondition)
    {
        return $this->__joinWhere($joinTable, $joinCondition, 'RIGHT OUTER');
    }

    public function __naturalJoin($joinTable, $joinCondition)
    {
        return $this->__joinWhere($joinTable, $joinCondition, 'NATURAL');
    }

    /**
     * Reset where conditions on query
     * 
     * @return Database
     */
    public static function __clearWhere()
    {
        self::$_where = array();
        return self::$_instance;
    }

    /**
     * This function store update column's name and column name of the
     * autoincrement column
     *
     * @param array  $updateColumns Variable with values
     * @param string $lastInsertId  Variable value
     *
     * @return Database
     */
    public static function onDuplicate($updateColumns, $lastInsertId = null)
    {
        self::$_lastInsertId = $lastInsertId;
        self::$_updateColumns = $updateColumns;
        return self::$_instance;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) OR WHERE statements for SQL queries.
     *
     * @uses $Database->__orWhere('id', 7)->__orWhere('title', 'MyTitle');
     *
     * @param string $whereProp  The name of the database field.
     * @param $whereValue The value of the database field.
     * @param string $operator   Comparison operator. Default is =
     *
     * @return Database
     */
    public static function __orWhere($whereProp, $whereValue = 'DBNULL', $operator = '=')
    {
        return self::__where($whereProp, $whereValue, $operator, 'OR');
    }

    /**
     * This method allows you to specify multiple (method chaining optional) AND HAVING statements for SQL queries.
     *
     * @uses $Database->__having('SUM(tags) > 10')
     *
     * @param string $havingProp  The name of the database field.
     * @param $havingValue The value of the database field.
     * @param string $operator    Comparison operator. Default is =
     *
     * @param string $cond
     *
     * @return Database
     */

    public static function __having($havingProp, $havingValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        // forkaround for an old operation api
        if (is_array($havingValue) && ($key = key($havingValue)) != "0") {
            $operator = $key;
            $havingValue = $havingValue[$key];
        }

        if (count(self::$_having) == 0) {
            $cond = '';
        }

        self::$_having[] = array($cond, $havingProp, $operator, $havingValue);
        return self::$_instance;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) OR HAVING statements for SQL queries.
     *
     * @uses $Database->__orHaving('SUM(tags) > 10')
     *
     * @param string $havingProp  The name of the database field.
     * @param $havingValue The value of the database field.
     * @param string $operator    Comparison operator. Default is =
     *
     * @return Database
     */
    public static function __orHaving($havingProp, $havingValue = null, $operator = null)
    {
        return self::__having($havingProp, $havingValue, $operator, 'OR');
    }

    /**
     * This method allows you to concatenate joins for the final SQL statement.
     *
     * @uses $Database->__join('table1', 'field1 <> field2', 'LEFT')
     *
     * @param string $joinTable     The name of the table.
     * @param string $joinCondition the condition.
     * @param string $joinType      'LEFT', 'INNER' etc.
     *
     * @throws Exception
     * @return Database
     */
    public static function __join($joinTable, $joinCondition, $joinType = '')
    {
        $allowedTypes = array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER', 'NATURAL');
        $joinType = strtoupper(trim($joinType));

        if ($joinType && !in_array($joinType, $allowedTypes)) {
            throw new DatabaseException('Wrong JOIN type: ' . $joinType);
        }

        if (!is_object($joinTable)) {
            $joinTable = self::$prefix . $joinTable;
        }

        self::$_join[] = array($joinType, $joinTable, $joinCondition);

        return self::$_instance;
    }


    /**
     * This is a basic method which allows you to import raw .CSV data into a table
     * Please check out http://dev.mysql.com/doc/refman/5.7/en/load-data.html for a valid .csv file.
     *
     * @author Jonas Barascu (Noneatme)
     *
     * @param string $importTable    The database table where the data will be imported into.
     * @param string $importFile     The file to be imported. Please use double backslashes \\ and make sure you
     * @param string $importSettings An Array defining the import settings as described in the README.md
     *
     * @return boolean
     * @throws Exception
     */
    public static function loadData($importTable, $importFile, $importSettings = null)
    {
        // We have to check if the file exists
        if (!file_exists($importFile)) {
            // Throw an exception
            throw new DatabaseException("importCSV -> importFile " . $importFile . " does not exists!");
        }

        // Define the default values
        // We will merge it later
        $settings = array("fieldChar" => ';', "lineChar" => PHP_EOL, "linesToIgnore" => 1);

        // Check the import settings
        if (gettype($importSettings) == "array") {
            // Merge the default array with the custom one
            $settings = array_merge($settings, $importSettings);
        }

        // Add the prefix to the import table
        $table = self::$prefix . $importTable;

        // Add 1 more slash to every slash so maria will interpret it as a path
        $importFile = str_replace("\\", "\\\\", $importFile);

        // Switch between LOAD DATA and LOAD DATA LOCAL
        $loadDataLocal = isset($settings["loadDataLocal"]) ? 'LOCAL' : '';

        // Build SQL Syntax
        $sqlSyntax = sprintf(
            'LOAD DATA %s INFILE \'%s\' INTO TABLE %s',
            $loadDataLocal,
            $importFile,
            $table
        );

        // FIELDS
        $sqlSyntax .= sprintf(' FIELDS TERMINATED BY \'%s\'', $settings["fieldChar"]);
        if (isset($settings["fieldEnclosure"])) {
            $sqlSyntax .= sprintf(' ENCLOSED BY \'%s\'', $settings["fieldEnclosure"]);
        }

        // LINES
        $sqlSyntax .= sprintf(' LINES TERMINATED BY \'%s\'', $settings["lineChar"]);
        if (isset($settings["lineStarting"])) {
            $sqlSyntax .= sprintf(' STARTING BY \'%s\'', $settings["lineStarting"]);
        }

        // IGNORE LINES
        $sqlSyntax .= sprintf(' IGNORE %d LINES', $settings["linesToIgnore"]);

        // Execute the query unprepared because LOAD DATA only works with unprepared statements.
        $result = self::queryUnprepared($sqlSyntax);

        // Are there rows modified?
        // Let the user know if the import failed / succeeded
        return (bool) $result;
    }

    /**
     * This method is useful for importing XML files into a specific table.
     * Check out the LOAD XML syntax for your MySQL server.
     *
     * @author Jonas Barascu
     *
     * @param  string $importTable    The table in which the data will be imported to.
     * @param  string $importFile     The file which contains the .XML data.
     * @param  string $importSettings An Array defining the import settings as described in the README.md
     *
     * @return boolean Returns true if the import succeeded, false if it failed.
     * @throws Exception
     */
    public static function loadXml($importTable, $importFile, $importSettings = null)
    {
        // We have to check if the file exists
        if (!file_exists($importFile)) {
            // Does not exists
            throw new DatabaseException("loadXml: Import file does not exists");
            return;
        }

        // Create default values
        $settings             = array("linesToIgnore" => 0);

        // Check the import settings
        if (gettype($importSettings) == "array") {
            $settings = array_merge($settings, $importSettings);
        }

        // Add the prefix to the import table
        $table = self::$prefix . $importTable;

        // Add 1 more slash to every slash so maria will interpret it as a path
        $importFile = str_replace("\\", "\\\\", $importFile);

        // Build SQL Syntax
        $sqlSyntax = sprintf(
            'LOAD XML INFILE \'%s\' INTO TABLE %s',
            $importFile,
            $table
        );

        // FIELDS
        if (isset($settings["rowTag"])) {
            $sqlSyntax .= sprintf(' ROWS IDENTIFIED BY \'%s\'', $settings["rowTag"]);
        }

        // IGNORE LINES
        $sqlSyntax .= sprintf(' IGNORE %d LINES', $settings["linesToIgnore"]);

        // Exceute the query unprepared because LOAD XML only works with unprepared statements.
        $result = self::queryUnprepared($sqlSyntax);

        // Are there rows modified?
        // Let the user know if the import failed / succeeded
        return (bool) $result;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) ORDER BY statements for SQL queries.
     *
     * @uses $Database->orderBy('id', 'desc')->orderBy('name', 'desc', '^[a-z]')->orderBy('name', 'desc');
     *
     * @param string $orderByField         The name of the database field.
     * @param string $orderbyDirection
     * @param $customFieldsOrRegExp Array with fieldset for ORDER BY FIELD() ordering or string with regular expression for ORDER BY REGEXP ordering
     *
     * @return Database
     * @throws Exception
     */
    public static function __orderBy($orderByField, $orderbyDirection = "DESC", $customFieldsOrRegExp = null)
    {
        $allowedDirection = array("ASC", "DESC");
        $orderbyDirection = strtoupper(trim($orderbyDirection));
        $orderByField = preg_replace("/[^ -a-z0-9\.\(\),_`\*\'\"]+/i", '', $orderByField);

        // Add table prefix to orderByField if needed.
        //FIXME: We are adding prefix only if table is enclosed into `` to distinguish aliases
        // from table names
        $orderByField = preg_replace('/(\`)([`a-zA-Z0-9_]*\.)/', '\1' . self::$prefix . '\2', $orderByField);


        if (empty($orderbyDirection) || !in_array($orderbyDirection, $allowedDirection)) {
            throw new DatabaseException('Wrong order direction: ' . $orderbyDirection);
        }

        if (is_array($customFieldsOrRegExp)) {
            foreach ($customFieldsOrRegExp as $key => $value) {
                $customFieldsOrRegExp[$key] = preg_replace("/[^\x80-\xff-a-z0-9\.\(\),_` ]+/i", '', $value);
            }
            $orderByField = 'FIELD (' . $orderByField . ', "' . implode('","', $customFieldsOrRegExp) . '")';
        } elseif (is_string($customFieldsOrRegExp)) {
            $orderByField = $orderByField . " REGEXP '" . $customFieldsOrRegExp . "'";
        } elseif ($customFieldsOrRegExp !== null) {
            throw new DatabaseException('Wrong custom field or Regular Expression: ' . $customFieldsOrRegExp);
        }

        self::$_orderBy[$orderByField] = $orderbyDirection;
        return self::$_instance;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) GROUP BY statements for SQL queries.
     *
     * @uses $Database->__groupBy('name');
     *
     * @param string $groupByField The name of the database field.
     *
     * @return Database
     */
    public static function __groupBy($groupByField)
    {
        $groupByField = preg_replace("/[^-a-z0-9\.\(\),_\* <>=!]+/i", '', $groupByField);

        self::$_groupBy[] = $groupByField;
        return self::$_instance;
    }


    /**
     * This method sets the current table lock method.
     *
     * @author Jonas Barascu
     *
     * @param  string $method The table lock method. Can be READ or WRITE.
     *
     * @throws Exception
     * @return Database
     */
    public static function setLockMethod($method)
    {
        // Switch the uppercase string
        switch (strtoupper($method)) {
                // Is it READ or WRITE?
            case "READ" || "WRITE":
                // Succeed
                self::$_tableLockMethod = $method;
                break;
            default:
                // Else throw an exception
                throw new DatabaseException("Bad lock type: Can be either READ or WRITE");
                break;
        }
        return self::$_instance;
    }

    /**
     * Locks a table for R/W action.
     *
     * @author Jonas Barascu
     *
     * @param string|array $table The table to be locked. Can be a table or a view.
     *
     * @return bool if succeeded;
     * @throws Exception
     */
    public static function lock($table)
    {
        // Main Query
        self::$_query = "LOCK TABLES";

        // Is the table an array?
        if (gettype($table) == "array") {
            // Loop trough it and attach it to the query
            foreach ($table as $key => $value) {
                if (gettype($value) == "string") {
                    if ($key > 0) {
                        self::$_query .= ",";
                    }
                    self::$_query .= " " . self::$prefix . $value . " " . self::$_tableLockMethod;
                }
            }
        } else {
            // Build the table prefix
            $table = self::$prefix . $table;

            // Build the query
            self::$_query = "LOCK TABLES " . $table . " " . self::$_tableLockMethod;
        }

        // Execute the query unprepared because LOCK only works with unprepared statements.
        $result = self::queryUnprepared(self::$_query);
        $errno  = self::mysqli()->errno;

        // Reset the query
        self::reset();

        // Are there rows modified?
        if ($result) {
            // Return true
            // We can't return ourself because if one table gets locked, all other ones get unlocked!
            return true;
        }
        // Something went wrong
        else {
            throw new DatabaseException("Locking of table " . $table . " failed", $errno);
        }

        // Return the success value
        return false;
    }

    /**
     * Unlocks all tables in a database.
     * Also commits transactions.
     *
     * @author Jonas Barascu
     * @return Database
     * @throws Exception
     */
    public static function unlock()
    {
        // Build the query
        self::$_query = "UNLOCK TABLES";

        // Execute the query unprepared because UNLOCK and LOCK only works with unprepared statements.
        $result = self::queryUnprepared(self::$_query);
        $errno  = self::mysqli()->errno;

        // Reset the query
        self::reset();

        // Are there rows modified?
        if ($result) {
            // return self
            return self::$_instance;
        }
        // Something went wrong
        else {
            throw new DatabaseException("Unlocking of tables failed", $errno);
        }


        // Return self
        return self::$_instance;
    }


    /**
     * This methods returns the ID of the last inserted item
     *
     * @return int The last inserted item ID.
     * @throws Exception
     */
    public static function getInsertId()
    {
        return self::mysqli()->insert_id;
    }

    /**
     * Escape harmful characters which might affect a query.
     *
     * @param string $str The string to escape.
     *
     * @return string The escaped string.
     * @throws Exception
     */
    public static function escape($str)
    {
        return self::mysqli()->real_escape_string($str);
    }

    /**
     * Method to call mysqli->ping() to keep unused connections open on
     * long-running scripts, or to reconnect timed out connections (if php.ini has
     * global mysqli.reconnect set to true). Can't do this directly using object
     * since _mysqli is protected.
     *
     * @return bool True if connection is up
     * @throws Exception
     */
    public static function ping()
    {
        return self::mysqli()->ping();
    }

    /**
     * This method is needed for prepared statements. They require
     * the data type of the field to be bound with "i" s", etc.
     * This function takes the input, determines what type it is,
     * and then updates the param_type.
     *
     * @param $item Input to determine the type.
     *
     * @return string The joined parameter types.
     */
    protected static function _determineType($item)
    {
        switch (gettype($item)) {
            case 'NULL':
            case 'string':
                return 's';
                break;

            case 'boolean':
            case 'integer':
                return 'i';
                break;

            case 'blob':
                return 'b';
                break;

            case 'double':
                return 'd';
                break;
        }
        return '';
    }

    /**
     * Helper function to add variables into bind parameters array
     *
     * @param string Variable value
     */
    protected static function _bindParam($value)
    {
        self::$_bindParams[0] .= self::_determineType($value);
        array_push(self::$_bindParams, $value);
    }

    /**
     * Helper function to add variables into bind parameters array in bulk
     *
     * @param array $values Variable with values
     */
    protected static function _bindParams($values)
    {
        foreach ($values as $value) {
            self::_bindParam($value);
        }
    }

    /**
     * Helper function to add variables into bind parameters array and will return
     * its SQL part of the query according to operator in ' $operator ?' or
     * ' $operator ($subquery) ' formats
     *
     * @param string $operator
     * @param $value Variable with values
     *
     * @return string
     */
    protected static function _buildPair($operator, $value)
    {

        if ($value instanceof Request) {
            self::_bindParam(-1);
            return ' = ? ';
        }

        if (!is_object($value)) {
            self::_bindParam($value);
            return ' ' . $operator . ' ? ';
        }

        $subQuery = $value->getSubQuery();
        self::_bindParams($subQuery['params']);

        return " " . $operator . " (" . $subQuery['query'] . ") " . $subQuery['alias'];
    }

    /**
     * Internal function to build and execute INSERT/REPLACE calls
     *
     * @param array  $insertData Data containing information for inserting into the DB.
     * @param string $operation  Type of operation (INSERT, REPLACE)
     * @param string $tableName  The name of the table.
     *
     * @return bool Boolean indicating whether the insert query was completed successfully.
     * @throws Exception
     */
    private static function _buildInsert($insertData, $operation, $tableName = '')
    {
        if (empty($table)) $table = self::$_tableName;

        if (self::$isSubQuery) {
            return;
        }

        self::$_query = $operation . " " . implode(' ', self::$_queryOptions) . " INTO " . self::$prefix . $tableName;
        $stmt = self::_buildQuery(null, $insertData);
        $status = $stmt->execute();
        self::$_stmtError = $stmt->error;
        self::$_stmtErrno = $stmt->errno;
        $haveOnDuplicate = !empty(self::$_updateColumns);
        self::reset();
        self::$count = $stmt->affected_rows;

        if ($stmt->affected_rows < 1) {
            // in case of onDuplicate() usage, if no rows were inserted
            if ($status && $haveOnDuplicate) {
                return true;
            }
            return false;
        }

        if ($stmt->insert_id > 0) {
            return $stmt->insert_id;
        }

        return true;
    }

    /**
     * Abstraction method that will compile the WHERE statement,
     * any passed update data, and the desired rows.
     * It then builds the SQL query.
     *
     * @param int|array $numRows     Array to define SQL limit in format Array ($offset, $count)
     *                               or only $count
     * @param array     $tableData   Should contain an array of data for updating the database.
     *
     * @return mysqli_stmt|bool Returns the $stmt object.
     * @throws Exception
     */
    protected static function _buildQuery($numRows = null, $tableData = null)
    {
        // $this->_buildJoinOld();
        self::_buildJoin();
        self::_buildInsertQuery($tableData);
        self::_buildCondition('WHERE', self::$_where);
        self::_buildGroupBy();
        self::_buildCondition('HAVING', self::$_having);
        self::_buildOrderBy();
        self::_buildLimit($numRows);
        self::_buildOnDuplicate($tableData);

        if (self::$_forUpdate) {
            self::$_query .= ' FOR UPDATE';
        }
        if (self::$_lockInShareMode) {
            self::$_query .= ' LOCK IN SHARE MODE';
        }

        self::$_lastQuery = self::replacePlaceHolders(self::$_query, self::$_bindParams);

        if (self::$isSubQuery) {
            return;
        }

        // Prepare query
        $stmt = self::_prepareQuery();

        // Bind parameters to statement if any
        if (count(self::$_bindParams) > 1) {
            call_user_func_array(array($stmt, 'bind_param'), self::refValues(self::$_bindParams));
        }

        return $stmt;
    }

    /**
     * This helper method takes care of prepared statements' "bind_result method
     * , when the number of variables to pass is unknown.
     *
     * @param mysqli_stmt $stmt Equal to the prepared statement object.
     *
     * @return array|string The results of the SQL fetch.
     * @throws Exception
     */
    protected static function _dynamicBindResults(\mysqli_stmt $stmt)
    {
        $parameters = array();
        $results = array();
        /**
         * @see http://php.net/manual/en/mysqli-result.fetch-fields.php
         */
        $mysqlLongType = 252;
        $shouldStoreResult = false;

        $meta = $stmt->result_metadata();

        // if $meta is false yet sqlstate is true, there's no sql error but the query is
        // most likely an update/insert/delete which doesn't produce any results
        if (!$meta && $stmt->sqlstate)
            return array();

        $row = array();
        while ($field = $meta->fetch_field()) {
            if ($field->type == $mysqlLongType) {
                $shouldStoreResult = true;
            }

            if (self::$_nestJoin && $field->table != self::$_tableName) {
                $field->table = substr($field->table, strlen(self::$prefix));
                $row[$field->table][$field->name] = null;
                $parameters[] = &$row[$field->table][$field->name];
            } else {
                $row[$field->name] = null;
                $parameters[] = &$row[$field->name];
            }
        }

        // avoid out of memory bug in php 5.2 and 5.3. Mysqli allocates lot of memory for long*
        // and blob* types. So to avoid out of memory issues store_result is used
        // https://github.com/joshcam/PHP-MySQLi-Database-Class/pull/119
        if ($shouldStoreResult) {
            $stmt->store_result();
        }

        call_user_func_array(array($stmt, 'bind_result'), $parameters);

        self::$totalCount = 0;
        self::$count = 0;

        while ($stmt->fetch()) {
            if (self::$returnType == 'object') {
                $result = new \stdClass();
                foreach ($row as $key => $val) {
                    if (is_array($val)) {
                        $result->$key = new \stdClass();
                        foreach ($val as $k => $v) {
                            $result->$key->$k = $v;
                        }
                    } else {
                        $result->$key = $val;
                    }
                }
            } else {
                $result = array();
                foreach ($row as $key => $val) {
                    if (is_array($val)) {
                        foreach ($val as $k => $v) {
                            $result[$key][$k] = $v;
                        }
                    } else {
                        $result[$key] = $val;
                    }
                }
            }
            self::$count++;
            if (self::$_mapKey) {
                $results[$row[self::$_mapKey]] = count($row) > 2 ? $result : end($result);
            } else {
                array_push($results, $result);
            }
        }

        if ($shouldStoreResult) {
            $stmt->free_result();
        }

        $stmt->close();

        // stored procedures sometimes can return more then 1 resultset
        if (self::mysqli()->more_results()) {
            self::mysqli()->next_result();
        }

        if (in_array('SQL_CALC_FOUND_ROWS', self::$_queryOptions)) {
            $stmt = self::mysqli()->query('SELECT FOUND_ROWS()');
            $totalCount = $stmt->fetch_row();
            self::$totalCount = $totalCount[0];
        }

        if (self::$returnType == 'json') {
            return json_encode($results);
        }

        return $results;
    }

    /**
     * Abstraction method that will build an JOIN part of the query
     *
     * @return void
     */
    protected static function _buildJoinOld()
    {
        if (empty(self::$_join)) {
            return;
        }

        foreach (self::$_join as $data) {
            list($joinType, $joinTable, $joinCondition) = $data;

            if (is_object($joinTable)) {
                $joinStr = self::_buildPair("", $joinTable);
            } else {
                $joinStr = $joinTable;
            }

            self::$_query .= " " . $joinType . " JOIN " . $joinStr .
                (false !== stripos($joinCondition, 'using') ? " " : " on ")
                . $joinCondition;
        }
    }

    /**
     * Insert/Update query helper
     *
     * @param array $tableData
     * @param array $tableColumns
     * @param bool  $isInsert INSERT operation flag
     *
     * @throws Exception
     */
    public static function _buildDataPairs($tableData, $tableColumns, $isInsert)
    {
        foreach ($tableColumns as $column) {
            $value = $tableData[$column];

            if (!$isInsert) {
                if (strpos($column, '.') === false) {
                    self::$_query .= "`" . $column . "` = ";
                } else {
                    self::$_query .= str_replace('.', '.`', $column) . "` = ";
                }
            }

            // Subquery value
            if ($value instanceof Database) {
                self::$_query .= self::_buildPair("", $value) . ", ";
                continue;
            }

            // Simple value
            if (!is_array($value)) {
                self::_bindParam($value);
                self::$_query .= '?, ';
                continue;
            }

            // Function value
            $key = key($value);
            $val = $value[$key];
            switch ($key) {
                case '[I]':
                    self::$_query .= $column . $val . ", ";
                    break;
                case '[F]':
                    self::$_query .= $val[0] . ", ";
                    if (!empty($val[1])) {
                        self::_bindParams($val[1]);
                    }
                    break;
                case '[N]':
                    if ($val == null) {
                        self::$_query .= "!" . $column . ", ";
                    } else {
                        self::$_query .= "!" . $val . ", ";
                    }
                    break;
                default:
                    throw new DatabaseException("Wrong operation");
            }
        }
        self::$_query = rtrim(self::$_query, ', ');
    }

    /**
     * Helper function to add variables into the query statement
     *
     * @param array $tableData Variable with values
     *
     * @throws Exception
     */
    protected static function _buildOnDuplicate($tableData)
    {
        if (is_array(self::$_updateColumns) && !empty(self::$_updateColumns)) {
            self::$_query .= " ON DUPLICATE KEY UPDATE ";
            if (self::$_lastInsertId) {
                self::$_query .= self::$_lastInsertId . "=LAST_INSERT_ID (" . self::$_lastInsertId . "), ";
            }

            foreach (self::$_updateColumns as $key => $val) {
                // skip all params without a value
                if (is_numeric($key)) {
                    self::$_updateColumns[$val] = '';
                    unset(self::$_updateColumns[$key]);
                } else {
                    $tableData[$key] = $val;
                }
            }
            self::_buildDataPairs($tableData, array_keys(self::$_updateColumns), false);
        }
    }

    /**
     * Abstraction method that will build an INSERT or UPDATE part of the query
     *
     * @param array $tableData
     *
     * @throws Exception
     */
    protected static function _buildInsertQuery($tableData)
    {
        if (!is_array($tableData)) {
            return;
        }

        $isInsert = preg_match('/^[INSERT|REPLACE]/', self::$_query);
        $dataColumns = array_keys($tableData);
        if ($isInsert) {
            if (isset($dataColumns[0]))
                self::$_query .= ' (`' . implode('`, `', $dataColumns) . '`) ';
            self::$_query .= ' VALUES (';
        } else {
            self::$_query .= " SET ";
        }

        self::_buildDataPairs($tableData, $dataColumns, $isInsert);

        if ($isInsert) {
            self::$_query .= ')';
        }
    }

    /**
     * Abstraction method that will build the part of the WHERE conditions
     *
     * @param string $operator
     * @param array  $conditions
     */
    protected static function _buildCondition($operator, &$conditions)
    {
        if (empty($conditions)) {
            return;
        }

        //Prepare the where portion of the query
        self::$_query .= ' ' . $operator;

        $currentGroup = null;
        $groupStarted = false;
        $condCount = 1;

        foreach ($conditions as $cond) {

            list($concat, $varName, $operator, $val, $_where_group_index, $_has_where_group) = $cond;

            $groupedConcatStart = '';
            $groupedConcatEnd = '';
            $groupedConcatLast = '';

            if ($_has_where_group && ($currentGroup == null || $currentGroup != $_where_group_index)) {
                if ($currentGroup != null) {
                    $groupedConcatEnd = ') ';
                    $groupStarted = false;
                }
                $currentGroup = $_where_group_index;
                $groupedConcatStart = ' (';
                $groupStarted = true;
            }

            if ($currentGroup != null && (!$_has_where_group || $currentGroup != $_where_group_index)) {
                $groupedConcatEnd = ') ';
                $currentGroup = $_where_group_index;
                $groupStarted = false;
            }

            if ($groupStarted && $condCount == count($conditions)) {
                $groupedConcatLast = ') ';
                $groupStarted = false;
            }

            $condCount++;

            self::$_query .= $groupedConcatEnd . $concat . $groupedConcatStart . " " . $varName;

            switch (strtolower($operator)) {
                case 'not in':
                case 'in':
                    $comparison = ' ' . $operator . ' (';
                    if (is_object($val)) {
                        $comparison .= self::_buildPair("", $val);
                    } else {
                        foreach ($val as $v) {
                            $comparison .= ' ?,';
                            self::_bindParam($v);
                        }
                    }
                    self::$_query .= rtrim($comparison, ',') . ' ) ';
                    break;
                case 'not between':
                case 'between':
                    self::$_query .= " $operator ? AND ? ";
                    self::_bindParams($val);
                    break;
                case 'not exists':
                case 'exists':
                    self::$_query .= $operator . self::_buildPair("", $val);
                    break;
                default:
                    if (is_array($val)) {
                        self::_bindParams($val);
                    } elseif ($val === null) {
                        self::$_query .= ' ' . $operator . " NULL";
                    } elseif ($val != 'DBNULL' || $val == '0') {
                        self::$_query .= self::_buildPair($operator, $val);
                    }
            }

            self::$_query .= $groupedConcatLast;
        }
    }

    /**
     * Abstraction method that will build the GROUP BY part of the WHERE statement
     *
     * @return void
     */
    protected static function _buildGroupBy()
    {
        if (empty(self::$_groupBy)) {
            return;
        }

        self::$_query .= " GROUP BY ";

        foreach (self::$_groupBy as $key => $value) {
            self::$_query .= $value . ", ";
        }

        self::$_query = rtrim(self::$_query, ', ') . " ";
    }

    /**
     * Abstraction method that will build the LIMIT part of the WHERE statement
     *
     * @return void
     */
    protected static function _buildOrderBy()
    {
        if (empty(self::$_orderBy)) {
            return;
        }

        self::$_query .= " ORDER BY ";
        foreach (self::$_orderBy as $prop => $value) {
            if (strtolower(str_replace(" ", "", $prop)) == 'rand()') {
                self::$_query .= "rand(), ";
            } else {
                self::$_query .= $prop . " " . $value . ", ";
            }
        }

        self::$_query = rtrim(self::$_query, ', ') . " ";
    }

    /**
     * Abstraction method that will build the LIMIT part of the WHERE statement
     *
     * @param int|array $numRows     Array to define SQL limit in format Array ($offset, $count)
     *                               or only $count
     *
     * @return void
     */
    protected static function _buildLimit($numRows)
    {
        if (!isset($numRows) && !isset(self::$_limitAttrs)) {
            return;
        }

        $useInLineLimit = true;

        if (!empty(self::$_limitAttrs)) {
            $useInLineLimit = false;
            self::$_query .= ' LIMIT ' . self::$_limitAttrs;
        }

        if ($useInLineLimit) {
            if (is_array($numRows)) {
                self::$_query .= ' LIMIT ' . (int) $numRows[0] . ', ' . (int) $numRows[1];
            } else {
                if ($numRows > 0) {
                    self::$_query .= ' LIMIT ' . (int) $numRows;
                }
            }
        }
    }

    protected static function __limit($limitAttrs = [])
    {
        if (!isset($limitAttrs)) {
            self::$_limitAttrs = [];
        }

        self::$_limitAttrs = $limitAttrs;
    }

    /**
     * Method attempts to prepare the SQL query
     * and throws an error if there was a problem.
     *
     * @return mysqli_stmt
     * @throws Exception
     */
    protected static function _prepareQuery()
    {
        $stmt = self::mysqli()->prepare(self::$_query);

        if ($stmt !== false) {
            if (self::$traceEnabled)
                self::$traceStartQ = microtime(true);
            return $stmt;
        }

        if (self::mysqli()->errno === 2006 && self::$autoReconnect === true && self::$autoReconnectCount === 0) {
            self::connect(self::$defConnectionName);
            self::$autoReconnectCount++;
            return self::_prepareQuery();
        }

        $error = self::mysqli()->error;
        $query = self::$_query;
        $errno = self::mysqli()->errno;
        self::reset();
        throw new DatabaseException(sprintf('%s query: %s', $error, $query), $errno);
    }

    /**
     * Referenced data array is required by mysqli since PHP 5.3+
     *
     * @param array $arr
     *
     * @return array
     */
    protected static function refValues(array &$arr)
    {
        //Reference in the function arguments are required for HHVM to work
        //https://github.com/facebook/hhvm/issues/5155
        //Referenced data array is required by mysqli since PHP 5.3+
        if (strnatcmp(phpversion(), '5.3') >= 0) {
            $refs = array();
            foreach ($arr as $key => $value) {
                $refs[$key] = &$arr[$key];
            }
            return $refs;
        }
        return $arr;
    }

    /**
     * Function to replace ? with variables from bind variable
     *
     * @param string $str
     * @param array  $vals
     *
     * @return string
     */
    protected static function replacePlaceHolders($str, $vals)
    {
        $i = 1;
        $newStr = "";

        if (empty($vals)) {
            return $str;
        }

        while ($pos = strpos($str, "?")) {
            $val = $vals[$i++];
            if (is_object($val)) {
                $val = '[object]';
            }
            if ($val === null) {
                $val = 'NULL';
            }
            $newStr .= substr($str, 0, $pos) . "'" . $val . "'";
            $str = substr($str, $pos + 1);
        }
        $newStr .= $str;
        return $newStr;
    }

    /**
     * Method returns last executed query
     *
     * @return string
     */
    public static function getLastQuery()
    {
        return self::$_lastQuery;
    }

    /**
     * Method returns mysql error
     *
     * @return string
     * @throws Exception
     */
    public static function getLastError()
    {
        if (!isset(self::$_mysqli[self::$defConnectionName])) {
            return "mysqli is null";
        }
        return trim(self::$_stmtError . " " . self::mysqli()->error);
    }

    /**
     * Method returns mysql error code
     *
     * @return int
     */
    public static function getLastErrno()
    {
        return self::$_stmtErrno;
    }

    /**
     * Mostly internal method to get query and its params out of subquery object
     * after get() and getAll()
     *
     * @return array
     */
    public static function getSubQuery()
    {
        if (!self::$isSubQuery) {
            return null;
        }

        array_shift(self::$_bindParams);
        $val = array(
            'query' => self::$_query,
            'params' => self::$_bindParams,
            'alias' => isset(self::$connectionsSettings[self::$defConnectionName]) ? self::$connectionsSettings[self::$defConnectionName]['host'] : null
        );
        self::reset();
        return $val;
    }

    /* Helper functions */

    /**
     * Method returns generated interval function as a string
     *
     * @param string $diff interval in the formats:
     *                     "1", "-1d" or "- 1 day" -- For interval - 1 day
     *                     Supported intervals [s]econd, [m]inute, [h]hour, [d]day, [M]onth, [Y]ear
     *                     Default null;
     * @param string $func Initial date
     *
     * @return string
     * @throws Exception
     */
    public static function interval($diff, $func = "NOW()")
    {
        $types = array("s" => "second", "m" => "minute", "h" => "hour", "d" => "day", "M" => "month", "Y" => "year");
        $incr = '+';
        $items = '';
        $type = 'd';

        if ($diff && preg_match('/([+-]?) ?([0-9]+) ?([a-zA-Z]?)/', $diff, $matches)) {
            if (!empty($matches[1])) {
                $incr = $matches[1];
            }

            if (!empty($matches[2])) {
                $items = $matches[2];
            }

            if (!empty($matches[3])) {
                $type = $matches[3];
            }

            if (!in_array($type, array_keys($types))) {
                throw new DatabaseException("invalid interval type in '{$diff}'");
            }

            $func .= " " . $incr . " interval " . $items . " " . $types[$type] . " ";
        }
        return $func;
    }

    /**
     * Method returns generated interval function as an insert/update function
     *
     * @param string $diff interval in the formats:
     *                     "1", "-1d" or "- 1 day" -- For interval - 1 day
     *                     Supported intervals [s]econd, [m]inute, [h]hour, [d]day, [M]onth, [Y]ear
     *                     Default null;
     * @param string $func Initial date
     *
     * @return array
     * @throws Exception
     */
    public static function now($diff = null, $func = "NOW()")
    {
        return array("[F]" => array(self::interval($diff, $func)));
    }

    /**
     * Method generates incremental function call
     *
     * @param int $num increment by int or float. 1 by default
     *
     * @throws Exception
     * @return array
     */
    public static function inc($num = 1)
    {
        if (!is_numeric($num)) {
            throw new DatabaseException('Argument supplied to inc must be a number');
        }
        return array("[I]" => "+" . $num);
    }

    /**
     * Method generates decremental function call
     *
     * @param int $num increment by int or float. 1 by default
     *
     * @return array
     * @throws Exception
     */
    public static function dec($num = 1)
    {
        if (!is_numeric($num)) {
            throw new DatabaseException('Argument supplied to dec must be a number');
        }
        return array("[I]" => "-" . $num);
    }

    /**
     * Method generates change boolean function call
     *
     * @param string $col column name. null by default
     *
     * @return array
     */
    public static function not($col = null)
    {
        return array("[N]" => (string)$col);
    }

    /**
     * Method generates user defined function call
     *
     * @param string $expr user function body
     * @param array  $bindParams
     *
     * @return array
     */
    public static function func($expr, $bindParams = null)
    {
        return array("[F]" => array($expr, $bindParams));
    }

    /**
     * Method creates new Database object for a subquery generation
     *
     * @param string $subQueryAlias
     *
     * @return Database
     */
    public static function subQuery($subQueryAlias = "")
    {
        return new self(array('host' => $subQueryAlias, 'isSubQuery' => true));
    }

    /**
     * Method returns a copy of a Database subquery object
     *
     * @return Database new Database object
     */
    public static function copy()
    {
        $copy = unserialize(serialize(self::$_instance));
        $copy->_mysqli = array();
        return $copy;
    }

    /**
     * Begin a transaction
     *
     * @uses mysqli->autocommit(false)
     * @uses register_shutdown_function(array($this, "_transaction_shutdown_check"))
     * @throws Exception
     */
    public static function startTransaction()
    {
        self::mysqli()->autocommit(false);
        self::$_transaction_in_progress = true;
        register_shutdown_function(array(self::$_instance, "_transaction_status_check"));
    }

    /**
     * Transaction commit
     *
     * @uses mysqli->commit();
     * @uses mysqli->autocommit(true);
     * @throws Exception
     */
    public static function commit()
    {
        $result = self::mysqli()->commit();
        self::$_transaction_in_progress = false;
        self::mysqli()->autocommit(true);
        return $result;
    }

    /**
     * Transaction rollback function
     *
     * @uses mysqli->rollback();
     * @uses mysqli->autocommit(true);
     * @throws Exception
     */
    public static function rollback()
    {
        $result = self::mysqli()->rollback();
        self::$_transaction_in_progress = false;
        self::mysqli()->autocommit(true);
        return $result;
    }

    /**
     * Shutdown handler to rollback uncommited operations in order to keep
     * atomic operations sane.
     *
     * @uses mysqli->rollback();
     * @throws Exception
     */
    public static function _transaction_status_check()
    {
        if (!self::$_transaction_in_progress) {
            return;
        }
        self::rollback();
    }

    /**
     * Query execution time tracking switch
     *
     * @param bool   $enabled     Enable execution time tracking
     * @param string $stripPrefix Prefix to strip from the path in exec log
     *
     * @return Database
     */
    public static function setTrace($enabled, $stripPrefix = null)
    {
        self::$traceEnabled = $enabled;
        self::$traceStripPrefix = $stripPrefix;
        return self::$_instance;
    }

    /**
     * Get where and what function was called for query stored in Database->trace
     *
     * @return string with information
     */
    private static function _traceGetCaller()
    {
        $dd = debug_backtrace();
        $caller = next($dd);
        while (isset($caller) && $caller["file"] == __FILE__) {
            $caller = next($dd);
        }

        return __CLASS__ . "->" . $caller["function"] . "() >>  file \"" .
            str_replace(self::$traceStripPrefix, '', $caller["file"]) . "\" line #" . $caller["line"] . " ";
    }

    /**
     * Method to check if needed table is created
     *
     * @param array $tables Table name or an Array of table names to check
     *
     * @return bool True if table exists
     * @throws Exception
     */
    public static function tableExists($tables)
    {
        $tables = !is_array($tables) ? array($tables) : $tables;
        $count = count($tables);
        if ($count == 0) {
            return false;
        }

        foreach ($tables as $i => $value)
            $tables[$i] = self::$prefix . $value;
        $db = isset(self::$connectionsSettings[self::$defConnectionName]) ? self::$connectionsSettings[self::$defConnectionName]['db'] : null;
        self::__where('table_schema', $db);
        self::__where('table_name', $tables, 'in');
        self::__getRows($count, '*', 'information_schema.tables');
        return self::$count == $count;
    }

    /**
     * Return result as an associative array with $idField field value used as a record key
     *
     * Array Returns an array($k => $v) if get(.."param1, param2"), array ($k => array ($v, $v)) otherwise
     *
     * @param string $idField field name to use for a mapped element key
     *
     * @return Database
     */
    public static function map($idField)
    {
        self::$_mapKey = $idField;
        return self::$_instance;
    }

    /**
     * Pagination wrapper to get()
     *
     * @access public
     *
     * @param int          $pageLimit   Page Limit
     * @param int          $page   Page number
     * @param array|string $fields Array or coma separated list of fields to fetch
     * @param string       $table  The name of the database table to work with
     *
     * @return array
     * @throws Exception
     */
    public static function paginate($pageLimit = 0, $page = 1, $fields = null, $table = '')
    {
        if (empty($table)) $table = self::$_tableName;
        if (empty($pageLimit)) $pageLimit = self::$pageLimit;
        $offset = $pageLimit * ($page - 1);
        $res = self::withTotalCount()->__getRows(array($offset, $pageLimit), $fields, $table);
        self::$totalPages = ceil(self::$totalCount / $pageLimit);
        $res['__pagination'] = [
            'totalPages' => self::$totalPages,
            'currentPage' => $page,
            'hasNextPage' => $page < self::$totalPages,
        ];
        return $res;
    }

    /**
     * Return result as an associative array with $idField field value used as a record key
     *
     * Array Returns an array($k => $v) if get(.."param1, param2"), array ($k => array ($v, $v)) otherwise
     *
     * @param int $pageLimit to show per page
     *
     * @return Database
     */
    public static function perPage($pageLimit)
    {
        self::$pageLimit = $pageLimit;
        return self::$_instance;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) AND WHERE statements for the join table on part of the SQL query.
     *
     * @uses $dbWrapper->__joinWhere('user u', 'u.id', 7)->where('user u', 'u.title', 'MyTitle');
     *
     * @param string $whereJoin  The name of the table followed by its prefix.
     * @param string $whereProp  The name of the database field.
     * @param $whereValue The value of the database field.
     *
     * @param string $operator
     * @param string $cond
     *
     * @return $this
     */
    public static function __joinWhere($whereJoin, $whereProp, $whereValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        self::$_joinAnd[self::$prefix . $whereJoin][] = array($cond, $whereProp, $operator, $whereValue);
        return self::$_instance;
    }

    /**
     * This method allows you to specify multiple (method chaining optional) OR WHERE statements for the join table on part of the SQL query.
     *
     * @uses $dbWrapper->__joinWhere('user u', 'u.id', 7)->where('user u', 'u.title', 'MyTitle');
     *
     * @param string $whereJoin  The name of the table followed by its prefix.
     * @param string $whereProp  The name of the database field.
     * @param $whereValue The value of the database field.
     * @param string $operator
     *
     * @return $this
     */
    public static function __joinOrWhere($whereJoin, $whereProp, $whereValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        return self::__joinWhere($whereJoin, $whereProp, $whereValue, $operator, 'OR');
    }

    /**
     * Abstraction method that will build an JOIN part of the query
     */
    protected static function _buildJoin()
    {
        if (empty(self::$_join))
            return;

        foreach (self::$_join as $data) {
            list($joinType,  $joinTable, $joinCondition) = $data;

            if (is_object($joinTable))
                $joinStr = self::_buildPair("", $joinTable);
            else
                $joinStr = $joinTable;

            self::$_query .= " " . $joinType . " JOIN " . $joinStr .
                (false !== stripos($joinCondition, 'using') ? " " : " on ")
                . $joinCondition;

            // Add join and query
            if (!empty(self::$_joinAnd) && isset(self::$_joinAnd[$joinStr])) {
                foreach (self::$_joinAnd[$joinStr] as $join_and_cond) {
                    list($concat, $varName, $operator, $val) = $join_and_cond;
                    self::$_query .= " " . $concat . " " . $varName;
                    self::conditionToSql($operator, $val);
                }
            }
        }
    }

    /**
     * Convert a condition and value into the sql string
     *
     * @param  String       $operator The where constraint operator
     * @param  String|array $val      The where constraint value
     */
    private static function conditionToSql($operator, $val)
    {
        switch (strtolower($operator)) {
            case 'not in':
            case 'in':
                $comparison = ' ' . $operator . ' (';
                if (is_object($val)) {
                    $comparison .= self::_buildPair("", $val);
                } else {
                    foreach ($val as $v) {
                        $comparison .= ' ?,';
                        self::_bindParam($v);
                    }
                }
                self::$_query .= rtrim($comparison, ',') . ' ) ';
                break;
            case 'not between':
            case 'between':
                self::$_query .= " $operator ? AND ? ";
                self::_bindParams($val);
                break;
            case 'not exists':
            case 'exists':
                self::$_query .= $operator . self::_buildPair("", $val);
                break;
            default:
                if (is_array($val))
                    self::_bindParams($val);
                else if ($val === null)
                    self::$_query .= $operator . " NULL";
                else if ($val != 'DBNULL' || $val == '0')
                    self::$_query .= self::_buildPair($operator, $val);
        }
    }
    /**
     * Truncate table
     *
     * @param  String  $tableName to truncate/empty
     */
    public static function __truncate($tableName = '')
    {
        if (empty($tableName)) $tableName = self::$_tableName;
        if (count(self::__rawQuery('TRUNCATE TABLE `' . $tableName . '`')) == 0)
            return true;
        return false;
    }
    /**
     * Get primary DB to connect
     * 
     * @return array $primaryDB
     */
    public static function primaryDB()
    {
        $settings = setting('database');
        $primaryDB = [];
        $primaryDBFound = false;
        foreach ($settings as $database => $setting) {
            if (is_array($setting)) {
                $setting['db'] = $database;
                if (key_exists('host', $setting) && key_exists('username', $setting) && key_exists('password', $setting) && key_exists('is_primary', $setting)) {
                    if ($setting['is_primary'] == true) {
                        if ($primaryDBFound) {
                            throw new MultiplePrimaryDBFound('Mutiple primary DB found in settings/database.php');
                        }
                        $primaryDB = $setting;
                        $primaryDBFound = true;
                    } else if (!$primaryDBFound) {
                        $primaryDB = $setting;
                    }
                }
            }
        }
        return $primaryDB;
    }
    /**
     * Get DB settings to connect
     * 
     * @param String $database The where constraint operator
     * 
     * @return array $dbSettings
     */
    public static function findSettings(string $database = '')
    {
        if (!empty($dbSettings = setting('database.' . $database))) {
            $dbSettings['db'] = $database;
            return $dbSettings;
        }
        throw new DatabaseException('Database info for `' . $database . '` not found in settings/database.php');
    }

    /**
     * Invoke useful dynamic methods by using magic method __call
     * 
     * @param string $method 
     * @param array $arguments
     * 
     * @return method response
     */
    public static function __callStatic($method, $arguments)
    {
        if (method_exists((new static), '__' . $method)) {
            return (new static)->{'__' . $method}(...$arguments);
        }

        throw new BadMethodException($method . ' method not defined');
    }

    /**
     * Invoke useful dynamic methods by using magic method __call
     * 
     * @param string $method 
     * @param array $arguments
     * 
     * @return method response
     */
    public function __call(string $method, $arguments)
    {
        if (method_exists($this, '__' . $method)) {
            return $this->{'__' . $method}(...$arguments);
        }

        if (!empty($method)) {
            if (strpos($method, 'As') !== FALSE) {
                $methodParts = explode('As', $method);
                $returnType = strtolower($methodParts[1]);
                if (in_array($returnType, ['json', 'object', 'array']) && method_exists(self::$_instance, $methodParts[0])) {
                    self::$returnType = $returnType;
                    return call_user_func_array('self::' . $methodParts[0], $arguments);
                }
            }
            throw new BadMethodException($method . ' method not defined');
        }
        throw new BadMethodException($method . ' method not defined');
    }
}