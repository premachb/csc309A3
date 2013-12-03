<?php
class Match_model extends CI_Model {
	
	function getExclusive($id)
	{
		$sql = "select * from `match` where id=? for update";
		$query = $this->db->query($sql,array($id));
		if ($query && $query->num_rows() > 0)
			return $query->row(0,'Match');
		else
			return null;
	}

	function get($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('match');
		if ($query && $query->num_rows() > 0)
			return $query->row(0,'Match');
		else
			return null;
	}
	
	
	function insert($match) {
		return $this->db->insert('match',$match);
	}
	
	
	function updateMsgU1($id,$msg) {
		$this->db->where('id',$id);
		return $this->db->update('match',array('u1_msg'=>$msg));
	}
	
	function updateMsgU2($id,$msg) {
		$this->db->where('id',$id);
		return $this->db->update('match',array('u2_msg'=>$msg));
	}
	
	function updateStatus($id, $status) {
		$this->db->where('id',$id);
		return $this->db->update('match',array('match_status_id'=>$status));
	}

	function getMatchState($id){
		$query = $this->db->query('select board_state from `match` where id = ' . $id);
		$this->load->database();

		if($query->num_rows > 0){
			foreach($query->result() as $row){
				return $row->board_state;
			}
		}
		else{
			return "no board";
		}
	}

	function updateMatchState($id, $board_state){
		$this->db->where('id',$id);
		return $this->db->update('match', array('board_state'=>$board_state));
	}

	function checkForWin($board, $lastChip, $lastUser){
		// The idea behind this algorithm is to not check the whole board each time
		// Only check the region in which the last chip was placed (Check horizontally, vertically and diagonally)

		$counter = 0;

		// Check 3 spots in both vertical directions
		for($i = intval($lastChip[1]); $i < intval($lastChip[1]) + 4; $i++){
			if(isset($board[intval($lastChip[0])][$i])){
				if($board[intval($lastChip[0])][$i] == $lastUser){
					$counter++;
				}
				else{
					// No match
					$counter = 0;
					break;
				}
			}	
			else{
				$counter = 0;
				break;
			}

			if($counter == 4){
				return $lastUser;
			}
		}

		for($i = intval($lastChip[1]); $i > intval($lastChip[1]) - 4; $i--){
			if(isset($board[intval($lastChip[0])][$i])){
				if($board[intval($lastChip[0])][$i] == $lastUser){
					$counter++;
				}
				else{
					$counter = 0;
					break;
				}
			}	
			else{
				$counter = 0;
				break;
			}

			if($counter == 4){
				return $lastUser;
			}
		}

		// HORIZONTAL CHECK 
		// Check 3 spots right
		for($i = intval($lastChip[0]); $i < intval($lastChip[0]) + 4; $i++){
			if(isset($board[$i][intval($lastChip[1])])){
				if($board[$i][intval($lastChip[1])] == $lastUser){
					$counter++;
				}
				else{
					$counter = 0;
					break;
				}
			}
			else{
				$counter = 0;
				break;
			}

			if($counter == 4){
				return $lastUser;
			}
		}

		// Check 3 spots left
		for($i = intval($lastChip[0]); $i > intval($lastChip[0]) - 4; $i--){
			if(isset($board[$i][intval($lastChip[1])])){
				if($board[$i][intval($lastChip[1])] == $lastUser){
					$counter++;
				}
				else{
					$counter = 0;
					break;
				}
			}
			else{
				$counter = 0;
				break;
			}

			if($counter == 4){
				return $lastUser;
			}
		}

		return null;
		
	}

	function isPlayersTurn($id){

	}
	
}
?>		