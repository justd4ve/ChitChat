<?php

class Rooms extends Model {

    function all() {
        $stmt = $this->db->query('SELECT ROW_NUMBER () OVER (ORDER BY created) as row, rooms.*, users.login, users.email FROM rooms JOIN users ON (id_users_owner=id_users) ORDER BY created');
        return $stmt->fetchAll();
    }
    
    function find($id) {
        $stmt = $this->db->prepare('SELECT * FROM rooms WHERE id_rooms=:id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
	
	function getRoomsInfo() {
			$stmt = $this->db->query('SELECT title, lock FROM rooms ORDER BY created');
			return $stmt->fetchAll();		
    }	
	
	function getRoomInfo($id) {		
			$stmt = $this->db->prepare('SELECT title, lock, id_users_owner FROM rooms WHERE id_rooms=:id');
			$stmt->bindValue(':id', $id);
			$stmt->execute();
			return $stmt->fetchAll();
    }
	
	//seznam mistnosti uzivatele + pocet zprav od jeho posledni navstevy
	function getRoomsByUser($id){
		$stmt = $this->db->prepare('SELECT in_room.id_rooms, title, poc.count FROM in_room
			LEFT JOIN rooms USING (id_rooms) 
			LEFT JOIN (SELECT s.id_rooms, COUNT(messages.created) FROM messages RIGHT JOIN
			(
			SELECT id_rooms, last_entry FROM in_room JOIN rooms USING (id_rooms)
			WHERE (id_users = :id) ORDER BY last_entry DESC
			) s USING (id_rooms)
			WHERE (id_users_from <> :id AND (messages.created > s.last_entry))
			GROUP BY s.id_rooms) poc ON (in_room.id_rooms=poc.id_rooms) WHERE id_users=:id ORDER BY title');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
	}
    
    function add($title, $idOwner, $lang, $lock = 0) {
        $stmt = $this->db->prepare('INSERT INTO rooms (created, title, id_users_owner, lock, language) VALUES (NOW(), :t, :id, :lo, :la)');
        $stmt->bindValue(':t',$title);
        $stmt->bindValue(':id', $idOwner);
        $stmt->bindValue(':lo', $lock);
		if($lang==''){
			$lang=NULL;
		}
        $stmt->bindValue(':la', $lang);		
		$stmt->execute(); 
		$idRoom = $this->db->lastInsertId('rooms_id_rooms_seq');
		$stmt = $this->db->prepare('INSERT INTO in_room (id_users, id_rooms, last_message, entered, last_entry) VALUES(
		:id, :rid , NOW(), NOW(), NOW())');
		$stmt->bindValue(':id', $idOwner);
        $stmt->bindValue(':rid', $idRoom);
        return $stmt->execute();        
    }
	
	function renameRoom($id, $title){
		$stmt = $this->db->prepare('UPDATE rooms SET title=:t WHERE (id_rooms=:id)');
		$stmt->bindValue(':t', $title);
		$stmt->bindValue(':id', $id);
        $stmt->execute();        
	}
	
	function lockRoom($id, $user){
		$stmt = $this->db->prepare('SELECT id_users_owner FROM rooms WHERE id_rooms=:id');
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		$owner = $stmt->fetch();
		if($user == $owner['id_users_owner']){
			$stmt = $this->db->prepare('UPDATE rooms SET lock=NOT lock WHERE (id_rooms=:id)');
			$stmt->bindValue(':id', $id);
			$stmt->execute();		
			return true;
		}
		return false;
	}
	
	function addUser($room, $user){
        $stmt = $this->db->prepare('INSERT INTO in_room VALUES (:u, :r, NOW(), NOW(), NOW())');
        $stmt->bindValue(':r', $room);
        $stmt->bindValue(':u', $user);
        $stmt->execute();   
	}   
	
	function kickUser($room, $user){
        $stmt = $this->db->prepare('DELETE FROM in_room WHERE (id_rooms=:r AND id_users=:u)');
        $stmt->bindValue(':r', $room);
        $stmt->bindValue(':u', $user);
        $stmt->execute();   	
		
		$stmt = $this->db->prepare('SELECT COUNT(*) FROM room_kick WHERE (id_users = :u AND id_rooms= :r)');
		$stmt->bindValue(':r', $room);
        $stmt->bindValue(':u', $user);
        $stmt->execute();   
		$count = $stmt->fetch();
		if($count['count']==1){
			$stmt = $this->db->prepare('UPDATE room_kick SET id_users = :u, id_rooms=:r, created=NOW() WHERE (id_users = :u AND id_rooms=:r)');
			$stmt->bindValue(':r', $room);
			$stmt->bindValue(':u', $user);
			$stmt->execute();
		}else{		
			$stmt = $this->db->prepare('INSERT INTO room_kick VALUES (:u,:r, NOW())');
			$stmt->bindValue(':r', $room);
			$stmt->bindValue(':u', $user);
			$stmt->execute();   		
		}		
	}
	
	function cleanUp(){
		$stmtQ = $this->db->query("SELECT id_rooms FROM in_room 
			LEFT JOIN (SELECT id_rooms, MAX(created) lmsg FROM messages GROUP BY id_rooms) msgs USING (id_rooms)
			GROUP BY id_rooms, msgs.lmsg
			HAVING (12<=(DATE_PART('day', NOW() - GREATEST(MAX(last_entry), msgs.lmsg)) * 24 + DATE_PART('hour', NOW() - GREATEST(MAX(last_entry), msgs.lmsg))))");       	
		$erased = $stmtQ->fetchAll();	

		if(sizeof($erased)>0){
			$params = substr(str_repeat("?,", sizeof($erased)), 0, -1);
			$this->db->beginTransaction();
			$stmt0 = $this->db->prepare("DELETE FROM room_kick WHERE id_rooms IN ($params)");		
			$stmt1 = $this->db->prepare("DELETE FROM in_room WHERE id_rooms IN ($params)");		
			$stmt2 = $this->db->prepare("DELETE FROM messages WHERE id_rooms IN ($params)");		
			$stmt3 = $this->db->prepare("DELETE FROM rooms WHERE id_rooms IN ($params)");		
			for($i = 0; $i < sizeof($erased); $i++) {
				$stmt0->bindValue($i+1, implode($erased[$i]));			
				$stmt1->bindValue($i+1, implode($erased[$i]));			
				$stmt2->bindValue($i+1, implode($erased[$i]));			
				$stmt3->bindValue($i+1, implode($erased[$i]));			
			}		
			$stmt0->execute();
			$stmt1->execute();
			$stmt2->execute();
			$stmt3->execute();
			$this->db->commit();
		}
	}
	
	function cleanEmptyRooms(){
		$stmt = $this->db->query('(SELECT id_rooms FROM rooms) EXCEPT (SELECT DISTINCT(id_rooms) FROM in_room)');
		$stmt->execute();
		$emptyRooms = $stmt->fetchAll();
		if(sizeof($emptyRooms)>0){
			$params = substr(str_repeat("?,", sizeof($emptyRooms)), 0, -1);
			$this->db->beginTransaction();
			$stmt0 = $this->db->prepare("DELETE FROM room_kick WHERE id_rooms IN ($params)");			
			$stmt1 = $this->db->prepare("DELETE FROM messages WHERE id_rooms IN ($params)");		
			$stmt2 = $this->db->prepare("DELETE FROM rooms WHERE id_rooms IN ($params)");					
			for($i = 0; $i < sizeof($emptyRooms); $i++) {
				$stmt0->bindValue($i+1, implode($emptyRooms[$i]));					
				$stmt1->bindValue($i+1, implode($emptyRooms[$i]));			
				$stmt2->bindValue($i+1, implode($emptyRooms[$i]));			
			}		
			$stmt0->execute();
			$stmt1->execute();
			$stmt2->execute();
			$this->db->commit();
		}
	}
}
