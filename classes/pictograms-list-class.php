<?php
require_once "dbconnection-class.php";
class pictogramsService{

    //------------------------------------ Count pictograms Pagination -------------------------------------
    public function countPictogram(){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT COUNT(id) FROM pictograms"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END------------------------------------------------------

    //------------------------------------------List pictograms---------------------------------------------
    public function listPictogram($start, $limit){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT id, alt, caption, type FROM pictograms ORDER BY id DESC LIMIT $start, $limit"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //------------------------------------------Update pictograms------------------------------------------
    public function updatePictogram($pictogram_alt, $pictograms_caption, $pictograms_type, $pictogram_id){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("UPDATE pictograms SET alt = '".$pictogram_alt."', caption = '".$pictograms_caption."', type = '".$pictograms_type."' WHERE id = '".$pictogram_id."' "))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //------------------------------------------Add pictograms---------------------------------------------
    public function addPictogram($alt, $caption, $type){

        global $mysqli;
        if (!$sql            = $mysqli->real_query("INSERT INTO pictograms(alt, caption, type) VALUES ('$alt', '$caption', '$type')"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;

    }
    //---------------------------------------------END-----------------------------------------------------

}
?>