<?php

require 'class/Comment.php';

class CommentDAO
{
    private $db;

    public function __construct(DB $db) {
        $this->db = $db;
    }

    public function listComments() {
        $comments = [];
        $rows = $this->db->select("SELECT * FROM `comment`");

        foreach($rows as $row) {
            $n = new Comment();
            $comments[] = $n->setId($row['id'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at'])
                ->setNewsId($row['news_id']);
        }

        return $comments;
    }

    public function addCommentForNews($body, $newsId)
    {
        $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES (?, ?, ?)";
        return $this->db->executeUpdate($sql, [$body, date('Y-m-d H:i:s'), $newsId]);
    }

    public function deleteComment($id)
    {
        $sql = "DELETE FROM `comment` WHERE `id`=" . $id;
        return $this->db->exec($sql);
    }
}
