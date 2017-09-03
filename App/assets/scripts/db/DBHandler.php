<?php

// Error Reporting komplett abschalten
error_reporting(0);

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 * Only for client interactions
 */
class DbHandler {

    private $conn;

    function __construct()
	{
        require_once dirname(__FILE__) . '/DBConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

	function _cleanUp($var)
	{
		return mysqli_real_escape_string($this->conn, $var);
	}

	public function start_transaction()
	{
		if(is_object($this->conn))
		{
			return $this->conn->autocommit(false);
		}
	}

	public function commit()
	{
		if(is_object($this->conn))
		{
			if($this->conn->commit())
			{
				return $this->conn->autocommit(true);
			}
			else
			{
				$this->conn->autocommit(true);
				throw new Exception;
			}
		}
	}

	public function rollback()
	{
		if(is_object($this->conn))
		{
			if($this->conn->rollback())
			{
				return $this->conn->autocommit(true);
			}
			else
			{
				$this->conn->autocommit(true);
				throw new Exception;
			}
		}
	}

    public function userLogin($u_username, $u_password, $u_user_browser)
	{
		$username = self::_cleanUp($u_username);
		$password = trim($u_password);

		$stmt = $this->conn->prepare("SELECT password_hash FROM users WHERE username = ?");

		if($stmt)
		{
			$stmt->bind_param("i", $username);
			$stmt->execute();
			$stmt->bind_result($password_hash);
			$stmt->store_result();

			if ($stmt->num_rows > 0)
			{
				// Now verify the password
				$stmt->fetch();

				if (password_verify($password, $password_hash))
				{
					// user password is correct

					$options = [ 'cost' => 12 ];

					$loginString = password_hash($password_hash . $u_user_browser, PASSWORD_BCRYPT, $options);

					$stmt->close();
					return $loginString;
				}
				else
				{
					$stmt->close();
					return false;
				}
			}
			else
			{
				$stmt->close();
				// user not existed with the username
				return false;
			}
		}
		else
		{
			var_dump($stmt);
			exit;
		}
    }

    public function getUserRole($u_username)
	{
		$username = self::_cleanUp($u_username);

		$stmt = $this->conn->prepare("SELECT u.user_role FROM users u WHERE u.username = ?");

		if($stmt)
		{
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($role);
			$stmt->fetch();
			$stmt->close();
		}
		else
		{
			var_dump($stmt);
			exit;
		}
        return $role;
    }
}
?>
