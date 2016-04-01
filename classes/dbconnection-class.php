<?php
header("Content-type: text/html; charset=utf-8");
require_once __DIR__."../../config/database.php";
class MySQLiContainer extends SplObjectStorage{

    public function newConnection($MYSQL_SERVER, $MYSQL_SERVER_USERNAME, $MYSQL_SERVER_PASSWORD, $MYSQL_SERVER_DATA_BASE)
    {
        $mysqli              = new mysqli($MYSQL_SERVER, $MYSQL_SERVER_USERNAME, $MYSQL_SERVER_PASSWORD, $MYSQL_SERVER_DATA_BASE);
        if (!$mysqli->set_charset('utf8')) {
            printf("Error loading character set utf8: %s\n", $mysqli->error);
        }
        $this->attach($mysqli);
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        return $mysqli;
    }
}
$mysqliContainer                    = new MySQLiContainer();
$mysqli                             = $mysqliContainer->newConnection(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
?>