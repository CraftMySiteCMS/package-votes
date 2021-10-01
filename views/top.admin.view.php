<?php
$title = VOTES_DASHBOARD_TITLE_TOP;
$description = VOTES_DASHBOARD_DESC;

ob_start();
?>


<?php $content = ob_get_clean(); ?>

<?php require(getenv("PATH_ADMIN_VIEW") . 'template.php'); ?>