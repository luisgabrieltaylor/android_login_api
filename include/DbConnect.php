<?php


class DbConnect {
    
    private $conn;

    // constructor
    function __construct() {
        
        // connecting to database
        $this->connect();
    }

    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
    }

    /**
     * Establishing database connection
     * @return database handler
     */
    function connect() {        
        include_once dirname(__FILE__) . './Config.php';
        
        // Connecting to mysql database
        $this->conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());

        // Selecting database
        mysql_select_db(DB_DATABASE) or die(mysql_error());
        
        // returing connection resource
        return $this->conn;
    }

    /**
     * Closing database connection
     */
    function close() {
        // closing db connection
        mysql_close($this->conn);
    }

}

?>
