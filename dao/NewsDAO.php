<?php

require 'class/News.php';

class NewsDAO
{
    private $db;

    public function __construct(DB $db) {
        $this->db = $db;
    }

    public function listNews()
    {
        $rows = $this->db->select("SELECT * FROM `news`");

        $news = [];
        foreach ($rows as $row) {
            $n = new News();
            $news[] = $n->setId($row['id'])
                ->setTitle($row['title'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at']);
        }

        return $news;
    }

    /**
     * add a record in news table
     */
    public function addNews($title, $body)
    {
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES('". $title . "','" . $body . "','" . date('Y-m-d') . "')";
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

        foreach($idsToDelete as $id) {
            $commentsDAO->deleteComment($id);
        }

        $sql = "DELETE FROM `news` WHERE `id`=" . $id;
        return $this->db->exec($sql);
    }
}
