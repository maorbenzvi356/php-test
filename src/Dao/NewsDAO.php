<?php

namespace App\Dao;

use App\Model\News;
use App\Utils\DB;

class NewsDAO
{
    private $db;

    /**
     * Constructs a NewsDAO with a DB connection.
     *
     * @param DB $db The DB instance to use for database operations.
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * Retrieves all news from the database.
     *
     * @return array An array of news items.
     */
    public function listNews()
    {
        $rows = $this->db->select("SELECT * FROM `news`");

        $newsList = [];
        foreach ($rows as $row) {
            $news = new News();
            $newsList[] = $news->setId($row['id'])
                ->setTitle($row['title'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at']);
        }

        return $newsList;
    }

    /**
     * add a record in news table
     */
    public function addNews($title, $body)
    {
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) 
                VALUES('" . $title . "','" . $body . "','" . date('Y-m-d') . "')";
        $this->db->exec($sql);
        return $this->db->lastInsertId($sql);
    }

    /**
     * deletes a news, and also linked comments
     */
    public function deleteNews($id)
    {
        $commentsDAO = new CommentDAO($this->db);
        $idsToDelete = [];

        foreach ($commentsDAO as $comment) {
            if ($comment->getNewsId() == $id) {
                $idsToDelete[] = $comment->getId();
            }
        }

        foreach ($idsToDelete as $id) {
            $commentsDAO->deleteComment($id);
        }

        $sql = "DELETE FROM `news` WHERE `id`=" . $id;
        return $this->db->exec($sql);
    }
}
