<?php

declare(strict_types=1);

use App\Dao\NewsDAO;
use App\Dao\CommentDAO;
use App\Utils\ConnectionFactory;
use Dotenv\Dotenv;

define('ROOT', __DIR__);
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = (new ConnectionFactory())->createConnection();
$newsDAO = new NewsDAO($db);
$commentDAO = new CommentDAO($db);

foreach ($newsDAO->listAll() as $news) {
    echo("############ NEWS " . $news->getTitle() . " ############\n");
    echo($news->getBody() . "\n");
    foreach ($commentDAO->listAll() as $comment) {
        if ($comment->getNewsId() == $news->getId()) {
            echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
        }
    }
}

$c = $commentDAO->listAll();
