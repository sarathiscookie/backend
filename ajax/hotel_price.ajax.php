<?php
require_once "../classes/manage_hotels.class.php";

$objHotel = new ManageHotels();
if(isset($_GET['mode']) && $_GET['mode']=='C') {
    $objHotel -> addHotelPrices();
}
else {
    $objHotel -> updatePrices();
}