<?php
require_once "dbconnection-class.php";
class textBlocksService{

    //------------------------------------ Count Text Blocks Pagination ------------------------------------------
    public function countTextBlocks(){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT COUNT(id) FROM text_blocks"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END------------------------------------------------------------

    //------------------------------------------List Text Blocks--------------------------------------------------
    public function listTextBlocks($start, $limit){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT id, title, description FROM text_blocks ORDER BY id DESC LIMIT $start, $limit"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END------------------------------------------------------------

    //------------------------------------------Update Text Blocks------------------------------------------------
    public function updTextBlocks($upd_title, $upd_description, $upd_id){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("UPDATE text_blocks SET title = '".$upd_title."', description = '".$upd_description."' WHERE id = '".$upd_id."' "))

        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END------------------------------------------------------------


    //------------------------------------------Add Text Blocks---------------------------------------------------
    public function addTextBlocks($title, $description){

        global $mysqli;
        if (!$sql            = $mysqli->real_query("INSERT INTO text_blocks(title, description) VALUES ('$title', '$description')"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;

    }
    //---------------------------------------------END------------------------------------------------------------


}
?>