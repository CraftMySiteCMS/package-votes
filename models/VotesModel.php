<?php

namespace CMS\Model\Votes ;

use CMS\Model\manager;

use PDO;
use stdClass;

/**
 * Class @votesModel
 * @package votes
 * @author Teyir | CraftMySite <contact@craftmysite.fr>
 * @version 1.0
 */

class votesModel extends manager{
    public int $id;
    public string $title;
    public string $url;
    public int $time;
    public string $idUnique;
    public int $rewardsId;
    public string $dateCreate;


    public int $idPlayer;
    public string $pseudo;
    public string $ipPlayer;



    public function getSite($url): array
    {
        $var = array(
            "url" => $url
        );

        $sql = "SELECT * FROM cms_votes_sites WHERE url=:url";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if($req->execute($var)) {
            return $req->fetch();
        }

        return [];
    }


    //Return true if the player can vote
    public function check($url)
    {

        //List of all websites:
        if (strpos($url, 'serveur-prive.net')){
            $result = @file_get_contents("https://serveur-prive.net/api/vote/json/$this->idUnique/$this->ipPlayer");
            if ($result && ($result = json_decode($result, true))){
                if ($result === false || intval($result['status']) == 1){
                    return true;
                }
            }
        } elseif (strpos($url, 'serveur-minecraft-vote.fr')){
            $result = @file_get_contents("https://serveur-minecraft-vote.fr/api/v1/servers/$this->idUnique/vote/$this->ipPlayer");
            if ($result && ($result = json_decode($result, true))){
                if ($result['canVote'] === false ) {
                    return true;
                }
            }
        } elseif (strpos($url, 'serveurs-mc.net')){
            $result = @file_get_contents("https://serveurs-mc.net/api/hasVote/$this->idUnique/$this->ipPlayer/10");
            if ($result && ($result = json_decode($result, true))){
                if ($result['hasVote'] === false ) {
                    return true;
                }
            }
        } elseif (strpos($url, 'top-serveurs.net')) {
            $result = @file_get_contents("https://api.top-serveurs.net/v1/votes/check-ip?server_token=$this->idUnique&ip=$this->ipPlayer");
            if ($result && ($result = json_decode($result, true))) {
                return true;
            }
        } elseif (strpos($url, 'serveursminecraft.org')){
            $result = @file_get_contents("https://www.serveursminecraft.org/sm_api/peutVoter.php?id=$this->idUnique&ip=$this->ipPlayer");
            if ($result === "true") {
                return true;
            }
        }

    }


    //Get the client IP
    public function getClientIp(){
        $ipClient = "";
        if (getenv('HTTP_CLIENT_IP')) {
            $this->ipPlayer = getenv('HTTP_CLIENT_IP');
        } else if(getenv('HTTP_X_FORWARDED_FOR')) {
            $this->ipPlayer = getenv('HTTP_X_FORWARDED_FOR');
        } else if(getenv('HTTP_X_FORWARDED')) {
            $this->ipPlayer = getenv('HTTP_X_FORWARDED');
        } else if(getenv('HTTP_FORWARDED_FOR')) {
            $this->ipPlayer = getenv('HTTP_FORWARDED_FOR');
        } else if(getenv('HTTP_FORWARDED')) {
            $this->ipPlayer = getenv('HTTP_FORWARDED');
        } else if(getenv('REMOTE_ADDR')) {
            $this->ipPlayer = getenv('REMOTE_ADDR');
        } else {
            $this->ipPlayer = 'UNKNOWN';
        }
        return $this->ipPlayer;
    }
}
