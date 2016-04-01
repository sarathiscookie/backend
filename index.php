<?php
require_once "includes/header.php";
require_once "classes/search.class.php";

$objSearch = new Search();
$results   = $objSearch ->getSearchResults();

?>
<div class="container-fluid">
    <div class="row">
        <?php require_once 'includes/sidebar.php'; ?>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Suchergebnisse</h1>

            <div class="table-responsive">
                <?php if($_GET['key']!='' && count($results)===0) {
                    print "No results found for the key: <strong>\"" . $_GET['key'] . "\"</strong>";
                } else {
                    if ($results['search_schools']) print $results['search_schools'];
                    if ($results['search_hotels']) print $results['search_hotels'];
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include "includes/scripts.php"; ?>
<script>
    $(function(){
        $('.no-data').hide();
        $('#filter').keyup(function () {
            var rex = new RegExp($(this).val(), 'i');
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();
            $('.no-data').hide();
            if($('.searchable tr:visible').length == 0)
            {
                $('.no-data').show();
            }
        });
    });
</script>
<?php include "includes/footer.php"; ?>

