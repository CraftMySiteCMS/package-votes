<?php
$title = VOTES_DASHBOARD_TITLE_LISTE_SITES;
$description = VOTES_DASHBOARD_DESC;

ob_start();
/** @var rewardsModel[] $rewards */
/** @var sitesModel[] $votesList */
?>


<?php ob_start(); ?>

    <div class="content">

        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= VOTES_DASHBOARD_LISTE_TITLE ?></h3>
                        </div>

                        <div class="card-body">

                            <div id="accordion">

                                <?php foreach ($votesList as $votes) : ?>
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapse<?= $votes['id'] ?>" aria-expanded="false">
                                                    <?= $votes['title'] ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?= $votes['id'] ?>" class="collapse" data-parent="#accordion" style="">
                                            <div class="card-body">
                                                <form action="" method="post" >

                                                    <input type="text" name="siteId" value="<?= $votes['id'] ?>" hidden>

                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                                        </div>
                                                        <input type="text" name="title" class="form-control"
                                                               placeholder="<?= VOTES_DASHBOARD_ADD_PLACEHOLDER_TITLE ?>"
                                                               value="<?= $votes['title'] ?>"
                                                               required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-hourglass-start"></i></span>
                                                        </div>
                                                        <input type="number" name="time" class="form-control"
                                                               placeholder="<?= VOTES_DASHBOARD_ADD_PLACEHOLDER_TIME ?>"
                                                               value="<?= $votes['time'] ?>"
                                                               required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                                        </div>
                                                        <input type="text" name="idUnique" class="form-control"
                                                               placeholder="<?= VOTES_DASHBOARD_ADD_PLACEHOLDER_ID_UNIQUE ?>"
                                                               value="<?= $votes['id_unique'] ?>"
                                                               required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                                                        </div>
                                                        <input type="url" name="url" class="form-control"
                                                               placeholder="<?= VOTES_DASHBOARD_ADD_PLACEHOLDER_URL ?>"
                                                               value="<?= $votes['url'] ?>"
                                                               required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label><?= VOTES_DASHBOARD_ADD_PLACEHOLDER_REWARDS ?></label>
                                                        <select name="reward" class="form-control"  required>
                                                            <?php foreach ($rewards as $reward) : ?>
                                                                <option value="<?= $reward['rewards_id'] ?>" <?= ($votes['rewards_id'] === $reward['rewards_id'] ? "selected" : "") ?>><?= $reward['title'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>

                                                    </div>


                                                    <input type="submit" value="<?= VOTES_DASHBOARD_BTN_SAVE ?>" class="btn btn-primary float-right">

                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#sitesDel<?= $votes['id']?>">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>


                                                    <!-- Modal Delete verif -->
                                                    <div class="modal fade" id="sitesDel<?= $votes['id']?>" tabindex="-1" role="dialog" aria-labelledby="siteDelLabel<?= $votes['id']?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="sitesCompLabel">
                                                                        <?= VOTES_DASHBOARD_LIST_DELSITE_MODAL_TITLE ?> <strong><?=$votes['title']?></strong>
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <!-- Button for delete the website -->
                                                                <div class="modal-body">
                                                                   <?= VOTES_DASHBOARD_LIST_DELSITE_MODAL_BODY ?>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <a href="delete/<?=$votes['id']?>" class="btn btn-danger">
                                                                        <?= VOTES_DASHBOARD_LIST_DELSITE_MODAL_BTN_DEL ?>
                                                                    </a>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= VOTES_DASHBOARD_BTN_CLOSE ?></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



<?php $content = ob_get_clean(); ?>

<?php require(getenv("PATH_ADMIN_VIEW") . 'template.php'); ?>