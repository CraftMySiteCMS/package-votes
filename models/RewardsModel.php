<?php
namespace CMS\Model\Votes ;

use CMS\Model\manager;

use PDO;
use stdClass;

/**
 * Class @rewardsModel
 * @package votes
 * @author Teyir | CraftMySite <contact@craftmysite.fr>
 * @version 1.0
 */



class rewardsModel extends manager {

    public int $rewardsId;
    public string $rewardsTitles;

    //Get all rewards
    public function fetchAll(): array
    {
        $sql = "SELECT rewards_id, title FROM cms_votes_rewards";
        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if($req->execute()) {
            return $req->fetchAll();
        }

        return [];
    }

    //Get a reward
    public function fetch($id)
    {
        $var = array(
            "rewards_id" => $id
        );

        $sql = "SELECT * FROM cms_votes_rewards WHERE rewards_id=:rewards_id";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $result = $req->fetch();
            foreach ($result as $key => $property) {
                if (property_exists(sitesModel::class, $key)) {
                    $this->$key = $property;
                }
            }
        }
    }

}