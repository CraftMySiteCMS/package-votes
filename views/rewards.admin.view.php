<?php
use CMS\Model\Votes\rewardsModel;

$title = VOTES_DASHBOARD_TITLE_REWARDS;
$description = VOTES_DASHBOARD_DESC;

ob_start();
?>


<pre>Faire un système de création de récompenses, une fois la récompense créé elle sera stocké dans une table et l'utilisateur
pourra la choisir dans les récompenses de votes et les récompenses automatiques.</pre>

<?php $content = ob_get_clean(); ?>

<?php require(getenv("PATH_ADMIN_VIEW") . 'template.php'); ?>
