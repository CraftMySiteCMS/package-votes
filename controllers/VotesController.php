<?php
namespace CMS\Controller\Votes;

use CMS\Controller\coreController;
use CMS\Controller\users\usersController;
use CMS\Model\votes\sitesModel;
use CMS\Model\votes\configModel;
use CMS\Model\votes\rewardsModel;
use CMS\Model\users\usersModel;


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

        header('location: ../site/list/');

    }

    public function deleteSitePostAdmin($id){
        usersController::isAdminLogged();

        $votes = new sitesModel();
        $votes->siteId = $id;
        $votes->delete($id);

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
    /* ///////////////////// PARTIE FRONT THEME /////////////////////*/
    /* //////////////////////////////////////////////////////////////*/





}