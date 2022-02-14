<?php

namespace CMS\Model\Votes;

use CMS\Model\manager;

use PDO;
use stdClass;

/**
 * Class @statsModel
 * @package votes
 * @author Teyir | CraftMySite <contact@craftmysite.fr>
 * @version 1.0
 */
class statsModel extends manager
{

    /**
     * @param string $type insert the type you want: "all", "month", "week", "day", "hour", "minute"
     * @return void
     */
    public function statsVotes($type): array
    {

        if ($type === "all") {

            $sql = "SELECT * FROM cms_votes_votes";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $res = $req->execute();

        } else if ($type === "month" || $type === "week" || $type === "day" || $type === "hour" || $type === "minute") {

            if ($type === "month") {
                $rangeStart = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                $rangeFinish = date("Y-m-d 00:00:00", strtotime("last day of this month"));
            }

            switch ($type) {
                case "month":
                    $rangeStart = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                    $rangeFinish = date("Y-m-d 00:00:00", strtotime("last day of this month"));
                    break;
                case "week":
                    $rangeStart = date("Y-m-d 00:00:00", strtotime("monday this week"));
                    $rangeFinish = date("Y-m-d 00:00:00", strtotime("sunday this week"));
                    break;
                case "day":
                    $rangeStart = date("Y-m-d 00:00:00");
                    $rangeFinish = date("Y-m-d 23:59:59");
                    break;
                case "hour":
                    $rangeStart = date("Y-m-d h:00:00");
                    $rangeFinish = date("Y-m-d h:00:00", strtotime("+1 hour"));
                    break;
                case "minute":
                    $rangeStart = date("Y-m-d h:i:00");
                    $rangeFinish = date("Y-m-d h:i:00", strtotime("+1 minute"));
                    break;
            }


            $var = array(
                "range_start" => $rangeStart,
                "range_finish" => $rangeFinish
            );

            $sql = "SELECT * FROM cms_votes_votes WHERE date BETWEEN (:range_start) AND (:range_finish)";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $res = $req->execute($var);
        }

        if ($res) {
            return $req->fetchAll();
        }

        return [];

    }

    public function statsVotesSitesTotaux($title): int
    {

        $var = array(
            "title" => $title
        );

        $sql = 'SELECT cms_votes_votes.id, cms_votes_sites.title FROM cms_votes_votes 
                    JOIN cms_votes_sites ON cms_votes_votes.id_site = cms_votes_sites.id WHERE cms_votes_sites.title = :title;';

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $res = $req->execute($var);

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return "";

    }

    public function statsVotesSitesMonth($title): int
    {

        $rangeStart = date("Y-m-d 00:00:00", strtotime("first day of this month"));
        $rangeFinish = date("Y-m-d 00:00:00", strtotime("last day of this month"));

        $var = array(
            "title" => $title,
            "range_start" => $rangeStart,
            "range_finish" => $rangeFinish
        );

        $sql = 'SELECT cms_votes_votes.id, cms_votes_sites.title FROM cms_votes_votes 
                    JOIN cms_votes_sites ON cms_votes_votes.id_site = cms_votes_sites.id 
                    WHERE cms_votes_sites.title = :title
                    AND cms_votes_votes.date BETWEEN (:range_start) AND (:range_finish);';

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $res = $req->execute($var);

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return "";

    }

    public function getNumberOfSites(): int
    {
        $sql = "SELECT id FROM cms_votes_sites";
        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $res = $req->execute();

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return "";
    }

}