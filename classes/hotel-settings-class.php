<?php
require_once "dbconnection-class.php";
class hotelSettingsService{

    //---------------------------------------------List Rooms--------------------------------------------------------
    public function listRooms(){

        global $mysqli;
        if (!$sql           = $mysqli->query("SELECT id, title FROM rooms GROUP BY id"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //------------------------------------------------END------------------------------------------------------------

    //------------------------------------------List Pictograms------------------------------------------------------
    public function listPictograms(){

        global $mysqli;
        if (!$sql           = $mysqli->query("SELECT id, alt FROM pictograms GROUP BY id"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //--------------------------------------------------END----------------------------------------------------------

    //------------------------------------------List Hotel Rooms-----------------------------------------------------
    public function listHotelRooms($hotel_id){

        global $mysqli;
        if (!$sql           = $mysqli->query("SELECT room_id FROM hotel_rooms WHERE hotel_id = '$hotel_id' GROUP BY room_id"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //-----------------------------------------------------END-------------------------------------------------------

    //------------------------------------------List Hotel Pictograms------------------------------------------------
    public function listHotelPictograms($hotel_id){

        global $mysqli;
        if (!$sql           = $mysqli->query("SELECT picto_id FROM hotel_pictograms WHERE hotel_id = '$hotel_id' GROUP BY picto_id"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //--------------------------------------------------END----------------------------------------------------------

    //------------------------------------------Insert Hotel Rooms---------------------------------------------------
    public function insertehotelrooms($hotelID, $room_id){

        global $mysqli;
        if (!$sql           = $mysqli->query("INSERT INTO hotel_rooms (hotel_id, room_id) VALUES ('$hotelID', '$room_id')"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //--------------------------------------------------END----------------------------------------------------------

    //------------------------------------------Insert Hotel Picto---------------------------------------------------
    public function insertehotelpicto($hotelID, $picto_id){

        global $mysqli;
        if (!$sql           = $mysqli->query("INSERT INTO hotel_pictograms (hotel_id, picto_id) VALUES ('$hotelID', '$picto_id')"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //--------------------------------------------------END----------------------------------------------------------

    //------------------------------------------Delete Hotel Rooms---------------------------------------------------
    public function deletehotelrooms($hotelID, $room_id){

        global $mysqli;
        if (!$sql           = $mysqli->query("DELETE FROM hotel_rooms WHERE hotel_id = '$hotelID' AND room_id = '$room_id'"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //--------------------------------------------------END----------------------------------------------------------

    //------------------------------------------Delete Hotel Pictos--------------------------------------------------
    public function deletehotelpicto($hotelID, $picto_id){

        global $mysqli;
        if (!$sql           = $mysqli->query("DELETE FROM hotel_pictograms WHERE hotel_id = '$hotelID' AND picto_id = '$picto_id'"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //--------------------------------------------------END----------------------------------------------------------


    ############################################### Hotel-Course ####################################################

    //------------------------------------------List Hotel Courses---------------------------------------------------
    public function listHotelCourses($hotel_id){

        global $mysqli;
        if (!$sql           = $mysqli->query("SELECT course_durations.title, course_durations.duration, course_durations.id
                                              FROM hotels
                                              INNER JOIN courses ON courses.school_id = hotels.school_id
                                              INNER JOIN course_options ON course_options.course_id = courses.id
                                              INNER JOIN course_durations ON course_durations.id = course_options.course_duration_id
                                              WHERE hotels.id = '$hotel_id'
                                              GROUP BY course_durations.id"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //-----------------------------------------------------END-------------------------------------------------------

    //------------------------------------------Insert Hotel Courses-------------------------------------------------
    public function insertHotelCourse($hotelID, $course_duration_id, $nights, $arrival){

        global $mysqli;
        if (!$sql           = $mysqli->query("INSERT INTO hotel_courses (hotel_id, course_duration_id, nights, arrival) VALUES ('$hotelID', '$course_duration_id', '$nights', '$arrival')"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //--------------------------------------------------END----------------------------------------------------------

    //------------------------------------------List Hotel Courses---------------------------------------------------
    public function hotelCourseDetails($hotel_id, $course_duration_id){

        global $mysqli;
        if (!$sql           = $mysqli->query("SELECT id, nights, arrival FROM hotel_courses WHERE hotel_id = '$hotel_id' AND course_duration_id = '$course_duration_id'"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //-----------------------------------------------------END-------------------------------------------------------

    //------------------------------------------Update Hotel Courses-------------------------------------------------
    public function updateHotelCourse($hotel_course_ID, $nights, $arrival){

        global $mysqli;
        if (!$sql           = $mysqli->query("UPDATE hotel_courses SET nights = '$nights', arrival = '$arrival' WHERE id = '$hotel_course_ID'"))
        {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return $sql;
    }
    //--------------------------------------------------END----------------------------------------------------------
    /**
     * Get Hotel Name
     */
    public function getHotelName()
    {
        global $mysqli;
        $hotel = $mysqli->query("SELECT title FROM hotels WHERE id=".$_GET["editID"]);
        $row  = $hotel->fetch_array();
        return $row["title"];
    }
}
?>