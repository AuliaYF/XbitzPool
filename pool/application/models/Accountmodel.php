<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccountModel extends CI_Model {

	private $tableName = "app_account";
	public function __construct()
	{
		parent::__construct();
	}

	public function getPoolStat($timeStart, $timeEnd){
		return $this->db->query("SELECT (SELECT COUNT(*) FROM (SELECT * FROM app_account_claim WHERE createdTime BETWEEN '$timeStart' AND '$timeEnd' GROUP BY accountId) x1) activeWorker, (SELECT SUM(x1.claimQty) FROM app_account_claim x1 WHERE x1.createdTime BETWEEN '$timeStart' AND '$timeEnd') totalClaim, (SELECT SUM(x1.claimMrai) FROM app_account_claim x1 WHERE x1.createdTime BETWEEN '$timeStart' AND '$timeEnd') totalMrai")->row();
	}

	public function populatePaylist($timeStart = NULL, $timeEnd = NULL){
		if(NULL == $timeStart && NULL == $timeEnd)
			return $this->db->query("SELECT t1.accountId, t1.accountName, t1.accountAddress, t1.accountThreshold, t1.accountBalance, (SELECT COUNT(*) FROM pending_claims x1 WHERE x1.accountId = t1.accountId AND DATE(x1.claimTime) = '" . date("Y-m-d") . "') totalClaim FROM app_account t1 ORDER BY 1")->result();
		else
			return $this->db->query("SELECT t1.accountId, t1.accountName, t1.accountAddress, t1.accountThreshold, t1.accountBalance, (SELECT COUNT(*) FROM pending_claims x1 WHERE x1.accountId = t1.accountId AND x1.claimTime BETWEEN '$timeStart' AND '$timeEnd' AND x1.claimStatus = 'd') totalClaim FROM app_account t1 ORDER BY 6 DESC")->result();
	}

	public function countMember($timeStart = NULL, $timeEnd = NULL){
		if(NULL === $timeStart && NULL === $timeEnd)
			return $this->db->query("SELECT accountId FROM pending_claims WHERE DATE(claimTime) = '" . date("Y-m-d") . "' GROUP BY accountId")->result();
		else
			return $this->db->query("SELECT accountId FROM pending_claims WHERE claimTime BETWEEN '$timeStart' AND '$timeEnd' AND claimStatus = 'd' GROUP BY accountId")->result();
	}

	public function countClaims($timeStart = NULL, $timeEnd = NULL){
		if(NULL === $timeStart && NULL === $timeEnd)
			return $this->db->query("SELECT COUNT(*) total FROM pending_claims WHERE DATE(claimTime) = '" . date("Y-m-d") . "'")->row()->total;
		else
			return $this->db->query("SELECT COUNT(*) total FROM pending_claims WHERE claimTime BETWEEN '$timeStart' AND '$timeEnd' AND claimStatus = 'd'")->row()->total;
	}

	public function isUsernameExist($username){
		return $this->db->where('accountName', $username)->get($this->tableName)->row();
	}

	public function isAccountExist($address){
		return $this->db->where('accountAddress', $address)->get($this->tableName)->row();
	}

	public function registerAccount($address, $fullname){
		return $this->db->insert($this->tableName, array('accountName' => $fullname, 'accountAddress' => $address, 'accountThreshold' => 50, 'accountStatus' => 't', 'createdTime' => date("Y-m-d H:i:s")));
	}

	public function storeClaim($address, $claimQty){
		$res = $this->isAccountExist($address);
		if($claimQty > 0){
			$this->db->insert('claims_history', array('accountId' => $res->accountId, 'claimQty' => $claimQty, 'claimTime' => date("Y-m-d H:i:s")));
		}
	}

	public function getTotalClaim($address, $timeStart = NULL, $timeEnd = NULL){
		$res = $this->isAccountExist($address);
		if(NULL === $timeStart && NULL === $timeEnd)
			return $this->db->query("SELECT COUNT(*) total FROM pending_claims WHERE accountId = '{$res->accountId}' AND DATE(claimTime) = '" . date("Y-m-d") . "'")->row()->total;
		else
			return $this->db->query("SELECT COUNT(*) total FROM pending_claims WHERE accountId = '{$res->accountId}' AND claimTime BETWEEN '$timeStart' AND '$timeEnd' AND claimStatus = 'd'")->row()->total;
	}
}

/* End of file Accountmodel.php */
/* Location: ./application/models/Accountmodel.php */