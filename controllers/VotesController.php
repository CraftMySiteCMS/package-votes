<?php
namespace CMS\Controller\Votes;

use CMS\Controller\coreController;
use CMS\Controller\Menus\menusController;
use CMS\Controller\users\usersController;
use CMS\Model\Votes\checkVotesModel;
use CMS\Model\votes\sitesModel;
use CMS\Model\votes\configModel;
use CMS\Model\votes\rewardsModel;
use CMS\Model\users\usersModel;
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

    public function votesConfig() {

        $config = new configModel();
        $config->fetch();

        //Include the view file ("views/config.admin.view.php").
        view('votes', 'config.admin', ["config" => $config], 'admin');
    }

    public function votesConfigPost(){
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

    public function addSiteAdmin() {

        $rewards = new rewardsModel();
        $rewards = $rewards->fetchAll();

        view('votes', 'add_site.admin', ["rewards" => $rewards], 'admin');
    }

    public function addSiteAdminPost(){
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


    public function listSites() {

        $votes = new sitesModel();
        $votesList = $votes->fetchAll();

        $rewards = new rewardsModel();
        $rewards = $rewards->fetchAll();


        view('votes', 'list_sites.admin', ["votesList" => $votesList, "rewards" => $rewards], 'admin');
    }

    public function votesSitesEdit(){

        $votes = new sitesModel();
        $votes->fetch($_POST['siteId']);

        view('votes', 'list_sites.admin', ["votes" => $votes], 'admin');
    }

    public function votesSitesEditPost(){
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

    public function deleteSitePostAdmin($id){
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

    public function votesRewards() {


        view('votes', 'rewards.admin', [], 'admin');
    }
    

    /* ///////////////////// TOP VOTES /////////////////////*/

    public function topVotes() {

        //$votes = new topModel();

        //Show top votes

        view('votes', 'top.admin', [], 'admin');
    }



    /* //////////////////////////////////////////////////////////////*/
    /* ///////////////////// PUBLIC VOTE SECTION ////////////////////*/
    /* //////////////////////////////////////////////////////////////*/

    public function votesPublic(){
        //Default controllers (important)
        $core = new coreController();
        $menu = new menusController();

        $vote = new votesModel();

        $sites = new sitesModel();
        $sites = $sites->fetchAll();

        //Include the public view file ("public/themes/$themePath/views/votes/main.view.php")
        view('votes', 'main', ["votes" => $vote, "sites" => $sites,
            "core" => $core, "menu" => $menu], 'public');
    }

    public function votesPublicVerify(){
        /* Error section */
        $errors = array();

        if (empty(filter_input(INPUT_POST, "url"))){
            $errors['name'] = "Url is null";
        }


        $vote = new votesModel();
        $user = new usersModel();


        $url = filter_input(INPUT_POST, "url");

        $site = $vote->getSite($url);
        $vote->idUnique = $site['id_unique'];
        $vote->idSite = $site['id'];

        $vote->ipPlayer = "";
        //$vote->getClientIp(); todo get l'ip de l'utilisateur

        //Get user id
        $user->fetch($_SESSION['cmsUserId']);
        $vote->idUser = $user->userId;


        if ($vote->check($url) == true){

            if ($vote->hasVoted() === "NEW_VOTE"){

                //Store the vote
                $vote->storeVote();
                //Get reward

                echo json_encode(array("response" =>"GOOD-NEW_VOTE"));
            }else{
                //If the player has already vote
                if ($vote->hasVoted()){

                    echo json_encode(array("response" =>"ALREADY_VOTE"));

                }else{
                    //Store the vote
                    $vote->storeVote();

                    //Get reward

                    echo json_encode(array("response" =>"GOOD"));
                }
            }

        } else{//retry
            echo json_encode(array("response" =>"NOT_CONFIRMED"));
        }


    }





}