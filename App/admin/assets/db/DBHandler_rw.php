<?php

// Error Reporting komplett abschalten
error_reporting(0);

session_start();

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 * Only for client interactions
 */
class DbHandler {

    private $conn;
    
    function __construct() 
	{
        require_once dirname(__FILE__) . '/DBConnect_rw.php';
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

	public function getAllSystemUsers()
	{
        $stmt = $this->conn->prepare("SELECT u.user_id, u.username, u.password_hash, u.user_role, u.timestamp_added FROM users u");
        
		if($stmt)
		{
			// $stmt->bind_param("i", $empNr);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($user_id, $username, $password_hash, $user_role, $timestamp_added);
			
			$response["users"] = array();
						
			while ($stmt->fetch()) 
			{
						$tmp = array();
						$tmp["user_id"] = $user_id;
						$tmp["username"] = $username;
						$tmp["password_hash"] = $password_hash;
						$tmp["user_role"] = $user_role;
						$tmp["timestamp_added"] = $timestamp_added;
						array_push($response["users"], $tmp);
					}
			
			$stmt->close();
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $response["users"];
	}

	public function getAllCommittees()
	{
        $stmt = $this->conn->prepare("SELECT c.committee_id, c.name, c.description, c.timestamp_added FROM committees c");
        
		if($stmt)
		{
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($committee_id, $name, $description, $timestamp_added);
			
			$response["committees"] = array();
						
			while ($stmt->fetch()) 
			{
						$tmp = array();
						$tmp["committee_id"] = $committee_id;
						$tmp["name"] = $name;
						$tmp["description"] = $description;
						$tmp["timestamp_added"] = $timestamp_added;
						array_push($response["committees"], $tmp);
					}
			
			$stmt->close();
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $response["committees"];
	}

	public function getAllAssociations()
	{
        $stmt = $this->conn->prepare("SELECT a.association_id, a.name, a.website ,a.timestamp_added FROM associations a");
        
		if($stmt)
		{
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($association_id, $name, $website, $timestamp_added);
			
			$response["associations"] = array();
						
			while ($stmt->fetch()) 
			{
						$tmp = array();
						$tmp["association_id"] = $association_id;
						$tmp["name"] = $name;
						$tmp["website"] = $website;
						$tmp["timestamp_added"] = $timestamp_added;
						array_push($response["associations"], $tmp);
					}
			
			$stmt->close();
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $response["associations"];
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

	public function deleteMemberSingle($member_id) 
	{
        $stmt = $this->conn->prepare("DELETE m FROM members m WHERE m.member_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("i", $member_id);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
        } 
		else 
		{
			var_dump($stmt);
			exit;
		}
		return $num_affected_rows > 0;
    }

    public function deleteMemberMultiple($member_ids) 
	{
		$members = $member_ids;
		$arrlength = count($members);

		for($x = 0; $x < $arrlength; $x++) {
		    
		    $stmt = $this->conn->prepare("DELETE m FROM members m WHERE m.member_id = ?");
        
			if($stmt)
			{
				$stmt->bind_param("i", $members[$x]);
				$stmt->execute();
				$num_affected_rows = $stmt->affected_rows;
				$stmt->close();
	        } 
			else 
			{
				var_dump($stmt);
				exit;
			}
		}
		return $num_affected_rows > 0;
    }

    public function deleteCommittee($committee_id) 
	{
        $stmt = $this->conn->prepare("DELETE c FROM committees c WHERE c.committee_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("i", $committee_id);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
        } 
		else 
		{
			var_dump($stmt);
			exit;
		}
		return $num_affected_rows > 0;
    }

    public function getCommitteeId($committee_name) 
	{	
		$committeeName = $committee_name;
		
		$stmt = $this->conn->prepare("SELECT c.committee_id FROM committees c WHERE c.name = ?");
        
		if($stmt)
		{
			$stmt->bind_param("s", $committeeName);
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->fetch();
			$stmt->close();
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $id;
    }

    public function deleteAssociation($association_id) 
	{
        $stmt = $this->conn->prepare("DELETE a FROM associations a WHERE a.association_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("i", $association_id);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
        } 
		else 
		{
			var_dump($stmt);
			exit;
		}
		return $num_affected_rows > 0;
    }

    public function getAssociationId($association_name) 
	{	
		$associationName = $association_name;
		
		$stmt = $this->conn->prepare("SELECT a.association_id FROM associations a WHERE a.name = ?");
        
		if($stmt)
		{
			$stmt->bind_param("s", $associationName);
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->fetch();
			$stmt->close();
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $id;
    }

    public function deleteUser($user_id) 
	{
        $stmt = $this->conn->prepare("DELETE u FROM users u WHERE u.user_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("i", $user_id);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
        } 
		else 
		{
			var_dump($stmt);
			exit;
		}
		return $num_affected_rows > 0;
    }

    public function getUserId($user_username) 
	{	
		$username = $user_username;
		
		$stmt = $this->conn->prepare("SELECT u.user_id FROM users u WHERE u.username = ?");
        
		if($stmt)
		{
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->fetch();
			$stmt->close();
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $id;
    }

    public function addUser($user_username, $user_password, $user_role) 
	{
		$username = $this->_cleanUp($user_username);
		
        if (!$this->doesUserExist($username)) 
		{
            // Generating password hash
			$password_hash = password_hash($user_password, PASSWORD_BCRYPT);
		
			$userRole = $this->_cleanUp($user_role);
		
            $stmt = $this->conn->prepare("INSERT INTO users(username, password_hash, user_role) VALUES(?, ?, ?)");
			
			if ($stmt) 
			{
				$stmt->bind_param("sss", $username, $password_hash, $userRole);
				$result = $stmt->execute();
				$stmt->close();
	
				// Check for successful insertion
				if ($result) 
				{
					// User successfully inserted
					return true;
				} 
				else 
				{
					// Failed to add user
					return false;
				}
			} 
			else 
			{
				var_dump($stmt);
				exit;
			}
        } 
		else 
		{
            // User with same username already exists in the db
			return false;
        }
		
		return false;
    }

    private function doesUserExist($user_username) 
	{	
		$username = $this->_cleanUp($user_username);
		
		$stmt = $this->conn->prepare("SELECT u.user_id from users u WHERE u.username = ?");	
		
		if ($stmt) 
		{
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->store_result();
			$num_rows = $stmt->num_rows;
			$stmt->close();	
			
			if($num_rows > 0)
				return true;
			else
				return false;	
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
		
		return false;
    }

    public function addCommittee($c_name, $c_desc) 
	{
		$name = $this->_cleanUp($c_name);
		$description = $this->_cleanUp($c_desc);
		
        if (!$this->doesCommitteeExist($name)) 
		{		
            $stmt = $this->conn->prepare("INSERT INTO committees(name, description) VALUES(?, ?)");
			
			if ($stmt) 
			{
				$stmt->bind_param("ss", $name, $description);
				$result = $stmt->execute();
				$stmt->close();
	
				// Check for successful insertion
				if ($result) 
				{
					// Committee successfully inserted
					return true;
				} 
				else 
				{
					// Failed to add committee
					return false;
				}
			} 
			else 
			{
				var_dump($stmt);
				exit;
			}
        } 
		else 
		{
            // Committee with same name already exists in the db
			return false;
        }
		
		return false;
    }

    private function doesCommitteeExist($c_name) 
	{	
		$name = $this->_cleanUp($c_name);
		
		$stmt = $this->conn->prepare("SELECT c.committee_id FROM committees c WHERE c.name = ?");	
		
		if ($stmt) 
		{
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->store_result();
			$num_rows = $stmt->num_rows;
			$stmt->close();	
			
			if($num_rows > 0)
				return true;
			else
				return false;	
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
		
		return false;
    }

    public function addAssociation($a_name, $a_website) 
	{
		$name = $this->_cleanUp($a_name);
		$website = $this->_cleanUp($a_website);
		
        if (!$this->doesAssociationExist($name)) 
		{		
            $stmt = $this->conn->prepare("INSERT INTO associations(name, website) VALUES(?, ?)");
			
			if ($stmt) 
			{
				$stmt->bind_param("ss", $name, $website);
				$result = $stmt->execute();
				$stmt->close();
	
				// Check for successful insertion
				if ($result) 
				{
					// Association successfully inserted
					return true;
				} 
				else 
				{
					// Failed to add association
					return false;
				}
			} 
			else 
			{
				var_dump($stmt);
				exit;
			}
        } 
		else 
		{
            // Association with same name already exists in the db
			return false;
        }
		
		return false;
    }

    private function doesAssociationExist($a_name) 
	{	
		$name = $this->_cleanUp($a_name);
		
		$stmt = $this->conn->prepare("SELECT a.association_id FROM associations a WHERE a.name = ?");	
		
		if ($stmt) 
		{
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->store_result();
			$num_rows = $stmt->num_rows;
			$stmt->close();	
			
			if($num_rows > 0)
				return true;
			else
				return false;	
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
		
		return false;
    }

    public function updateUser($user_id, $username_new, $user_role) 
	{
		$userId = $this->_cleanUp($user_id);
		$username = $this->_cleanUp($username_new);
		$userRole = $this->_cleanUp($user_role);
		
        $stmt = $this->conn->prepare("UPDATE users u SET u.username = ?, u.user_role = ? WHERE u.user_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("ssi", $username, $userRole, $userId);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
			
			if($num_affected_rows > 0)
				return true;
			else
				return false;
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return false;
    }

    public function updateCommittee($c_id, $c_name, $c_description) 
	{
		$committeeId = $this->_cleanUp($c_id);
		$committeeName = $this->_cleanUp($c_name);
		$committeeDescription = $this->_cleanUp($c_description);
		
        $stmt = $this->conn->prepare("UPDATE committees c SET c.name = ?, c.description = ? WHERE c.committee_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("ssi", $committeeName, $committeeDescription, $committeeId);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
			
			if($num_affected_rows > 0)
				return true;
			else
				return false;
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return false;
    }

    public function updateAssociation($a_id, $a_name, $a_website) 
	{
		$associationId = $this->_cleanUp($a_id);
		$associationName = $this->_cleanUp($a_name);
		$associationWebsite = $this->_cleanUp($a_website);
		
        $stmt = $this->conn->prepare("UPDATE associations a SET a.name = ?, a.website = ? WHERE a.association_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("ssi", $associationName, $associationWebsite, $associationId);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
			
			if($num_affected_rows > 0)
				return true;
			else
				return false;
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return false;
    }

    public function addMemberSingle($m_fname, $m_lname, $m_email, $m_studentnr) 
	{
		$fname = $this->_cleanUp($m_fname);
		$lname = $this->_cleanUp($m_lname);
		$email = $this->_cleanUp($m_email);
		$studentnr = $this->_cleanUp($m_studentnr);
				
        $stmt = $this->conn->prepare("INSERT INTO members(fname, lname, student_nr, email) VALUES(?, ?, ?, ?)");
		
		if ($stmt) 
		{
			$stmt->bind_param("ssis", $fname, $lname, $studentnr, $email);
			$result = $stmt->execute();
			$stmt->close();

			// Check for successful insertion
			if ($result) 
			{
				// Member successfully inserted
				return true;
			} 
			else 
			{
				// Failed to add member
				return false;
			}
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
		
		return false;
    }

    public function getMemberIdByEmail($m_email) 
	{	
		$email = $m_email;
		
		$stmt = $this->conn->prepare("SELECT m.member_id FROM members m WHERE m.email = ?");
        
		if($stmt)
		{
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->fetch();
			$stmt->close();
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $id;
    }

    public function updateMemberSingle($m_id, $m_email, $m_fname, $m_lname, $m_studentNr) 
	{
		$memberId = $this->_cleanUp($m_id);
		$memberEmail = $this->_cleanUp($m_email);
		$memberFname = $this->_cleanUp($m_fname);
		$memberLname = $this->_cleanUp($m_lname);
		$memberStudentNr = $this->_cleanUp($m_studentNr);
		
        $stmt = $this->conn->prepare("UPDATE members m SET m.fname = ?, m.lname = ?, m.student_nr = ?, m.email = ? WHERE m.member_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("ssisi", $memberFname, $memberLname, $memberStudentNr, $memberEmail, $memberId);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
			
			if($num_affected_rows > 0)
				return true;
			else
				return false;
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return false;
    }

	public function getSingleMember($m_id)
	{
		$memberId = $this->_cleanUp($m_id);

        $stmt = $this->conn->prepare("SELECT m.member_id, m.fname, m.lname, m.student_nr, m.email FROM members m WHERE m.member_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("i", $memberId);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($member_id, $fname, $lname, $student_nr, $email);
			
			$response["member"] = array();
						
			while ($stmt->fetch()) 
			{
				$tmp = array();
				$tmp["member_id"] = $member_id;
				$tmp["fname"] = $fname;
				$tmp["lname"] = $lname;
				$tmp["student_nr"] = $student_nr;
				$tmp["email"] = $email;
				array_push($response["member"], $tmp);
			}
			
			$stmt->close();
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $response["member"];
	}

    public function addOfficePeriod($op_date_start, $op_date_end) 
	{
		$dateStart = $this->_cleanUp($op_date_start);
		$dateEnd = $this->_cleanUp($op_date_end);
				
        $stmt = $this->conn->prepare("INSERT INTO office_periods(date_starts, date_ends) VALUES(?, ?)");
		
		if ($stmt) 
		{
			$stmt->bind_param("ss", $dateStart, $dateEnd);
			$result = $stmt->execute();
			$stmt->close();

			// Check for successful insertion
			if ($result) 
			{
				// Committee successfully inserted
				return true;
			} 
			else 
			{
				// Failed to add committee
				return false;
			}
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
		
		return false;
    }

	public function deleteOfficePeriod($period_id) 
	{
        $stmt = $this->conn->prepare("DELETE op FROM office_periods op WHERE op.period_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("i", $period_id);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
        } 
		else 
		{
			var_dump($stmt);
			exit;
		}
		return $num_affected_rows > 0;
    }

	public function getAllOfficePeriods()
	{
        $stmt = $this->conn->prepare("SELECT op.period_id, op.date_starts, op.date_ends FROM office_periods op");
        
		if($stmt)
		{
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($period_id, $date_starts, $date_ends);
			
			$response["periods"] = array();
						
			while ($stmt->fetch()) 
			{
						$tmp = array();
						$tmp["period_id"] = $period_id;
						$tmp["date_starts"] = $date_starts;
						$tmp["date_ends"] = $date_ends;
						array_push($response["periods"], $tmp);
					}
			
			$stmt->close();
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
        return $response["periods"];
	}

    public function updateOfficePeriod($period_id, $date_start, $date_end) 
	{
		$period_id = $this->_cleanUp($period_id);
		$date_start = $this->_cleanUp($date_start);
		$date_end = $this->_cleanUp($date_end);
		
        $stmt = $this->conn->prepare("UPDATE office_periods op SET op.date_starts = ?, op.date_ends = ? WHERE op.period_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("ssi", $date_start, $date_end, $period_id);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
			
			if($num_affected_rows > 0)
				return true;
			else
				return false;
		}
		else 
		{
			var_dump($stmt);
			exit;
		}
        return false;
    }

    public function addMembershipNormalPeriodCommittee($ms_member_id, $ms_period_id, $ms_committee_id) 
	{
		$memberId = $this->_cleanUp($ms_member_id);
		$periodId = $this->_cleanUp($ms_period_id);
		$committeeId = $this->_cleanUp($ms_committee_id);
	
        $stmt = $this->conn->prepare("INSERT INTO memberships(member_id, period_id, committee_id) VALUES(?, ?, ?)");
		
		if ($stmt) 
		{
			$stmt->bind_param("iii", $memberId, $periodId, $committeeId);
			$result = $stmt->execute();
			$stmt->close();

			// Check for successful insertion
			if ($result) 
			{
				// addMembershipNormalPeriodCommittee successfully inserted
				return true;
			} 
			else 
			{
				// Failed to add addMembershipNormalPeriodCommittee
				return false;
			}
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
		
		return false;
    }

    public function addMembershipNormalPeriodCommitteeStuRa($ms_member_id, $ms_period_id, $ms_committee_id, $ms_opt_ass_id_stura) 
	{
		$memberId = $this->_cleanUp($ms_member_id);
		$periodId = $this->_cleanUp($ms_period_id);
		$committeeId = $this->_cleanUp($ms_committee_id);
		$associationId = $this->_cleanUp($ms_opt_ass_id_stura);
	
        $stmt = $this->conn->prepare("INSERT INTO memberships(member_id, period_id, committee_id, association_id) VALUES(?, ?, ?, ?)");
		
		if ($stmt) 
		{
			$stmt->bind_param("iiii", $memberId, $periodId, $committeeId, $associationId);
			$result = $stmt->execute();
			$stmt->close();

			// Check for successful insertion
			if ($result) 
			{
				// addMembershipNormalPeriodCommitteeStuRa successfully inserted
				return true;
			} 
			else 
			{
				// Failed to add addMembershipNormalPeriodCommitteeStuRa
				return false;
			}
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
		
		return false;
    }

    public function addMembershipCustomPeriodCommittee($ms_member_id, $ms_custom_reason, $ms_custom_date_start, $ms_custom_date_end, $ms_committee_id) 
	{
		$memberId = $this->_cleanUp($ms_member_id);
		$customReason = $this->_cleanUp($ms_custom_reason);
		$customDateStart = $this->_cleanUp($ms_custom_date_start);
		$customDateEnd = $this->_cleanUp($ms_custom_date_end);
		$committeeId = $this->_cleanUp($ms_committee_id);

		$successorId = $this->addSuccessorEntry($customReason, $memberId);

		if($successorId != '0')
		{
		
	        $stmt = $this->conn->prepare("INSERT INTO memberships(date_started, date_ended, member_id, committee_id, successor_id) VALUES(?, ?, ?, ?, ?)");
			
			if ($stmt) 
			{
				$stmt->bind_param("ssiii", $customDateStart, $customDateEnd, $memberId, $committeeId, $successorId);
				$result = $stmt->execute();
				$stmt->close();

				// Check for successful insertion
				if ($result) 
				{
					// addMembershipCustomPeriodCommittee successfully inserted
					return true;
				} 
				else 
				{
					// Failed to add addMembershipCustomPeriodCommittee
					return false;
				}
			} 
			else 
			{
				var_dump($stmt);
				exit;
			}
		}
		else 
		{
			return false;
		}
		
		return false;
    }

    public function addMembershipCustomPeriodCommitteeStuRa($ms_member_id, $ms_custom_reason, $ms_custom_date_start, $ms_custom_date_end, $ms_committee_id, $ms_opt_ass_id_stura) 
	{
		$memberId = $this->_cleanUp($ms_member_id);
		$customReason = $this->_cleanUp($ms_custom_reason);
		$customDateStart = $this->_cleanUp($ms_custom_date_start);
		$customDateEnd = $this->_cleanUp($ms_custom_date_end);
		$committeeId = $this->_cleanUp($ms_committee_id);
		$associationId = $this->_cleanUp($ms_opt_ass_id_stura);

		$successorId = $this->addSuccessorEntry($customReason, $memberId);

		if($successorId != '0')
		{
	
	        $stmt = $this->conn->prepare("INSERT INTO memberships(date_started, date_ended, member_id, committee_id, association_id, successor_id) VALUES(?, ?, ?, ?, ?, ?)");
			
			if ($stmt) 
			{
				$stmt->bind_param("ssiiii", $customDateStart, $customDateEnd, $memberId, $committeeId, $associationId, $successorId);
				$result = $stmt->execute();
				$stmt->close();

				// Check for successful insertion
				if ($result) 
				{
					// addMembershipCustomPeriodCommitteeStuRa successfully inserted
					return true;
				} 
				else 
				{
					// Failed to add addMembershipCustomPeriodCommitteeStuRa
					return false;
				}
			} 
			else 
			{
				var_dump($stmt);
				exit;
			}
		}
		else 
		{
			return false;
		}
		
		return false;
    }

    public function addMembershipNormalPeriodAssociation($ms_member_id, $ms_period_id, $ms_association_id) 
	{
		$memberId = $this->_cleanUp($ms_member_id);
		$periodId = $this->_cleanUp($ms_period_id);
		$associationId = $this->_cleanUp($ms_association_id);
	
        $stmt = $this->conn->prepare("INSERT INTO memberships(member_id, period_id, association_id) VALUES(?, ?, ?)");
		
		if ($stmt) 
		{
			$stmt->bind_param("iii", $memberId, $periodId, $associationId);
			$result = $stmt->execute();
			$stmt->close();

			// Check for successful insertion
			if ($result) 
			{
				// addMembershipNormalPeriodAssociation successfully inserted
				return true;
			} 
			else 
			{
				// Failed to add addMembershipNormalPeriodAssociation
				return false;
			}
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
		
		return false;
    }

    public function addMembershipCustomPeriodAssociation($ms_member_id, $ms_custom_reason, $ms_custom_date_start, $ms_custom_date_end, $ms_association_id) 
	{
		$memberId = $this->_cleanUp($ms_member_id);
		$customReason = $this->_cleanUp($ms_custom_reason);
		$customDateStart = $this->_cleanUp($ms_custom_date_start);
		$customDateEnd = $this->_cleanUp($ms_custom_date_end);
		$associationId = $this->_cleanUp($ms_association_id);

		$successorId = $this->addSuccessorEntry($customReason, $memberId);

		if($successorId != '0')
		{
	        $stmt = $this->conn->prepare("INSERT INTO memberships(date_started, date_ended, member_id, association_id, successor_id) VALUES(?, ?, ?, ?, ?)");
			
			if ($stmt) 
			{
				$stmt->bind_param("ssiii", $customDateStart, $customDateEnd, $memberId, $associationId, $successorId);
				$result = $stmt->execute();
				$stmt->close();

				// Check for successful insertion
				if ($result) 
				{
					// addMembershipCustomPeriodAssociation successfully inserted
					return true;
				} 
				else 
				{
					// Failed to add addMembershipCustomPeriodAssociation
					return false;
				}
			} 
			else 
			{
				var_dump($stmt);
				exit;
			}
		}
		else 
		{
			// Failed to add addSuccessorEntry
			return false;
		}
		
		return false;
    }

    public function addSuccessorEntry($s_reason, $s_member_id) 
	{
		$memberId = $this->_cleanUp($s_member_id);
		$reason = $this->_cleanUp($s_reason);
				
        $stmt = $this->conn->prepare("INSERT INTO successors(reason, member_id) VALUES(?, ?)");
		
		if ($stmt) 
		{
			$stmt->bind_param("si", $reason, $memberId);
			$result = $stmt->execute();
			$successorId = $stmt->insert_id;
			$stmt->close();

			// Check for successful insertion
			if ($result) 
			{
				// addSuccessorEntry successfully inserted
				return $successorId;
			} 
			else 
			{
				// Failed to add addSuccessorEntry
				return 0;
			}
		} 
		else 
		{
			var_dump($stmt);
			exit;
		}
		
		return 0;
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

	public function deleteMembershipSingle($membership_id) 
	{
        $stmt = $this->conn->prepare("DELETE ms FROM memberships ms WHERE ms.membership_id = ?");
        
		if($stmt)
		{
			$stmt->bind_param("i", $membership_id);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
        } 
		else 
		{
			var_dump($stmt);
			exit;
		}
		return $num_affected_rows > 0;
    }

    public function deleteMembershipMultiple($membership_ids) 
	{
		$memberships = $membership_ids;
		$arrlength = count($memberships);

		for($x = 0; $x < $arrlength; $x++) {
		    
		    $stmt = $this->conn->prepare("DELETE ms FROM memberships ms WHERE ms.membership_id = ?");
        
			if($stmt)
			{
				$stmt->bind_param("i", $memberships[$x]);
				$stmt->execute();
				$num_affected_rows = $stmt->affected_rows;
				$stmt->close();
	        } 
			else 
			{
				var_dump($stmt);
				exit;
			}
		}
		return $num_affected_rows > 0;
    }
}
?>
