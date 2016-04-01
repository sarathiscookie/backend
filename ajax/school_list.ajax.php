<?php
require_once "../classes/manage_hotels.class.php";

$objHotel = new ManageHotels();
$hotel    = $objHotel -> getSchoolList();