<?php

namespace CMS\Model\Votes;

use CMS\Model\manager;

use PDO;
use stdClass;

/**
 * Class @rewardsModel
 * @package votes
 * @author Teyir | CraftMySite <contact@craftmysite.fr>
 * @version 1.0
 */
class rewardsModel extends manager
{

    public int $rewardsId;
    public string $title;
    public ?string $action;
    public int $idSite;
    public int $userId;

    //Get all rewards
    public function fetchAll(): array
    {
        $sql = "SELECT * FROM cms_votes_rewards";
        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute()) {
            return $req->fetchAll();
        }

        return [];
    }

    //Get a reward
    public function fetch($id): void
    {
        $var = array(
            "rewards_id" => $id
        );

        $sql = "SELECT * FROM cms_votes_rewards WHERE rewards_id=:rewards_id";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $result = $req->fetch();
            $this->rewardsId = $result['rewards_id'];
            $this->title = $result['title'];
            $this->action = $result['action'];
        }

    }

    //Add a new Reward
    public function addReward(): void
    {
        $var = array(
            'title' => $this->title,
            'action' => $this->action
        );

        $sql = "INSERT INTO cms_votes_rewards (title, action) VALUES (:title, :action)";
        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    //Delete a reward
    public function delete($id): void
    {
        $var = array(
            "rewards_id" => $id
        );

        $sql = "DELETE FROM cms_votes_rewards WHERE rewards_id=:rewards_id";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    //Update a reward
    public function update():void
    {

        $var = array(
            "rewards_id" => $this->rewardsId,
            "title" => $this->title,
            "action" => $this->action
        );

        $sql = "UPDATE cms_votes_rewards SET title=:title, action=:action WHERE rewards_id=:rewards_id";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($var);

    }

    //select reward
    public function selectReward()
    {

        //Select the reward action, rewards_id and id site with the site id
        $var = array(
          "id" => $this->idSite
        );

        $sql = "SELECT cms_votes_sites.rewards_id, cms_votes_sites.id ,cms_votes_rewards.action FROM cms_votes_sites 
                    JOIN cms_votes_rewards ON cms_votes_sites.rewards_id = cms_votes_rewards.rewards_id 
                    WHERE cms_votes_sites.id =:id LIMIT 1;";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $result = $req->fetch();
            $this->idSite = $result['id'];
            $this->rewardsId = $result['rewards_id'];
            $this->action = $result['action'];
        }


        //Detect type
        switch (json_decode($result['action'])->type){

            case "votepoints":
                $this->giveRewardVotePoints(json_decode($result['action'])->amount);
                break;

            case "votepoints-random":
                $this->giveRewardVotePointsRandom(json_decode($result['action'])->amount->min, json_decode($result['action'])->amount->min);
                break;

        }


    }

    public function detectFirstVotePointsReward()
    {
        $var = array(
          "id_user" => $this->userId
        );

        $sql = "SELECT id_user FROM cms_votes_votepoints WHERE id_user=:id_user";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute($var)){
            $lines =  $req->fetchAll();

            if (count($lines) <= 0){
                return true;
            } else{
                return false;
            }

        }
    }

    //Reward → votepoints
    public function giveRewardVotePoints($amount)
    {
        //If the player has never get a reward
        if ($this->detectFirstVotePointsReward()){
            $var = array(
              "id_user" => $this->userId,
              "amount" => $amount
            );

            $sql = "INSERT INTO cms_votes_votepoints (id_user, amount) VALUES (:id_user, :amount)";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $req->execute($var);

        }else{ //If the player has already get a reward
            $var = array(
                "id_user" => $this->userId,
                "amount" => $amount
            );

            $sql = "UPDATE cms_votes_votepoints SET amount = amount+:amount WHERE id_user=:id_user";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $req->execute($var);
        }

    }

    //Reward → votepoints random
    public function giveRewardVotePointsRandom($min, $max)
    {
        $amount = rand($min, $max);

        //If the player has never get a reward
        if ($this->detectFirstVotePointsReward()){
            $var = array(
                "id_user" => $this->userId,
                "amount" => $amount
            );

            $sql = "INSERT INTO cms_votes_votepoints (id_user, amount) VALUES (:id_user, :amount)";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $req->execute($var);

        }else{ //If the player has already get a reward
            $var = array(
                "id_user" => $this->userId,
                "amount" => $amount
            );

            $sql = "UPDATE cms_votes_votepoints SET amount = amount+:amount WHERE id_user=:id_user";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $req->execute($var);
        }

        $this->setLog();

    }


    //Log reward
    public function setLog()
    {
        $var = array(
            "user_id" => $this->userId,
            "reward_id" => $this->rewardsId
        );

        $sql = "INSERT INTO cms_votes_logs_rewards (user_id, reward_id) VALUES (:user_id, :reward_id)";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($var);
    }
}