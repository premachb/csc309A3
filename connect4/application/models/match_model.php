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
		$counter = 0;
		//current number of links

		// Check verticals for current user victory
		for($i = 0; $i < 7; $i++){
			for($j = 0; $j < 6; $j++){
				if($board[$i][$j] == $lastUser){
					$counter++;
				}
				else{
					$counter = 0;
				}

				if($counter == 4){
					return $lastUser;
				}
			}

			if($counter < 4){
				$counter = 0;
			}
		}

		// Check Horizonatals for current user victory
		for($i = 0; $i < 6; $i++){
			for($j = 0; $j < 7; $j++){
				if($board[$j][$i] == $lastUser){
					$counter++;
				}
				else{
					$counter = 0;
				}

				if($counter == 4){
					return $lastUser;
				}
			}	
			if($counter < 4){
				$counter = 0;
			}		
		}
		
		//first set of Diagonals, starting from [0, 1] to [0, 6], going right down
		
		for ($j = 1; $j < 7 ; $j++){ 
			for ($i = 0; $i < 7 - $j; $i++){

				if($board[$i + $j][$i] == $lastUser){
					$counter++;
				}
				else{
					$counter = 0;
				}

				if($counter == 4){
					return $lastUser;
				}
			}	
			if($counter < 4){
				$counter = 0;
			}	
		}
		
		//second set of Diagonals, starting from [0, 0] to [5, 0] going right down
	
		for ($j = 0; $j < 6 ; $j++){ 
			for ($i = 0; $i < 7 - $j - 1; $i++){

				if($board[$i][$i + $j] == $lastUser){
					$counter++;
				}
				else{
					$counter = 0;
				}

				if($counter == 4){
					return $lastUser;
				}
			}	
			if($counter < 4){
				$counter = 0;
			}	
		}
	
		//third set of Diagonals, starting from [0, 0] to [6, 0] going upper right
	
		for ($j = 0; $j < 6 ; $j++){ 
			for ($i = 0; $i < $j + 1; $i++){
				if($board[$i][$j - $i] == $lastUser){
					$counter++;
				}
				else{
					$counter = 0;
				}

				if($counter == 4){
					return $lastUser;
				}
			}	
			if($counter < 4){
				$counter = 0;
			}	
		}
	
		//Fourth set of Diagonals, starting from [1, 5] to [6, 5] going upper right
	
		for ($j = 1; $j < 7 ; $j++){ 
			for ($i = 0; $i < 7 - $j; $i++){
				if($board[5 - $i][$j - $i] == $lastUser){
					$counter++;
				}
				else{
					$counter = 0;
				}

				if($counter == 4){
					return $lastUser;
				}
			}	
			if($counter < 4){
				$counter = 0;
			}	
		}

		
		return null;
	}

}
?>		