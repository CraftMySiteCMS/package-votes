<?php

namespace CMS\Controller\Votes;

use CMS\Controller\coreController;
use CMS\Controller\Menus\menusController;
use CMS\Controller\users\usersController;
use CMS\Model\users\usersModel;
use CMS\Model\Votes\checkVotesModel;
use CMS\Model\votes\configModel;
use CMS\Model\votes\rewardsModel;
use CMS\Model\votes\sitesModel;
use CMS\Model\Votes\statsModel;
use CMS\Model\Votes\votesModel;


/**
 * Class: @votesController
 * @package votes
 * @author Teyir | CraftMySite <contact@craftmysite.fr>
 * @version 1.0
 */
class votesController extends coreController
{

    public static string $themePath;

    public function __construct($themePath = null)
    {
        parent::__construct($themePath);
    }


    /* ///////////////////// CONFIG /////////////////////*/

    public function votesConfig()
    {

        $config = new configModel();
        $config->fetch();

        //Include the view file ("views/config.admin.view.php").
        view('votes', 'config.admin', ["config" => $config], 'admin');
    }

    public function votesConfigPost()
    {
        //Keep this perm control just for the post function
        usersController::isAdminLogged();

        $votes = new configModel();

        $votes->topShow = $_POST['topShow'];
        $votes->reset = $_POST['reset'];
        $votes->autoTopRewardActive = $_POST['autoTopRewardActive'];
        $votes->autoTopReward = $_POST['autoTopReward'];


        //Faire la config pour les rewards


        $votes->update();

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_EDIT_SUCCESS;

        header('location: ../votes/config/');
    }


    /* ///////////////////// SITES /////////////////////*/

    public function addSiteAdmin()
    {

        $rewards = new rewardsModel();
        $rewards = $rewards->fetchAll();

        view('votes', 'add_site.admin', ["rewards" => $rewards], 'admin');
    }

    public function addSiteAdminPost()
    {
        usersController::isAdminLogged();

        $votes = new sitesModel();

        $votes->title = $_POST['title'];
        $votes->time = $_POST['time'];
        $votes->idUnique = $_POST['idUnique'];
        $votes->url = $_POST['url'];
        $votes->rewardsId = $_POST['reward'];

        $votes->addSite();

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ADD_SUCCESS;

        header('location: ../site/add');

    }


    public function listSites()
    {

        $votes = new sitesModel();
        $votesList = $votes->fetchAll();

        $rewards = new rewardsModel();
        $rewards = $rewards->fetchAll();


        view('votes', 'list_sites.admin', ["votesList" => $votesList, "rewards" => $rewards], 'admin');
    }

    public function votesSitesEdit()
    {

        $votes = new sitesModel();
        $votes->fetch($_POST['siteId']);

        view('votes', 'list_sites.admin', ["votes" => $votes], 'admin');
    }

    public function votesSitesEditPost()
    {
        usersController::isAdminLogged();

        $votes = new sitesModel();
        $votes->siteId = $_POST['siteId'];
        $votes->title = $_POST['title'];
        $votes->time = $_POST['time'];
        $votes->idUnique = $_POST['idUnique'];
        $votes->url = $_POST['url'];
        $votes->rewardsId = $_POST['reward'];
        $votes->update();

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_EDIT_SUCCESS;

        header('location: ../site/list/');

    }

    public function deleteSitePostAdmin($id)
    {
        usersController::isAdminLogged();

        $votes = new sitesModel();
        $votes->siteId = $id;
        $votes->delete($id);

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_DELETE_SUCCESS;

        header('location: ../list/');
    }


    /* ///////////////////// REWARDS /////////////////////*/

    public function votesRewards()
    {

        $rewards = new rewardsModel();
        $rewards = $rewards->fetchAll();


        view('votes', 'rewards.admin', ["rewards" => $rewards], 'admin');
    }

    public function addRewardPost()
    {
        usersController::isAdminLogged();

        $reward = new rewardsModel();

        $rewardType = filter_input(INPUT_POST, "reward_type");
        $reward->title = filter_input(INPUT_POST, "title");

        //Define the reward action
        switch ($rewardType) {
            case "votepoints":
                $reward->action = json_encode(array("type" => "votepoints", "amount" => filter_input(INPUT_POST, "amount")));
                break;

            case "votepoints-random":
                $reward->action = json_encode(array("type" => "votepoints-random",
                    "amount" => array(
                        "min" => filter_input(INPUT_POST, "amount-min"),
                        "max" => filter_input(INPUT_POST, "amount-max"))));
                break;

            case "none"://Error, redirect
                $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_ERROR;
                $_SESSION['toaster'][0]['type'] = "bg-danger";
                $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ERROR_INTERNAL;
                header("location: ../rewards");
                break;
        }

        //Add reward
        $reward->addReward();

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ADD_SUCCESS;

        header("location: ../rewards");

    }

    public function deleteRewardPostAdmin($id)
    {
        usersController::isAdminLogged();

        $reward = new rewardsModel();
        $reward->rewardsId = $id;
        $reward->delete($id);

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_DELETE_SUCCESS;

        header('location: ../rewards');
    }

    public function editRewardPost()
    {
        usersController::isAdminLogged();

        $reward = new rewardsModel();

        $reward->rewardsId = filter_input(INPUT_POST, "reward_id");
        $rewardType = filter_input(INPUT_POST, "reward_type");
        $reward->title = filter_input(INPUT_POST, "title");


        //Define the reward action
        switch ($rewardType) {
            case "votepoints":
                $reward->action = json_encode(array("type" => "votepoints", "amount" => filter_input(INPUT_POST, "amount")));
                break;

            case "votepoints-random":
                $reward->action = json_encode(array("type" => "votepoints-random",
                    "amount" => array(
                        "min" => filter_input(INPUT_POST, "amount-min"),
                        "max" => filter_input(INPUT_POST, "amount-max"))));
                break;

            case "none"://Error, redirect
                $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_ERROR;
                $_SESSION['toaster'][0]['type'] = "bg-danger";
                $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ERROR_INTERNAL;
                header("location: ../votes/rewards");
                break;
        }


        $reward->update();

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_EDIT_SUCCESS;

        header('location: rewards');

    }

    //Return the reward with a specific ID
    public function getReward(){
        /* Error section */
        if (empty(filter_input(INPUT_POST, "id"))) {
            echo json_encode(array("response" => "ERROR-EMPTY_ID"));
        } else {
            $reward = new rewardsModel();

            $reward->fetch(filter_input(INPUT_POST, "id"));

            echo $reward->action;
        }

    }

    /* ///////////////////// STATS /////////////////////*/

    public function statsVotes()
    {

        $stats = new statsModel();

        $all = $stats->statsVotes("all");
        $month = $stats->statsVotes("month");
        $week = $stats->statsVotes("week");
        $day = $stats->statsVotes("day");

        $sites = new sitesModel();
        $listSites = $sites->fetchAll();

        $numberOfSites = $stats->getNumberOfSites();
        
        $actualTop = $stats->getActualTopNoLimit();
        $globalTop = $stats->getGlobalTopNoLimit();
        $previousTop = $stats->getPreviousMonthTop();


        view('votes', 'stats.admin', ["stats" => $stats, "all" => $all, "month" => $month, "week" => $week, "day" => $day,
            "listSites" => $listSites, "numberOfSites" => $numberOfSites, "actualTop" => $actualTop,
            "globalTop" => $globalTop, "previousTop" => $previousTop], 'admin');;
    }



    /* //////////////////////////////////////////////////////////////*/
    /* ///////////////////// PUBLIC VOTE SECTION ////////////////////*/
    /* //////////////////////////////////////////////////////////////*/

    public function votesPublic()
    {
        //Default controllers (important)
        $core = new coreController();
        $menu = new menusController();

        $vote = new votesModel();

        $sites = new sitesModel();
        $sites = $sites->fetchAll();

        $_SESSION['votes']['token'] = $vote->generateToken();

        //Include the public view file ("public/themes/$themePath/views/votes/main.view.php")
        view('votes', 'main', ["votes" => $vote, "sites" => $sites,
            "core" => $core, "menu" => $menu], 'public');
    }

    public function votesPublicVerify()
    {

        /* Error section */
        if (empty(filter_input(INPUT_POST, "url"))) {
            echo json_encode(array("response" => "ERROR-URL"));
        } else {


            $vote = new votesModel();
            $user = new usersModel();
            $reward = new rewardsModel();


            $url = filter_input(INPUT_POST, "url");

            $site = $vote->getSite($url);
            $vote->idUnique = $site['id_unique'];
            $vote->idSite = $site['id'];

            $reward->idSite = $vote->idSite;

            $vote->ipPlayer = "";
            //$vote->getClientIp(); todo get l'ip de l'utilisateur

            //Get user id
            $user->fetch($_SESSION['cmsUserId']);
            $vote->idUser = $user->userId;
            $reward->userId = $vote->idUser;


            if ($vote->check($url) === true) {

                if ($vote->hasVoted() === "NEW_VOTE") {

                    //Store the vote
                    $vote->storeVote();

                    //Get reward
                    $reward->selectReward();

                    echo json_encode(array("response" => "GOOD-NEW_VOTE"));
                } else {
                    //If the player can get the reward
                    if ($vote->hasVoted() === "GOOD") {
                        //Store the vote
                        $vote->storeVote();

                        //Get reward
                        $reward->selectReward();

                        echo json_encode(array("response" => "GOOD"));

                        //If the player has already vote
                    } else if ($vote->hasVoted() === "ALREADY_VOTE") {

                        echo json_encode(array("response" => "ALREADY_VOTE"));

                    } else {
                        echo json_encode(array("response" => $vote->hasVoted()));
                    }
                }

            } else {//retry
                echo json_encode(array("response" => "NOT_CONFIRMED"));
            }

        }

    }


}
