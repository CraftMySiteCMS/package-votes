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


    public int $idSite;
    public int $idUser;
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

    public function storeVote(): void
    {
        $var = array(
            "id_user" => $this->idUser,
            "ip" => $this->ipPlayer,
            "id_site" => $this->idSite
        );

        $sql = "INSERT INTO cms_votes_votes (id_user, ip, id_site) VALUES (:id_user, :ip, :id_site)";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    public function hasVoted(){
        //Check if the player has already vote for the website id
        $var = array(
            "id_user" => $this->idUser,
            "id_site" => $this->idSite
        );

        $sql = "SELECT * FROM cms_votes_votes WHERE id_user = :id_user AND id_site = :id_site";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute($var)){
            $lines =  $req->fetchAll();

            if (count($lines) <= 0){
                return "NEW_VOTE";
            }

        }

        //Get current date
        $currentDate = time();

        //Get the vote time
        $var = array(
            "id" => $this->idSite
        );

        $sql = "SELECT time FROM cms_votes_sites WHERE id = :id";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if($req->execute($var)) {
            $res = $req->fetch();
            $time = $res['time']; // Vote time

        }

        //Get the last vote
        $var = array(
          "id_user" => $this->idUser,
          "id_site" => $this->idSite
        );

        $sql = "SELECT date FROM cms_votes_votes WHERE id_user = :id_user AND id_site = :id_site ORDER BY `cms_votes_votes`.`date` DESC LIMIT 1";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if($req->execute($var)) {
            $res = $req->fetch();
        }

        //Creating the dates
        $dateLatest = strtotime($res['date']); // Last vote date
        $nextVoteDate = $dateLatest + ($time * 60);

        //Converting the dates
        $dateLatest = date("Y-m-d h:i:s", $dateLatest);
        $nextVoteDate = date("Y-m-d h:i:s", $nextVoteDate);
        $currentDate = date("Y-m-d h:i:s", $currentDate);

        //Check if the player has already vote or not
        if ($currentDate >= $nextVoteDate || $currentDate === $dateLatest){
            $this->storeVote();
            return "GOOD";
        } else{
            return "ALREADY_VOTE";
        }


    }

    function generateToken(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 128; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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
