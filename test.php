<?php include "includes/header.php"; ?>
<div class="container-fluid">
    <h1>Test Page</h1>
<?php
$select = "SELECT title FROM `schools` WHERE title<>'' ORDER BY title";
$rows   = mysqli_query($connection, $select);

while($school = mysqli_fetch_array($rows)) {
    echo $school['title']. "<br>";
}
?>
</div>
<?php
include "includes/footer.php";
$start = $fetch_course_option["arrival"];
$end = $start;
for ($i = 1; $i <= $fetch_course_option["nights"]; $i ++)
{ $end ++; if ($end == 8) $end = 1; }
?>