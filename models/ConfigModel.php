<?php

namespace CMS\Model\Votes ;

use CMS\Model\manager;

use PDO;
use stdClass;

/**
 * Class @configModel
 * @package votes
 * @author Teyir | CraftMySite <contact@craftmysite.fr>
 * @version 1.0
 */



class configModel extends manager {
    //Config
    public int $topShow;
    public int $reset;
    public int $autoTopRewardActive;
    public string $autoTopReward;



    //Get the config
    public function fetch(){

        $sql = "SELECT * FROM cms_votes_config LIMIT 1";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if($req->execute()) {
            $result = $req->fetch();
            foreach ($result as $key => $property) {

                //to camel case all keys (top_show => topShow (for $this->>topShow))
                $key = explode('_', $key);
                $firstElement = array_shift($key);
                $key = array_map('ucfirst', $key);
                array_unshift($key, $firstElement);
                $key = implode('', $key);

                if (property_exists(configModel::class, $key)) {
                    $this->$key = $property;
                }
            }
        }
    }

    //Update the config
    public function update(){
        $info = array(
            "top_show" => $this->topShow,
            "reset" => $this->reset,
            "auto_top_reward_active" => $this->autoTopRewardActive,
            "auto_top_reward" => $this->autoTopReward
        );

        $sql = "UPDATE cms_votes_config SET top_show=:top_show, reset=:reset, auto_top_reward_active=:auto_top_reward_active, auto_top_reward=:auto_top_reward";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($info);
    }
}