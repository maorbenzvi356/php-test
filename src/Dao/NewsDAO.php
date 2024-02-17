<?php

declare(strict_types=1);

namespace App\Dao;

use App\Model\News;
use App\Utils\DB;
use PDOException;

class NewsDAO implements DAOInterface
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
    public function listAll(): array
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
    public function add($data): int|false
    {
        try {
            $title = $data['title'];
            $body = $data['body'];
            $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUE(:title, :body, :created_at)";

            return $this->db->executeUpdate($sql, [
                ':title' => $title,
                ':body' => $body,
                ':created_at' => date('Y-m-d')
            ]);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * deletes a news, and also linked comments
     * We add a transaction to make sure that we cana rollback incase we encounter an error.
     * This way the attempted changes will get discarded
     * Risks: Deleting database entries can be dangerous. Therefore if something goes wrong, we want to make
     * sure that the entire transaction failed
     */
    public function delete($id): int|false
    {
        $this->db->beginTransaction();

        try {
            $this->deleteCommentsByNewsId($id);

            $sql = "DELETE FROM `news` WHERE `id` = :id";
            $result = $this->db->executeUpdate($sql, [':id' => $id]);
            $this->deleteCommentsByNewsId($id);
            $this->db->commit();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->db->rollBack();

            return false;
        }
    }

    public function deleteCommentsByNewsId(int $id): void
    {
        $commentsDAO = new CommentDAO($this->db);
        $comments = $commentsDAO->listAll();

        $idsToDelete = [];
        foreach ($comments as $comment) {
            if ($comment->getNewsId() == $id) {
                $idsToDelete[] = $comment->getId();
            }
        }

        foreach ($idsToDelete as $id) {
            $commentsDAO->delete($id);
        }
    }
}
