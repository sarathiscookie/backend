<?php
require_once "dbconnection-class.php";
class groupService{

    //------------------------------------ Count Group Pagination -------------------------------------------
    public function countGroup(){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT COUNT(id) FROM groups"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END------------------------------------------------------

    //------------------------------------------List Group--------------------------------------------------
    public function listGroup($start, $limit){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT id, title, headline, anchor FROM groups ORDER BY id DESC LIMIT $start, $limit"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //------------------------------------------Update Group-----------------------------------------------
    public function updateGroup($group_title, $group_headline, $anchor, $group_id){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("UPDATE groups SET title = '".$group_title."', headline = '".$group_headline."', anchor = '".$anchor."' WHERE id = '".$group_id."' "))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //------------------------------------------Add Room---------------------------------------------------
    public function addGroup($title, $headline, $anchor){

        global $mysqli;
        if (!$sql            = $mysqli->real_query("INSERT INTO groups(title, headline, anchor) VALUES ('$title', '$headline', '$anchor')"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;

    }
    //---------------------------------------------END-----------------------------------------------------

}
?>