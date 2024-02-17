<?php

declare(strict_types=1);

namespace App\Service;

use App\Dao\NewsDAO;
use App\Dao\CommentDAO;

class NewsService
{
    private NewsDAO $newsDAO;
    private CommentDAO $commentDAO;

    public function __construct(NewsDAO $newsDAO, CommentDAO $commentDAO)
    {
        $this->newsDAO = $newsDAO;
        $this->commentDAO = $commentDAO;
    }

    public function displayNewsWithComments(): void
    {
        $newsList = $this->newsDAO->listAll();
        foreach ($newsList as $news) {
            echo "############ NEWS " . $news->getTitle() . " ############\n";
            echo $news->getBody() . "\n";

            $comments = $this->commentDAO->listAll();
            foreach ($comments as $comment) {
                if ($comment->getNewsId() == $news->getId()) {
                    echo "Comment " . $comment->getId() . ": " . $comment->getBody() . "\n";
                }
            }
        }
    }
}


