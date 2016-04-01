<?php
require_once "dbconnection-class.php";
class courseTypesService{

    //-------------------------------- Count course type Pagination ------------------------------------
    public function countCourseType(){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT COUNT(id) FROM course_types"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END------------------------------------------------------

    //-----------------------------------------List course type---------------------------------------------
    public function listCourseType($start, $limit){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT id, title FROM course_types ORDER BY id DESC LIMIT $start, $limit"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //-----------------------------------------Update course type------------------------------------------
    public function updateCourseType($courseType_title, $courseType_id){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("UPDATE course_types SET title = '".$courseType_title."' WHERE id = '".$courseType_id."' "))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //------------------------------------------Add course type--------------------------------------------
    public function addCourseType($title){

        global $mysqli;
        if (!$sql            = $mysqli->real_query("INSERT INTO course_types(title) VALUES ('$title')"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;

    }
    //---------------------------------------------END-----------------------------------------------------

}
?>