<?php
require_once "dbconnection-class.php";
class hotelRoomService{

    //------------------------------------ Count Room Pagination ------------------------------------------
    public function countRoom(){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT COUNT(id) FROM rooms"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //------------------------------------------List Room--------------------------------------------------
    public function listRoom($start, $limit){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT id, title, title_short, persons FROM rooms ORDER BY id DESC LIMIT $start, $limit"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //------------------------------------------Update Room------------------------------------------------
    public function updateRoom($room_title, $room_title_short, $persons, $room_id){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("UPDATE rooms SET title = '".$room_title."', title_short = '".$room_title_short."', persons = '".$persons."' WHERE id = '".$room_id."' "))

        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------


    //------------------------------------------Add Room------------------------------------------------
    public function addRoom($title, $title_short, $person){

        global $mysqli;
        if (!$sql            = $mysqli->real_query("INSERT INTO rooms(title, title_short, persons) VALUES ('$title', '$title_short', '$person')"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;

    }
    //---------------------------------------------END-----------------------------------------------------


}
?>