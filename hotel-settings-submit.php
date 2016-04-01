<?php
require_once "config/debug.php";
require_once "classes/hotel-settings-class.php";
header('Content-Type: application/json');
$service                   = new hotelSettingsService();

/*---- Insert data in to hotel rooms ----*/
if(isset($_POST['hotelroomsubmit']))
{
    $room_id            = $_POST['multihotelroomdata'];
    $hotelID            = $_POST['hotelID'];
    $insert_hotelRooms  = $service->insertehotelrooms($hotelID, $room_id);
}

/*---- Delete data from hotel rooms when uncheck checkbox ----*/
if(isset($_POST['hotelroomsDel']))
{
    $room_id            = $_POST['multidataDel'];
    $hotelID            = $_POST['hotelID'];
    $delete_hotelRooms  = $service->deletehotelrooms($hotelID, $room_id);
}

/*---- Insert data in to hotel pictograms ----*/
if(isset($_POST['hotelpictogramsubmit']))
{
    $hotelID            = $_POST['hotelID'];
    $picto_id           = $_POST['hotelpictodata'];
    $insert_hotelPicto  = $service->insertehotelpicto($hotelID, $picto_id);
}

/*---- Delete data from hotel pictograms when uncheck checkbox ----*/
if(isset($_POST['hotelpictodataDel']))
{
    $picto_id           = $_POST['pictodataDel'];
    $hotelID            = $_POST['hotelID'];
    $delete_hotePicto   = $service->deletehotelpicto($hotelID, $picto_id);
}

/*---- Checkbox selected if data is already stored in hotel rooms table ----*/
if(isset($_POST['selected']))
{
    $hotel_id    = $_POST["hotel_id"];
    $selected    = $service->listHotelRooms($hotel_id);
    $result      = array();
    while($array = $selected->fetch_array()) {
        $result[] = $array["room_id"];
    }
    echo json_encode($result);
}

/*---- Checkbox selected if data is already stored in hotel pictograms table ----*/
if(isset($_POST['selectedPictograms']))
{
    $hotel_id    = $_POST["hotel_id"];
    $selected    = $service->listHotelPictograms($hotel_id);
    $result      = array();
    while($array = $selected->fetch_array()) {
        $result[] = $array["picto_id"];
    }
    echo json_encode($result);
}

/* Insert data in to Hotel Course */
if(isset($_POST['save_hotel_course']))
{
    $nights              = $_POST['nights'];
    $arrival             = $_POST['arrival'];
    $course_duration_id  = $_POST['course_duration_id'];
    $hotelID             = $_POST['hotelID'];
    $insert_hotel_course = $service->insertHotelCourse($hotelID, $course_duration_id, $nights, $arrival);
    echo $insert_hotel_course;
}

/* Update data in to Hotel Course */
if(isset($_POST['update_hotel_course']))
{
    $nights              = $_POST['nights'];
    $arrival             = $_POST['arrival'];
    $hotel_course_ID     = $_POST['hotel_course_ID'];
    $update_hotel_course = $service->updateHotelCourse($hotel_course_ID, $nights, $arrival);
    echo $update_hotel_course;
}