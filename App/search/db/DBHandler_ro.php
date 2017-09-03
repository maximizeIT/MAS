<?php

// Error Reporting komplett abschalten
error_reporting(0);

/**
 * Class to handle all DB operations
 * Only for employee interactions
 */
class DbHandler {

    private $conn;
    
    function __construct() 
	{
        require_once dirname(__FILE__) . '/DBConnect_ro.php';
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

    public function getNrOfMemberships() 
	{
		$stmt = $this->conn->prepare("SELECT ms.membership_id FROM memberships ms");
        
		if($stmt)
		{
			$stmt->execute();
			$stmt->store_result();
			
			$nrOfRows = $stmt->num_rows;
			
			$stmt->close();

			return $nrOfRows;
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return null;
    }

    public function getNrOfMembers() 
	{
		$stmt = $this->conn->prepare("SELECT m.member_id FROM members m");
        
		if($stmt)
		{
			$stmt->execute();
			$stmt->store_result();
			
			$nrOfRows = $stmt->num_rows;
			
			$stmt->close();

			return $nrOfRows;
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return null;
    }

    public function getNrOfCommittees() 
	{
		$stmt = $this->conn->prepare("SELECT c.committee_id FROM committees c");
        
		if($stmt)
		{
			$stmt->execute();
			$stmt->store_result();
			
			$nrOfRows = $stmt->num_rows;
			
			$stmt->close();

			return $nrOfRows;
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return null;
    }

    public function getNrOfAssociations() 
	{
		$stmt = $this->conn->prepare("SELECT a.association_id FROM associations a");
        
		if($stmt)
		{
			$stmt->execute();
			$stmt->store_result();
			
			$nrOfRows = $stmt->num_rows;
			
			$stmt->close();

			return $nrOfRows;
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return null;
    }

    public function getAllMemberships()
	{
		$stmt = $this->conn->prepare("SELECT 	members.fname, 
												members.lname,
												memberships.membership_id,
												memberships.date_started, 
												memberships.date_ended,
												office_periods.date_starts,
												office_periods.date_ends,
												committees.name,
												associations.name,
												successors.reason

										FROM memberships 
										
										LEFT JOIN members ON memberships.member_id = members.member_id 
										
										LEFT JOIN office_periods ON memberships.period_id = office_periods.period_id
										
										LEFT JOIN associations ON memberships.association_id = associations.association_id
										
										LEFT JOIN committees ON memberships.committee_id = committees.committee_id

										LEFT JOIN successors ON memberships.successor_id = successors.successor_id AND successors.member_id = members.member_id"
										);
		if($stmt)
		{
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($fname, $lname, $membership_id, $date_started, $date_ended, $date_starts, $date_ends, $cname, $aname, $successorReason);
			
			$response["alldata"] = array ();

			while($stmt->fetch())
			{
				$tmp = array();
                $tmp['fname'] = $fname;
                $tmp['lname'] = $lname;
                $tmp['membership_id'] = $membership_id;
                $tmp['date_started'] = $date_started;
				$tmp['date_ended'] = $date_ended;
				$tmp['date_starts'] = $date_starts;
				$tmp['date_ends'] = $date_ends;
				$tmp['cname'] =$cname;
				$tmp['aname'] =$aname;
				$tmp['successorReason'] =$successorReason;

				array_push($response["alldata"], $tmp);
			}

			$stmt->close();
		} 
		else 
		{
			var_dump($stmt);
			return false;
		}

		return $response["alldata"];
	}

	public function getAllMembersPersonal()
	{
        $stmt = $this->conn->prepare("SELECT m.member_id, m.fname, m.lname, m.student_nr, m.email ,m.timestamp_added FROM members m");
        
		if($stmt)
		{
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($member_id, $fname, $lname, $student_nr, $email, $timestamp_added);
			
			$response["membersPersonal"] = array();
						
			while ($stmt->fetch()) 
			{
						$tmp = array();
						$tmp["member_id"] = $member_id;
						$tmp["fname"] = $fname;
						$tmp["lname"] = $lname;
						$tmp["student_nr"] = $student_nr;
						$tmp["email"] = $email;
						$tmp["timestamp_added"] = $timestamp_added;
						array_push($response["membersPersonal"], $tmp);
					}
			
			$stmt->close();
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $response["membersPersonal"];
	}
}

?>
