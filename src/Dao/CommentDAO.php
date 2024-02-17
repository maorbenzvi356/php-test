<?php

declare(strict_types=1);

namespace App\Dao;

use App\Model\Comment;
use App\Utils\DB;

class CommentDAO
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
    public function listComments(): array
    {
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
    }

    public function addCommentForNews($body, $newsId): int
    {
        $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES (?, ?, ?)";
        return $this->db->executeUpdate($sql, [$body, date('Y-m-d H:i:s'), $newsId]);
    }

    public function deleteComment($id): int
    {
        $sql = "DELETE FROM `comment` WHERE `id`=" . $id;
        return $this->db->executeUpdate($sql);
    }
}
