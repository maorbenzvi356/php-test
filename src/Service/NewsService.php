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
        // Fetch all news items
        $newsList = $this->newsDAO->listAll();

        if (empty($newsList)) {
            echo "No news items found.\n";
            return;
        }

        // Prepare simple list of newsIds given all available news
        $newsIds = array_map(function ($news) { return $news->getId(); }, $newsList);

        // Fetch comments for these news IDs
        $allComments = $this->commentDAO->listCommentsByNewsIds($newsIds);

        // Organize comments by news ID for easy access
        $commentsByNewsId = [];
        foreach ($allComments as $comment) {
            $commentsByNewsId[$comment->getNewsId()][] = $comment;
        }

        /*
         * Display news and their comments
         * Previously the function included a nested for loop, where no matter the news id, all comments were queried.
         * This is inefficient, and can cause a longer query time given larger datasets.
         * In our case, this array is pre-populated with comments organized by their associated news ID, allowing for
         * efficient access without the need for additional database queries per news item.
         */
        foreach ($newsList as $news) {
            echo "############ NEWS " . $news->getTitle() . " ############\n";
            echo $news->getBody() . "\n";

            if (!empty($commentsByNewsId[$news->getId()])) {
                /*
                 * Iterate through the comments specifically related to this news item and display them.
                 * This approach significantly reduces the computational overhead compared to fetching comments
                 * individually for each news item, thereby addressing the N+1 query problem.
                 */
                foreach ($commentsByNewsId[$news->getId()] as $comment) {
                    echo "Comment " . $comment->getId() . ": " . $comment->getBody() . "\n";
                }
            } else {
                echo "No comments for this news item.\n";
            }

            echo "\n"; // Add a newline for better readability between news items
        }
    }
}


