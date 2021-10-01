<?php
use CMS\Model\Votes\configModel;

$title = VOTES_DASHBOARD_TITLE_CONFIG;
$description = VOTES_DASHBOARD_DESC;

ob_start();
/** @var configModel $config */
?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <form action="" method="post">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><?= VOTES_DASHBOARD_CONFIG_CARD_TITLE ?> :</h3>
                            </div>
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-trophy"></i></span>
                                    </div>
                                    <!-- Top players show -->
                                    <input type="number" name="topShow" class="form-control"
                                           placeholder="<?= VOTES_DASHBOARD_ADD_PLACEHOLDER_TOPSHOW ?>"
                                           value="<?= $config->topShow ?>" required>
                                </div>

                                <!-- Reset -->
                                <div class="form-group">
                                    <label><?= VOTES_DASHBOARD_CONFIG_PLACEHOLDER_RESET ?></label>
                                    <select name="reset" class="form-control" required>
                                        <option value="<?= $config->reset ?>" selected>
                                            <?php if ($config->reset == 0){
                                                echo VOTES_DASHBOARD_CONFIG_RESET_0;
                                                }else{
                                                echo VOTES_DASHBOARD_CONFIG_RESET_1;
                                                }
                                            ?></option>

                                        <option value="<?php if($config->reset == 1) {
                                                echo "0";
                                            }else{
                                                echo "1";
                                            }
                                            ?>">
                                            <?php if ($config->reset == 1){
                                                echo VOTES_DASHBOARD_CONFIG_RESET_0;
                                            }else{
                                                echo VOTES_DASHBOARD_CONFIG_RESET_1;
                                            }
                                            ?></option>
                                    </select>
                                </div>
                            </div>

                            <input type="number" name="autoTopRewardActive" value="1" hidden>

                            <!-- /!\ JSON function /!\-->
                            <input type="text" name="autoTopReward" value='cc le JSON' hidden>

                        <!-- Récompenses automatique (mensuel)

                            <input type="checkbox" name="toggleAutoReward" value="checkbox" onchange="showMe('showAutoReward')" /> Activer les récompenses automatique

                            <div id="showAutoReward" style="display:none;">
                                Cc me voilà
                            </div>

                            <script type="text/javascript">

                                function showMe (box) {
                                    var chboxs = document.getElementById("showAutoReward").style.display;
                                    var vis = "none";
                                    if(chboxs=="none"){
                                        vis = "block"; }
                                    if(chboxs=="block"){
                                        vis = "none"; }
                                    document.getElementById(box).style.display = vis;
                                }

                            </script>
                        -->
                            <div class="card-footer">


                                <button type="submit" class="btn btn-primary float-right">
                                    <?= VOTES_DASHBOARD_BTN_SAVE ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

<?php $content = ob_get_clean(); ?>

<?php require(getenv("PATH_ADMIN_VIEW") . 'template.php'); ?>