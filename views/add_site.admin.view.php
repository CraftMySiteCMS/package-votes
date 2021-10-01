<?php
header('Access-Control-Allow-Origin: *');
use CMS\Model\Votes\rewardsModel;
$title = VOTES_DASHBOARD_TITLE_ADD;
$description = VOTES_DASHBOARD_DESC;

$styles = '<link rel="stylesheet" href="'.getenv("PATH_SUBFOLDER").'admin/resources/vendors/sweetalert2/sweetalert2.css">';

//Javascript file for testing the unique Id + toaster sweetalert2
$scripts= '<script src="'.getenv("PATH_SUBFOLDER").'app/package/votes/views/ressources/js/testSitesId.js"></script>
<script src="'.getenv("PATH_SUBFOLDER").'admin/resources/vendors/sweetalert2/sweetalert2.all.js"></script>';

ob_start();
/** @var rewardsModel[] $rewards */
?>



    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <form action="" method="post">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><?= VOTES_DASHBOARD_ADD_CARD_TITLE ?> :</h3>
                            </div>
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                    </div>
                                    <input type="text" name="title" class="form-control"
                                           placeholder="<?= VOTES_DASHBOARD_ADD_PLACEHOLDER_TITLE ?>" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-hourglass-start"></i></span>
                                    </div>
                                    <input type="number" name="time" class="form-control"
                                           placeholder="<?= VOTES_DASHBOARD_ADD_PLACEHOLDER_TIME ?>" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                    </div>
                                    <input type="text" name="idUnique" id="idUnique" class="form-control"
                                           placeholder="<?= VOTES_DASHBOARD_ADD_PLACEHOLDER_ID_UNIQUE ?>" required>
                                    <div class="input-group-prepend">
                                        <button type="button" onclick="testId();" class="btn btn-success"><?= VOTES_DASHBOARD_ADD_BTN_TESTID ?></button>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-link"></i></span>
                                    </div>
                                    <input type="url" name="url" id="url" class="form-control"
                                           placeholder="<?= VOTES_DASHBOARD_ADD_PLACEHOLDER_URL ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="reward"><?= VOTES_DASHBOARD_ADD_PLACEHOLDER_REWARDS ?></label>
                                    <select name="reward" class="form-control" required>
                                        <?php /** @var sitesModel[] $rewards */
                                        foreach ($rewards as $reward) : ?>
                                            <option value="<?=$reward['rewards_id']?>"><?=$reward['title']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sitesComp">
                                    <?= VOTES_DASHBOARD_ADD_BTN_SITESCOMP ?>
                                </button>

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


    <!-- Modal Listing websites compatibilities -->
    <div class="modal fade" id="sitesComp" tabindex="-1" role="dialog" aria-labelledby="sitesCompLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sitesCompLabel"><?= VOTES_DASHBOARD_ADD_SITESCOMP_MODAL_TITLE ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Websites name, exemple → serveurs-minecraft.org -->
                <div class="modal-body">
                    <ul>
                        <!-- Minecraft server list -->
                        <h4 class="text-center font-weight-bold">Sites de vote Minecraft</h4>
                        <hr>
                        <li><a href="https://serveur-prive.net" target="_blank">serveur-prive.net</a></li>
                        <li><a href="https://serveur-minecraft-vote.fr" target="_blank">serveur-minecraft-vote.fr</a></li>
                        <li><a href="https://serveurs-mc.net" target="_blank">serveurs-mc.net</a></li>
                        <li><a href="https://top-serveurs.net" target="_blank">top-serveurs.net</a></li>
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= VOTES_DASHBOARD_BTN_CLOSE ?></button>
                </div>
            </div>
        </div>
    </div>

<?php $content = ob_get_clean(); ?>

