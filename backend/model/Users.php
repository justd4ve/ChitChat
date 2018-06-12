<?php

class Users extends Model {
	
    function getUserByLogin($login) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE login = :l');
        $stmt->bindValue(':l', $login);
        $stmt->execute();
        return $stmt->fetch();
    }
	 
	//uzivatel + mistnosti(admin+nonadmin)
    function getUserById($id) {
		$stmt = $this->db->prepare("SELECT id_users, login, email, name, surname, gender,
		TO_CHAR(registered,'DD. MM. YYYY') regist, poc, COALESCE(pocA,0) pocA FROM users 
		LEFT JOIN (
			SELECT id_users, COUNT(id_users) poc FROM in_room GROUP BY id_users
		) vse USING (id_users) 
		LEFT JOIN(
			SELECT id_users_owner, COUNT(id_users_owner) pocA FROM rooms GROUP BY id_users_owner
		) adm ON (users.id_users = adm.id_users_owner) WHERE id_users = :id");
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetch();
    }
	
	function editUser($name, $surname, $gender, $id){
		$stmt = $this->db->prepare("UPDATE users SET name = :na, surname = :su, gender = :ge WHERE id_users = :id");
		$stmt->bindValue(':na', $name);
        $stmt->bindValue(':su', $surname);   
        $stmt->bindValue(':ge', $gender);   
        $stmt->bindValue(':id', $id);   
		$stmt->execute();
	}
	 
    function verify($login, $pass){
		$user = $this->getUserByLogin($login);
		if($user){
			if(password_verify($pass, $user['password'])) {
				return $user;
			}
		}
		return null;
	}
	
	//pridat uzivatele do mistnosti, pripadne upravit jeho cas posledniho vstupu
	function addRoomUser($room, $user) {
		$stmtK = $this->db->prepare("SELECT created FROM room_kick WHERE (10>=(DATE_PART('day', NOW() - created) * 24 + 
			DATE_PART('hour', NOW() - created) * 60 +
		    DATE_PART('minute', NOW() - created)) AND id_users=:u AND id_rooms=:r)");
		$stmtK->bindValue(':r', $room);
		$stmtK->bindValue(':u', $user);   
		$stmtK->execute();   		
		$kick = $stmtK->fetch();
		if ($kick['created']<>null){
			return true;
		}
		$stmtE = $this->db->prepare('SELECT COUNT(*) FROM in_room WHERE (id_users=:u AND id_rooms =:r)');
        $stmtE->bindValue(':r', $room);
        $stmtE->bindValue(':u', $user);   
        $stmtE->execute();   	
		$in = $stmtE->fetch();
		if ($in['count'] < 1){
			$stmtV = $this->db->prepare('SELECT lock FROM rooms WHERE id_rooms=:r');
			$stmtV->bindValue(':r', $room);
			$stmtV->execute();
			$lock = $stmtV->fetch();
			
			if($lock['lock']==true){
				return true;
			}else{
				$stmt = $this->db->prepare('INSERT INTO in_room (id_rooms, id_users, last_message, entered, last_entry) VALUES (:r, :u, NOW(), NOW(), NOW())');
				$stmt->bindValue(':r', $room);
				$stmt->bindValue(':u', $user);   
				$stmt->execute();		
				return false;
			}
		}else{
			$stmt = $this->db->prepare('UPDATE in_room SET last_entry=NOW() WHERE (id_users=:u AND id_rooms=:r)');
			$stmt->bindValue(':r', $room);
			$stmt->bindValue(':u', $user);   
			$stmt->execute();
			return false;
		}       
    }
	
	//seznam osob v mistnosti
	function getUsersByRoom($id){
		$stmt = $this->db->prepare('SELECT id_users, login FROM in_room JOIN users USING (id_users) WHERE id_rooms=:id ORDER BY entered');
		$stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
	}
	
	function leaveRoom($room, $user){
		//vyhledani id admina mistnosti + pocet lidi v ni
		$stmtA = $this->db->prepare('SELECT id_users_owner, sub.count FROM rooms JOIN (
		SELECT id_rooms, COUNT(id_rooms) FROM in_room WHERE id_rooms=:rid GROUP BY id_rooms) sub USING (id_rooms)
		WHERE id_rooms=:rid');
		$stmtA->bindValue(':rid', $room);	
		$stmtA->execute();
		$in = $stmtA->fetch();
		//v pripade ze je adminem probehne vyber noveho(entered)
		if ($in['id_users_owner'] == $user){		
			//jestli je v mistnosti aspon 1 clovek, nahrad admina a smaz puvodniho, jinak smaz mistnost
			if($in['count'] > 1){							
				$stmtB = $this->db->prepare('UPDATE rooms SET id_users_owner = (SELECT id_users FROM in_room WHERE id_rooms=:rid ORDER BY entered LIMIT 2 OFFSET 1) WHERE id_rooms=:rid');
				$stmtB->bindValue(':rid', $room);	
				$stmtB->execute();			
			}else{
				try{
					$stmt = $this->db->prepare('DELETE FROM in_room WHERE id_rooms=:id');
					$stmt->bindValue(':id', $room);
					$stmt->execute();echo $room; echo $user;
					$stmt = $this->db->prepare('DELETE FROM room_kick WHERE id_rooms=:id');
					$stmt->bindValue(':id', $room);
					$stmt->execute();echo $room; echo $user;
					$stmt = $this->db->prepare('DELETE FROM messages WHERE id_rooms=:id');
					$stmt->bindValue(':id', $room);
					$stmt->execute();
					$stmt = $this->db->prepare('DELETE FROM rooms WHERE id_rooms=:id');
					$stmt->bindValue(':id', $room);
					return $stmt->execute();
				}catch(Exception $ex){
					$this->logger->error($ex->getMessage());
					exit($ex->getMessage());      
				}
			}
		}
		$stmt = $this->db->prepare('DELETE FROM in_room WHERE (id_users=:uid AND id_rooms=:rid)');
		$stmt->bindValue(':rid', $room);
		$stmt->bindValue(':uid', $user);   
		$stmt->execute();
		return $stmt->execute(); 
	}
	
	//kteri nejsou v mistnosti
	function getOtherUsers($room){
		$stmt = $this->db->prepare('SELECT id_users, login, email, name, surname FROM users WHERE id_users NOT IN (SELECT id_users FROM in_room WHERE id_rooms=:r)');
		$stmt->bindValue(':r', $room);
		$stmt->execute();
        return $stmt->fetchAll();
	}
	
	function cleanUp(){
		$stmtQ = $this->db->query("SELECT id_rooms, id_users FROM in_room 
			GROUP BY id_rooms, id_users
			HAVING (1<=(DATE_PART('day', NOW() - GREATEST(MAX(last_message),MAX(last_entry))) * 24 
			+ DATE_PART('hour', NOW() - GREATEST(MAX(last_message),MAX(last_entry))) * 60))");
		$erased = $stmtQ->fetchAll();	

		if(sizeof($erased)>0){
			$params = substr(str_repeat("(id_rooms = ? AND id_users = ?) OR ", sizeof($erased)), 0, -4);
			echo $params;
			$stmt = $this->db->prepare("DELETE FROM in_room WHERE ($params)");	
			$j = 1;
			for($i = 0; $i < sizeof($erased); $i++) {
				$stmt->bindValue($j, implode((array) $erased[$i]['id_rooms']));
				$stmt->bindValue($j+1, implode((array) $erased[$i]['id_users']));
				$j+=2;
			}		
			$stmt->execute();
		}
	}
     
}
