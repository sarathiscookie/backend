<?php
require_once "dbconnection-class.php";

class Search
{
    /**
     * Display search results - School/Hotel
     * @return array
     */
    public function getSearchResults()
    {
        global $mysqli, $_GET;
        $key     = $mysqli->real_escape_string($_GET['key']);
        $results = array();

        $status["hidden"] = "versteckt";
        $status["online"] = "online";
        $status["offline"] = "offline";
        $status["deleted"] = "gelöscht";

            $searchData = "AND `title` LIKE '%" . $key . "%'";
            $search = "SELECT id, title, status FROM `schools` WHERE status<>'deleted' AND (title<>'' OR title<>null) $searchData ORDER BY FIELD(status, 'online', 'hidden', 'offline', 'deleted'), title";
            $rows = $mysqli->query($search);
            $shl_count =  $rows->num_rows;
            if ($shl_count > 0) {
                $school_list = '';
                while ($school = $rows->fetch_array()) {
                    $school_list .= '<tr><td><a href="edit_school.php?sid='.$school['id'].'">' . $school['title'] . " <small>(" . $status[$school['status']] . ')</small></a></td></tr>';
                }
                if ($school_list != '') {
                    $results["search_schools"] = '<table class="table table-striped"><thead><tr><th>Golfschulen</th></tr></thead><tbody class="searchable"><tr class="no-data"><td colspan="9">No data in this page.</td></tr>' . $school_list . '</tbody></table>';
                }
            }

            $search_hotel = "SELECT id, title, status FROM `hotels` WHERE status<>'deleted' AND (title<>'' OR title<>null)  $searchData  ORDER BY FIELD(status, 'online', 'offer', 'hidden', 'offline', 'deleted'), title";
            $hotels = $mysqli->query($search_hotel);
            $htl_count = $hotels->num_rows;
            if ($htl_count > 0) {
                $hotel_list = '';
                while ($hotel = $hotels->fetch_array()) {
                    $hotel_list .= '<tr><td><a href="edit_hotel.php?hid='.$hotel['id'].'">' . $hotel['title'] . " <small>(" . $hotel['status'] . ')</small></a></td></tr>';
                }
                if ($hotel_list != '') {
                    $results["search_hotels"] = '<table class="table table-striped"><thead><tr><th>Hotels</th></tr></thead><tbody class="searchable"><tr class="no-data"><td colspan="9">No data in this page.</td></tr>' . $hotel_list . '</tbody></table>';
                }
            }

        return $results;
    }
}