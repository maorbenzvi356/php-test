<?php

declare(strict_types=1);

namespace App\Dao;

use App\Model\News;
use App\Utils\DB;

class NewsDAO
{
    private DB $db;

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
    public function listNews(): array
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
    public function addNews($title, $body): string|false
    {
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) 
                VALUES('" . $title . "','" . $body . "','" . date('Y-m-d') . "')";
        $this->db->exec($sql);
        return $this->db->lastInsertId($sql);
    }

    /**
     * deletes a news, and also linked comments
     */
    public function deleteNews($id): int
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
        return $this->db->executeUpdate($sql);
    }
}
