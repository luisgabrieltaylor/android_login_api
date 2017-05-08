<?php



class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'include/DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $uuid, $name, $email, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }
	
	public function storeDevice($name, $mac, $user) {
        
        $stmt = $this->conn->prepare("INSERT INTO devices(name, mac, user, created_at) VALUES(?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $mac, $user);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM devices WHERE mac = ?");
            $stmt->bind_param("s", $mac);
            $stmt->execute();
            $device = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $device;
        } else {
            return false;
        }
    }
	
	public function storeCode($erpco, $driver, $bus, $category, $region, $date, $site, $classification, $type, $suggestion) {
        
        $stmt = $this->conn->prepare("INSERT INTO codes(erpco, driver, bus, category, region, date, site, classification, type, suggestion, createdat) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssssss", $erpco, $driver, $bus, $category, $region, $date, $site, $classification, $type, $suggestion);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM codes WHERE erpco = ?");
            $stmt->bind_param("s", $erpco);
            $stmt->execute();
            $incident = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $incident;
        } else {
            return false;
        }
    }
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");

        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }
	
	/**
     * Get user by MAC
     */
    public function getDeviceByMAC($mac) {

        $stmt = $this->conn->prepare("SELECT * FROM devices WHERE mac = ?");

        $stmt->bind_param("s", $mac);

        if ($stmt->execute()) {
            $device = $stmt->get_result()->fetch_assoc();
            $stmt->close();
			return $device;
        } else {
            return NULL;
        }
    }
	
	public function getCode($erpco) {
        $stmt = $this->conn->prepare("SELECT * FROM codes WHERE erpco = ?");

        $stmt->bind_param("s", $erpco);

        if ($stmt->execute()) {
            $incident = $stmt->get_result()->fetch_assoc();
            $stmt->close();
			return $incident;
        } else {
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
	
	/**
     * Check device is existed or not
     */
	 public function isDeviceExisted($mac) {
        $stmt = $this->conn->prepare("SELECT mac from devices WHERE mac = ?");

        $stmt->bind_param("s", $mac);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
	
	public function isCodeExisted($erpco) {
        $stmt = $this->conn->prepare("SELECT erpco from codes WHERE erpco = ?");

        $stmt->bind_param("s", $erpco);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // code existed 
            $stmt->close();
            return true;
        } else {
            // code not existed
            $stmt->close();
            return false;
        }
    }
	
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

}

?>
