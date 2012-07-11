<?php
require_once("ioT.class.php");
require_once("ioF.php");
//define("DB_DEBUG",true);
//define("DB_DEBUG",false);

class DB {
    // Connection parameters
    var $host = 'internal-db.s19349.gridserver.com';
    var $user = 'db19349';
    var $password = 'ScpyXwms';
    var $database = 'db19349_smart';
    var $persistent = false;

    // Database connection handle
    var $conn = NULL;

    // Query result
    var $result = false;

    function DB() {
		switch(getHttpHost()) {
			case "localhost":
			$this->host = "internal-db.s19349.gridserver.com";
			break;
		}
    }

    function isActive() {
        return mysql_ping($this->conn);
    }

    function open() {

    	$t = new Timer();
    	$t->timerStart('connect');
    	
    	if (defined("DB_DEBUG")) {
    		logDebug("OPEN CONNECTION TO " . $this->host);
    	}

        // Choose the appropriate connect function
        if ($this->persistent) {
            $func = 'mysql_pconnect';
        } else {
            $func = 'mysql_connect';
        }

        // Connect to the MySQL server
        $this->conn = $func($this->host, $this->user, $this->password,true);
        if (!$this->conn) {
			header("Location: dberror.php?error=".mysql_error($this->conn));
			logError("DB Error: Cannot connect to DB Host: $this->host User: $this->user");
			exit();
            return false;
        }

        $execution_time = $t->timerStop('connect');
        if ($execution_time > 10) {
                logError("ERROR: Database connect time: " . round($execution_time,10));
        }

        $st = new Timer();
        $st->timerStart('select');
        // Select the requested database
        if (!@mysql_select_db($this->database, $this->conn)) {
			header("Location: dberror.php?error=".mysql_error($this->conn));
			logError("SELECT DB Error Host: $this->host Database: $this->database");
			exit();
            return false;
        }

        $execution_time = $st->timerStop('select');
        if ($execution_time > 5) {
                logError("ERROR: Database select time: " . round($execution_time,5));
        }

        return true;
    }

    function close() {
        return (@mysql_close($this->conn));
    }

    function error() {
        return (@mysql_error($this->conn));
    }

    function errorNumber() {
        return (@mysql_errno($this->conn));
    }

    function query($sql = '') {
		$t = new Timer();
		$t->timerStart('sql');
		$this->result = @mysql_query($sql, $this->conn);
		$execution_time_in_secs = $t->timerStop('sql');

		// check for performance and log if the query is too slow or DEBUG is set or Error occurred, show SQL

		if ($execution_time_in_secs > 30 | defined("DEBUG") | $this->errorNumber()) {
			if ($this->errorNumber()) {
  				$log_message = "MYSQL ROWS=".$this->affectedRows()." SQLERROR=".$this->errorNumber()." ".$this->error()." TIME=$execution_time_in_secs SQL=$sql";
			}
			else {
				$log_message = "MYSQL ROWS=".$this->affectedRows()." TIME=$execution_time_in_secs SQL=$sql";
			}
			logDebug($log_message);
		}
		if ($execution_time_in_secs > 30) {
			logError($log_message);
		}
		
        return ($this->result != false);
    }

    function affectedRows() {
        return (@mysql_affected_rows($this->conn));
    }

    function numRows() {
        return (@mysql_num_rows($this->result));
    }
	
    function getOne($sql = '') {
		$this->query($sql);
		$row=@mysql_fetch_row($this->result);
	    return ($row[0]);
    }

    function fetchObject() {
        return (@mysql_fetch_object($this->result));
    }

    function fetchArray() {
        return (@mysql_fetch_array($this->result, MYSQL_ASSOC));
    }

    function fetchArrayNum() {
        return (@mysql_fetch_array($this->result, MYSQL_NUM));
    }
	
    function fetchRow() {
        return (@mysql_fetch_row($this->result));
    }

    function fetchAssoc() {
        return (@mysql_fetch_assoc($this->result));
    }
	
    function dataSeek($row = 0) {
        return (@mysql_data_seek($this->result, $row));
    }

    function freeResult() {
        return (@mysql_free_result($this->result));
    }

    function insertID() {
    	return (@mysql_insert_id($this->conn));
    }

    function beginTransaction() {
    	return ($this->query("BEGIN"));
    }

    function commitTransaction() {
    	return ($this->query("COMMIT"));
    }

    function rollbackTransaction() {
    	return ($this->query("ROLLBACK"));
    }

    function get_column_members($table_name,$enum_col) {
        $result = $this->query("SHOW COLUMNS FROM $table_name LIKE " . '"' . $enum_col . '"');

        while ($answer = $this->fetchArray()) {
                ereg( "('(.*)')", $answer[1], $temp);
                $array = explode( "','", $temp[2] );
        }
        return $array;
    }

}
class smart_readDB extends DB {
    function smart_readDB() {
	    	$this->host = 'internal-db.s19349.gridserver.com';
	    	$this->user = 'db19349';
	    	$this->password = 'ScpyXwms';
	    	$this->database = 'db19349_smart';
	    	$this->persistent = false;
        return $this;
    }
}
?>
