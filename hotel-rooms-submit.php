<?php
require_once "config/debug.php";
require_once "classes/hotel-room-class.php";
$service                   = new hotelRoomService();
if(isset($_POST['editroom'])){
    $room_id               = $_POST['id'];
    $room_title            = $_POST['firstname'];
    $room_title_short      = $_POST['secondname'];
    $persons               = $_POST['thirdname'];
    $service->updateRoom($room_title, $room_title_short, $persons, $room_id);
}
if(isset($_POST['add_room'])){
    $title            = $_POST['title'];
    $title_short      = $_POST['title_short'];
    $person           = $_POST['persons'];
    $service->addRoom($title, $title_short, $person);
    echo '<div class="alert alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            Hotelzimmer erfolgreich hinzugefügt
            </div>';
}