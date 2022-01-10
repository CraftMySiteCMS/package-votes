<?php

use CMS\Controller\votes\votesController;
use CMS\Router\Router;

require_once('Lang/'.getenv("LOCALE").'.php');

/** @var $router Router Main router */


/* Administration scope of package */
$router->scope('/cms-admin/', function($router) {
    $router->get('votes/config', 'votes#votesConfig');
    $router->post('votes/config', 'votes#votesConfigPost');

    $router->get('votes/top/', 'votes#topVotes');

    $router->get('votes/site/list', 'votes#listSites');
    $router->post('votes/site/list', 'votes#votesSitesEditPost');

    $router->get('votes/site/delete/:id', function($id) {
        (new votesController)->deleteSitePostAdmin($id);
    })->with('id', '[0-9]+');

    $router->get('votes/site/add/', 'votes#addSiteAdmin');
    $router->post('votes/site/add/', 'votes#addSiteAdminPost');

    $router->get('votes/rewards', 'votes#votesRewards');


});

/* PUBLIC PAGE */
$router->scope('/vote', function($router) {
    $router->get('/', "votes#votesPublic");
    $router->post('/', "votes#votesPublic");

    $router->post('/verify', "votes#votesPublicVerify");
});
