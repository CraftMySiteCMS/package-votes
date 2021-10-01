<?php

namespace CMS\Model\Votes ;

use CMS\Model\manager;

use PDO;
use stdClass;

/**
 * Class @sitesModel
 * @package votes
 * @author Teyir | CraftMySite <contact@craftmysite.fr>
 * @version 1.0
 */



class sitesModel extends manager
{
    //Sites
    public string $title;
    public int $time;
    public string $idUnique;
    public string $url;
    public int $rewardsId;
    public int $siteId;



    //Add a new Website
    public function addSite()
    {
        $var = array(
            'title' => $this->title,
            'time' => $this->time,
            'id_unique' => $this->idUnique,
            'url' => $this->url,
            'rewards_id' => $this->rewardsId
        );

        $sql = "INSERT INTO cms_votes_sites (title, time, id_unique, url, rewards_id) VALUES (:title, :time, :id_unique, :url, :rewards_id)";
        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    //Get all sites
    public function fetchAll(): array
    {
        $sql = "SELECT * FROM cms_votes_sites";
        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $res = $req->execute();

        if ($res) {
            return $req->fetchAll();
        }

        return [];

    }


    //Get a website
    public function fetch($id)
    {
        $var = array(
            "id" => $id
        );

        $sql = "SELECT * FROM cms_votes_sites WHERE id=:id";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $result = $req->fetch();
            foreach ($result as $key => $property) {

                //to camel case all keys (top_show => topShow (for $this->>topShow))
                $key = explode('_', $key);
                $firstElement = array_shift($key);
                $key = array_map('ucfirst', $key);
                array_unshift($key, $firstElement);
                $key = implode('', $key);

                if (property_exists(sitesModel::class, $key)) {
                    $this->$key = $property;
                }
            }
        }
    }

    //Edit a website
    public function update()
    {
        $info = array(
            "id" => $this->siteId,
            "title" => $this->title,
            "time" => $this->time,
            "id_unique" => $this->idUnique,
            "url" => $this->url,
            "rewards_id" => $this->rewardsId
        );

        $sql = "UPDATE cms_votes_sites SET title=:title, time=:time, id_unique=:id_unique, url=:url, rewards_id=:rewards_id WHERE id=:id";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($info);
    }

    //Delete a site
    public function delete($id)
    {
        $info = array(
            "id" => $id
        );

        $sql = "DELETE FROM cms_votes_sites WHERE id=:id";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($info);
    }
}