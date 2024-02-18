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

    /**
     * Retrieves comments for a given array of news IDs.
     * @param array $newsIds The array of news IDs.
     * @return array An array of Comment objects.
     */
    public function listCommentsByNewsIds(array $newsIds): array
    {
        //It's always good to return immedietely in case nothing to process
        if (empty($newsIds)) {
            return [];
        }

        // Creating placeholders for each news ID
        $placeholders = implode(',', array_fill(0, count($newsIds), '?'));
        $sql = "SELECT * FROM `comment` WHERE `news_id` IN ($placeholders)";

        // Since your DB class's select method handles parameter binding,
        // you can directly pass $newsIds as the parameter array.
        try {
            $rows = $this->db->select($sql, $newsIds);

            $comments = [];
            foreach ($rows as $row) {
                $comment = new Comment();
                $comments[] = $comment
                    ->setId((int)$row['id'])
                    ->setBody($row['body'])
                    ->setCreatedAt($row['created_at'])
                    ->setNewsId((int)$row['news_id']);
            }

            return $comments;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }
}
