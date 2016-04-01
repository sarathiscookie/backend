<?php
require_once "dbconnection-class.php";
class courseDurationsService{

    //-------------------------------- Count course duration Pagination ------------------------------------
    public function countCourseDurations(){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT COUNT(id) FROM course_durations"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END------------------------------------------------------

    //-------------------------------------List course duration---------------------------------------------
    public function listCourseDuration($start, $limit){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("SELECT id, title, period, duration FROM course_durations ORDER BY id DESC LIMIT $start, $limit"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //-------------------------------------Update course duration------------------------------------------
    public function updateCourseDuration($CourseDuration_title, $CourseDuration_period, $CourseDuration_duration, $courseDuration_id){

        global $mysqli;
        if (!$sql           = $mysqli->real_query("UPDATE course_durations SET title = '".$CourseDuration_title."', period = '".$CourseDuration_period."', duration = '".$CourseDuration_duration."' WHERE id = '".$courseDuration_id."' "))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //---------------------------------------------END-----------------------------------------------------

    //--------------------------------------Add course duration--------------------------------------------
    public function addCourseDuration($title, $period, $duration){

        global $mysqli;
        if (!$sql            = $mysqli->real_query("INSERT INTO course_durations(title, period, duration) VALUES ('$title', '$period', '$duration')"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;

    }
    //---------------------------------------------END-----------------------------------------------------

}
?>