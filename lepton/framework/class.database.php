<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		Website Baker Project, LEPTON Project
 * @copyright	2004-2010, Website Baker Project
 * @copyright       2010-2014 LEPTON Project
 * @link		http://www.LEPTON-cms.org
 * @license		http://www.gnu.org/licenses/gpl.html
 * @license_terms	please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-30
 */


// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH'))
{
    include(WB_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

require_once(WB_PATH . '/framework/functions.php');

global $database;

class database
{
	/**
	 *	Private var for the error-messages.
	 */
    private	$error = '';
    
    /**
     *	Public database-handle.
     */
    public	$db_handle = false;
    
    /**
     *	Private var for the error-message-handling.
     *	If set to 'true' the process will stop at the first (db/query) error
     */
    private	$prompt_on_error = false;
    
    /**
     *	Private var to handle the session_check.
     */
    private	$override_session_check = false;
    
    /**
     * Constructor of the class database
     *
     *	@param	array	Assoc. array with the connection-settings. Pass by reference!
     *
     *	@seealso		Method "connect" for details.		
     */
    public function __construct(&$settings = array())
    {
        $this->connect($settings);
    } // __construct()
    
    /**
     * Destructor of the class database
     */
    public function __destruct()
    {

    } // __desctruct()
    
    /**
     * Set error
     * @param string
     */
    protected function set_error($error = '')
    {
        $this->error = $error;
    } // set_error()
    
    /**
     * Return the last error
     * @return string
     */
    public function get_error()
    {
        return $this->error;
    } // get_error()
    
    /**
     * Check if there occured any error
     * @return boolean
     */
    public function is_error()
    {
        return (!empty($this->error)) ? true : false;
    } // is_error()
        
    /**
     * Get the MySQL DB handle
     * 
     * @return resource or boolean false if no connection is established
     */
    public function get_db_handle()
    {
        return $this->db_handle;
    } // get_db_handle()
        
    /**
     *	Establish the connection to the desired database defined in /config.php.
     *
     *	This function does not connect multiple times, if the connection is
     *	already established the existing database handle will be used.
     *
     *	@param	array	Assoc. array within optional settings. Pass by reference!
     *	@return boolean
     *
     *	@notice	Param 'settings' is an assoc. array with the connection-settins, e.g.:
     *			$settings = array(
     *				'host'	=> "example.tld",
     *				'user'	=> "example_user_string",
     *				'pass'	=> "example_user_password",
     *				'name'	=> "example_database_name",
     *				'port'	=>	"1003",
     *				'charset' => "ISO-8859-1"
     *			);
     *
     */
    final function connect(&$settings = array())
    {
        
        $setup = array(
            'host' => (array_key_exists('host', $settings) ? $settings['host'] : DB_HOST),
            'user' => (array_key_exists('user', $settings) ? $settings['user'] : DB_USERNAME),
            'pass' => (array_key_exists('pass', $settings) ? $settings['pass'] : DB_PASSWORD),
            'name' => (array_key_exists('name', $settings) ? $settings['name'] : DB_NAME),
            'port' => (array_key_exists('port', $settings) ? $settings['port'] : DB_PORT),
            'charset' => (array_key_exists('charset', $settings) ? $settings['charset'] : "utf8")
        );
        
        // use DB_PORT only if it differ from the standard port 3306
        if ($setup['port'] !== '3306')
            $setup['host'] .= ';port=' . $setup['port'];
       
		$dsn = "mysql:dbname=".$setup['name'].";host=".$setup['host'].";charset=".$setup['charset'];
		
		try {
		
			$this->db_handle = new PDO(
				$dsn,
				$setup['user'],
				$setup['pass'],
				array(
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$setup['charset'],
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_PERSISTENT => true,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				)
			);
			
			/**
			 *	Keep in mind that prior to php-version 5.3.6, charset was ignored in the dsn.
			 *
			 */
			$this->query("SET NAMES '".$setup['charset']."'");
			
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
		
    } // connect()
    
    /**
     * Switch prompting of errors on or off
     * If $switch=true the database will trigger each error.
     * 
     * @param boolean $switch	Default is 'true'.
     */
    public function prompt_on_error($switch = true)
    {
        $this->prompt_on_error = $switch;
    } // prompt_on_error()
    
    /**
     * Exec a SQL query and return a handle to queryMySQL
     * @param	STR		$SQL - the query string to execute
     * @return	RESOURCE or NULL for error
     */
    public function query($SQL)
    {
		$result = new queryMySQL( $this->db_handle );
		$return_val =  $result->query( $SQL );
		$err = $this->db_handle->errorInfo();
		
	if ( ( is_array($err) ) && (isset($err[2]) ))
		{
			$this->set_error($err[2]);
			return NULL;
		}
		else
		{
			return $return_val;
		}
    } // query()
    
    /**
     *	Execute a SQL query and return the first row of the result array
     *
     *	@param	string	Any SQL-Query or statement
     *	@param	const	Type for the fetchRow-method for the return-array.
     *					Could be one of the following constants:
     *					MYSQL_ASSOC, MYSQL_NUM or MYSQL_BOTH (default).
     *					Any other value will result in MYSQL_BOTH.
     *
     *	@return	mixed 	Value of the table-field or NULL for error
     */
    public function get_one($SQL, $type = MYSQL_BOTH)
    {
        $query = $this->query($SQL);
        if (($query !== null) && ($query->numRows() > 0))
        {
        	$temp = $query->fetchRow( MYSQL_ASSOC );
            return array_shift($temp);
        }
        return null;
    } // get_one()
    
    /**
     * Read GUID from database
     * Don't use the LEPTON_GUID because GUID may change while runtime!
     * @return STR GUID
     */
    private function getLeptonGUID()
    {
        if (defined("LEPTON_INSTALL"))
            return "E610A7F2-5E4A-4571-9391-C947152FDFB0";
        $this->override_session_check = true;
        $SQL                          = sprintf("SELECT value FROM %ssettings WHERE name='lepton_guid'", TABLE_PREFIX);
        $result                       = $this->get_one($SQL);
        $this->override_session_check = false;
        return $result;
    } // getLeptonGUID()
    
    /**
     * Check the Lepton GUID
     * 
     * The Lepton GUID is splitted in two parts: the primary GUID which identify _this_
     * installation and is unique in all Lepton installations worldwide and the second
     * part of the GUID string (the last 12 digits) which contains informations about the
     * installation, i.e. if the installation is registered. 
     * @return BOOL
     */
    private function __checkGUID()
    {
        $lepton_guid = $this->getLeptonGUID();
        if ((strlen($lepton_guid) < 37) || (substr_count($lepton_guid, '-') !== 4))
        {
            // invalid GUID - create a new one!
            return false;
        }
        return true;
    } // __checkGUID()
    
    private function __initSession()
    {
        if (defined('SESSION_STARTED') && !isset($_SESSION['LEPTON_SESSION']))
        {
            // $_SESSION for class.database.php
            $this->__checkGUID();
            $_SESSION['LEPTON_SESSION'] = true;
        }
    } // __initSession()
    
    /**
     *	Get more than one result in an assoc. array. E.g. "Select * form [TP]pages" if you want
     *	to get all informations about all pages.
     *
     *	@param	string	The query itself
     *	@param	array	The array to store the results. Pass by reference!
     *
     */
    public function get_all($query, &$storrage = array())
    {
    	$result = new queryMySQL( $this->db_handle );
    	$temp = $result->fetchAll( $query );
    	foreach($temp as $data) $storrage[] = $data;
    }
    
    /**
     *	Returns a linear array within the tablenames of the current database
     *
     *	@param	string	Optional string to 'strip' chars from the tablenames, e.g. the prefix.
     *	@return	array	An array within the tablenames of the current database.
     *
     */
    public function list_tables(&$strip = "")
    {
        $result = $this->query("SHOW tables");
        if (!$result)
            return array(
                $this->get_error()
            );
        $ret_value = array();
        while (false != ($data = $result->fetchRow( MYSQL_ASSOC )))
        {
            $ret_value[] = array_shift( $data );
        }
        if ($strip != "")
        {
            foreach ($ret_value as &$ref)
                $ref = str_replace($strip, "", $ref);
        }
        return $ret_value;
    }
    
    /**
     *	Placed for all fields from a given table(-name) an assocc. array
     *	inside a given storrage-array.
     *
     *	@param	string	The tablename.
     *	@param	array	An array to store the results. Pass by reference!
     *	@return	bool	True if success, otherwise false.
     *
     */
    public function describe_table($tablename, &$storage = array())
    {
        $result = $this->query("DESCRIBE `" . $tablename . "`");
        if (!$result)
            return false;
        while (false != ($data = $result->fetchRow(MYSQL_ASSOC)))
        {
            $storage[] = $data;
        }
        return true;
    }
    
    /**
     *
     *	Build a query-string.
     *
     *	@param	string	The type, e.g. "update", "insert" or "delete"
     *	@param	string	The tablename
     *	@param	array	Assoc. array, that holds the field names corr. to the values
     *	@param	string	The condition
     *
     *	@return	string	The mySQL query-string or NULL if no type march.
     *
     *	@example
     *	$values = 	array(
     *		'page_title' => 'example',
     *		'menu_title' => 'example'
     *	);
     *		
     *	$query = $database_instance->build_mysql_query(
     *		'update',
     *		TABLE_PREFIX."pages",
     *		$values,
     *		'page_id = '.$page_id
     *	);
     */
    public function build_mysql_query($type, $table_name, &$table_values, $condition = "")
    {
        
        switch (strtolower($type))
        {
            
            case 'update':
                $q = "UPDATE `" . $table_name . "` set ";
                foreach ($table_values as $field => $value)
                    $q .= "`" . $field . "`='" . $this->mysql_escape( $value ) . "',";
                $q = substr($q, 0, -1) . (($condition != "") ? " WHERE " . $condition : "");
                
                break;
            
            case 'insert':
                $q = "INSERT into `" . $table_name . "` (`";
                $q .= implode("`,`", array_keys($table_values)) . "`) VALUES ('";
                $q .= implode("','", $this->mysql_escape( array_values($table_values) ) ) . "')";
                
                break;
            
            case 'delete':
                $q = "DELETE from `" . $table_name . "`" . (($condition != "") ? " WHERE " . $condition : "");
                
                break;
            
            case 'select':
                $q = "SELECT `";
                $q .= implode("`,`", $table_values) . "` ";
                $q .= "FROM `" . $table_name . "`" . (($condition != "") ? " WHERE " . $condition : "");
                
                break;
            
            /**
             *	Alter a table
             *
             *	On this one, we're using the $table_values in a differ way than normal:
             *	The array has to be formed as
             * 
             *	'fieldname' => array(
             *		'operation'	=> add | delete | set	!required
             *		'type'		=> field type e.g. VARCHAR(255)	!required
             *		'charset'	=> optional charset
             *		'collate'	=> optional collate
             *		'params'	=> optional any additional params like "not NULL"
             *	);
             *
             *	e.g.:
             *
             *	$upgrade_fields = array(
             *		'plan'	=> array(
             *			'operation'	=> "add",
             *			'type'		=> "varchar(255)",
             *			'charset'	=> "utf8",
             *			'collate'	=> "utf8_general_ci",
             *			'params'	=> "not NULL default ''"
             *		),
             *		'sort'	=> array(
             *			'operation' => "add",
             *			'type'		=> "varchar(255)",
             *			'charset'	=> "utf8",
             *			'collate'	=> "utf8_general_ci",
             *			'params'	=> "not NULL default ''"
             *		)
             *	);
             *
             */
            case 'alter':
                $q = "ALTER TABLE `" . $table_name . "`";
                
                foreach ($table_values as $name => &$options)
                {
                    
                    if ((!isset($options['operation'])) || (!isset($options['type'])))
                        continue;
                    
                    $q .= " " . strtoupper($options['operation']) . " `" . $name . "` " . $options['type'];
                    
                    if (isset($options['charset']))
                        $q .= " CHARACTER SET " . $options['charset'];
                    if (isset($options['collate']))
                        $q .= " COLLATE " . $options['collate'];
                    if (isset($options['params']))
                        $q .= " " . $options['params'];
                    
                    $q .= ",";
                }
                $q = substr($q, 0, -1);
                
                break;
            
            default:
                $q = NULL; // "Error: type doesn't match to 'update', 'insert', or 'delete'!";
                
        }
        
        return $q;
    }
    
	/**
	 *	A 'helper' function for method 'build_mysql_query'.
	 *
	 *	@param	mixed	Any string to be escaped, or an array wthin strings.
	 *	@return	mixed	The escaped string or an array within theese.
	 *
	 */
	static public function mysql_escape($str) { 
		if(is_array($str))
			return array_map(__METHOD__, $str); 

		if(!empty($str) && is_string($str)) { 
			return str_replace(
				array('\\', "\0", "\n", "\r", "'", '"', "\x1a"),
				array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'),
				$str
			); 
		} 

		return $str; 
	}

    /**
     *	Public "shortcut" for executeing a single mySql-query without passing values.
     *
     *	@since	LEPTON 2.0
     *
     *	@param	string	A valid mySQL query.
     *	@param	bool	Fetch the result - default is false.
     *	@param	array	A storage array for the fetched results. Pass by reference!
     *	@param	bool	Try to get all entries. Default is true.
     *
     */
    public function execute_query( $aQuery="", $bFetch=false, &$aStorage=array(), $bFetchAll=true ) {
    	$oStatement=$this->db_handle->prepare($aQuery);
    	$oStatement->execute();
    	if ( true === $bFetch ){
    		$aStorage = (true === $bFetchAll)
    			? $oStatement->fetchAll()
    			: $oStatement->fetch()
    			;
    	}
    	$err = $this->db_handle->errorInfo();
		if ($err[2] != "") $this->error = $err[2];
    }
    
    /**
     *	Public function for prepare a given query within marker and execute it.
     *
     *	@since	LEPTON 2.0
     *	@see	execute_query, build_and_execute
     *
     *	@param	string	A valid mySQL query string within marker ('?' or ':name').
     *	@param	array	A valid array within the values. Type depending on the query. Pass by reference.
     *	@param	bool	Boolean for fetching the result(-s). Default is false.
     *	@param	array	Optional array to store the results. Pass by reference.
     *	@param	bool	Try to get all entries. Default is true.
     *	@return	nothing
     *
     */
    public function prepare_and_execute( $sQuery="", &$aValues=array(), $bFetch=false, &$aStorage=array(), $bFetchAll=true ) {
		$oStatement=$this->db_handle->prepare($sQuery);
    	$oStatement->execute( $aValues );
   	   	if ( true === $bFetch ){
    		$aStorage = (true === $bFetchAll)
    			? $oStatement->fetchAll()
    			: $oStatement->fetch()
    			;
    	}
    	
    	$err = $this->db_handle->errorInfo();
		if ($err[2] != "") $this->error = $err[2];
    }
    
    /**
     *	Public function to build and execute a mySQL query direct.
     *	Use this function/method for update and insert values only.
     *	As for a simple select you can use "prepare_and_execute" above.
     *
     *	@param	string	A "job"-type: this time only "update" and "insert" are supported.
     *	@param	string	A valid tablename (incl. table-prefix).
     *	@param	array	An array within the table-field-names and values. Pass by reference!
     *	@param	string	An optional condition for "update" - this time a simple string.
     *	@return	bool	False if fails, otherwise true.
     *
     */
    public function build_and_execute( $type, $table_name, &$table_values, $condition="" ) {
    
    	switch( strtolower($type) ) {
    		case 'update':
    			$q = "UPDATE `". $table_name ."` SET ";
    			foreach($table_values as $field => $value) {
    				$q .= "`". $field ."`= :".$field.", ";
    			}
    			$q = substr($q, 0, -2) . (($condition != "") ? " WHERE " . $condition : "");
    			break;
   			
   			case 'insert':
   				$keys = array_keys($table_values);
                $q = "INSERT into `" . $table_name . "` (`";
                $q .= implode("`,`", $keys) . "`) VALUES (:";
                $q .= implode(", :", $keys) . ")";
               
                break;
            
   			default:
   				die("<build_and_execute>:: type unknown!");
   				break; 
    	}

    	$oStatement = $this->db_handle->prepare( $q );
    	$oStatement->execute( $table_values );
	
		$err = $this->db_handle->errorInfo();
		if ($err[2] != "")
		{
			$this->error = $err[2];
			return FALSE;
		} else {
			return TRUE;
		}
    }
} // class database

final class queryMySQL
{
    /**
     *	Internal storrage for the results.
     *	Since we're using PDO this is type of PDOStatement(-object)!
     *
     */
    private $query_result = false;
    
    /**
     *	Internal PDO handle.
     *
     */
    private $pdo = NULL;
    
    /**
     *	Construtor of the class.
     *
     *	@param	object	A valid PDO Handle
     *	@since	Lepton-CMS 2.0.0
     *
     */
    public function __construct( $pdo_handle )
    {
    	$this->pdo = &$pdo_handle;
    }
    
    /**
     *	Destructor of the class.
     *
     *	@since	Lepton-CMS 2.0.0
     *	@notice	Free all 'Sub'-Objects.
     *
     */
    public function __destruct()
    {
    	// if this class var is an object we've to call the __destruct method by our self
    	if ($this->query_result != false) unset( $this->query_result );
    }
    
    /**
     * Execute a MySQL query statement and return the resource or false on error
     * 
     * @param string	$SQL query
     * @return object	This
     *
     */
    public function query($SQL)
    {
        $this->query_result = $this->pdo->query($SQL);
        return $this;
    } // query()
    
    /**
     * Return the number of rows of the query result
     *
     * @return INT	The number of rows of the last query result.
     *
     */
    public function numRows()
    {
        return $this->query_result->rowCount();
    } // numRows()
    
    /**
     *	Fetch a Row from the result array
     * 
     *	Specify $array_type you want to get back: MYSQL_BOTH, MYSQL_NUM or MYSQL_ASSOC.
     *	Since we're using PDO there are also PDO constants supported.
     *	See http://www.php.net/manual/en/pdostatement.fetch.php for details.
     *
     *	This function return FALSE if there is no further row.
     *
     *	@param	INT		Typicaly a PHP Constant for the requestet type. Default is MYSQL_BOTH.
     *	@return	ARRAY	The result-array or false if there is no further row.
     */
    public function fetchRow($array_type = MYSQL_BOTH)
    {
    	switch($array_type)
    	{
    		case MYSQL_BOTH:
    			$type = PDO::FETCH_BOTH;
    			break;
    		
    		case MYSQL_NUM:
    			$type = PDO::FETCH_NUM;
    			break;
    			
    		case MYSQL_ASSOC:
    			$type = PDO::FETCH_ASSOC;
    			break;
    		
    		default:
    			$type = PDO::FETCH_ASSOC;
    	}
    	
        return $this->query_result->fetch( $type );
    } // fetchRow()
    
    /**
     *	Public function to return all results of the query as
     *	an assoc. array.
     *
     *	@param	string	The query-string to execute.
     *	@return array	The results as assoc. array.
     *
     */
    public function fetchAll( $SQL ) {
    	$this->query_result = $this->pdo->query($SQL);
    	return $this->query_result->fetchAll( PDO::FETCH_ASSOC );
    }
} // class queryMySQL
?>