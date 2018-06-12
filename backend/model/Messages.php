<?php

class Messages extends Model {
   
    function getMessages($room, $user) {
        $stmt = $this->db->prepare("SELECT id_messages, id_users_from, id_users_to, 
		TO_CHAR(created, 'DD-MM-YYYY HH24:MI:SS') created_formated, message, u1.login login_from, u2.login login_to FROM messages 
		JOIN users u1 ON (id_users_from=u1.id_users) LEFT JOIN users u2 ON (id_users_to=u2.id_users) WHERE ((id_rooms=:r) AND (id_users_from=:u OR id_users_to=:u)) ORDER BY created DESC");
        $stmt->bindValue(':r', $room);
        $stmt->bindValue(':u', $user);
        $stmt->execute();
        return $stmt->fetchAll();
    }
	
	function add($room, $sender, $message, $receiver) {
        $stmtP = $this->db->query('SELECT MAX(id_messages) as sum FROM messages');
        $id = $stmtP->fetch();
        $stmt = $this->db->prepare('INSERT INTO messages (id_messages, id_rooms, id_users_from, id_users_to, created, message) VALUES (:id, :r, :s, :to, NOW(), :m)');
        $stmt->bindValue(':id', $id['sum']+1);
        $stmt->bindValue(':r', $room);
        $stmt->bindValue(':s', $sender);			
        $stmt->bindValue(':to', $receiver);					
        $stmt->bindValue(':m', $message);		
        $stmt->execute();		
		$stmt = $this->db->prepare('UPDATE in_room SET last_message=NOW() WHERE (id_users=:u AND id_rooms=:r)');
        $stmt->bindValue(':r', $room);		
        $stmt->bindValue(':u', $sender);	
        return $stmt->execute();			
    }

}