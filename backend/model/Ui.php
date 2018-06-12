<?php

class Ui extends Model {

    function getLayoutLabels($la, $comp) {
		if($la == 'en'){			
			$stmt = $this->db->prepare('SELECT id_ui, text_en as text FROM ui WHERE UPPER(component) = UPPER(:co) ORDER BY id_ui');
		}else{
			$stmt = $this->db->prepare('SELECT id_ui, COALESCE(text_cz,text_en) as text FROM ui WHERE component=:co ORDER BY id_ui');	
		}
		$stmt->bindValue(':co', $comp);
		$stmt->execute();
        return $stmt->fetchAll();
    }
}
