<?php

use CMS\Controller\votes\votesController;
use CMS\Router\Router;

require_once('lang/'.getenv("LOCALE").'.php');

/** @var $router Router Main router */


/* Administration scope of package */
$router->scope('/cms-admin/', function($router) {
    $router->get('votes/config', 'votes#votesConfig');
    $router->post('votes/config', 'votes#votesConfigPost');

    $router->get('votes/stats/', 'votes#statsVotes');

    $router->get('votes/site/list', 'votes#listSites');
    $router->post('votes/site/list', 'votes#votesSitesEditPost');

    $router->get('votes/site/delete/:id', function($id) {
        (new votesController)->deleteSitePostAdmin($id);
    })->with('id', '[0-9]+');

    $router->get('votes/site/add/', 'votes#addSiteAdmin');
    $router->post('votes/site/add/', 'votes#addSiteAdminPost');

    $router->get('votes/rewards', 'votes#votesRewards');
    $router->post('votes/rewards', 'votes#editRewardPost');

    $router->post('votes/rewards/get', 'votes#getReward');

    $router->post('votes/rewards/add', 'votes#addRewardPost');

    $router->get('votes/rewards/delete/:id', function($id) {
        (new votesController)->deleteRewardPostAdmin($id);
    })->with('id', '[0-9]+');
});

/* PUBLIC PAGE */
$router->scope('/vote', function($router) {
    $router->get('/', "votes#votesPublic");
    $router->post('/', "votes#votesPublic");

    $router->post('/verify', "votes#votesPublicVerify");
});
