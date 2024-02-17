<?php

declare(strict_types=1);

namespace App\Dao;

use App\Model\Comment;
use App\Utils\DB;
use PDOException;

class CommentDAO implements DAOInterface
{
    private DB $db;

    /**
     * Constructs a CommentDAO with a DB connection.
     *
     * @param DB $db The DB instance to use for database operations.
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * Retrieves all comments from the database.
     *
     * @return array An array of comments.
     */
    public function listAll(): array
    {
        try {
            $comments = [];
            $rows = $this->db->select("SELECT * FROM `comment`");

            foreach ($rows as $row) {
                $comment = new Comment();
                $comments[] = $comment->setId($row['id'])
                    ->setBody($row['body'])
                    ->setCreatedAt($row['created_at'])
                    ->setNewsId($row['news_id']);
            }

            return $comments;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function add($data): int|false
    {
        // Extract data specific to Comment, including newsId
        $newsId = $data['newsId'];
        $body = $data['body'];
        $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES (?, ?, ?)";
        return $this->db->executeUpdate($sql, [$body, date('Y-m-d H:i:s'), $newsId]);
    }

    public function delete($id): int|false
    {
        try {
            $sql = "DELETE FROM `comment` WHERE `id`=" . $id;

            return $this->db->executeUpdate($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
