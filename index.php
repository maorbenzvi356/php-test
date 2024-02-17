<?php

declare(strict_types=1);

use App\Utils\DB;
use App\Dao\NewsDAO;
use App\Dao\CommentDAO;

define('ROOT', __DIR__);
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dbName = $_ENV['DB_DATABASE'];
$dbHost = $_ENV['DB_HOST'];
$dbUser = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];
$dsn = "mysql:host=" . $dbHost . ";dbname=" . $dbName;

$db = new DB($dsn, $dbUser, $dbPassword);
$newsDAO = new NewsDAO($db);
$commentDAO = new CommentDAO($db);

foreach ($newsDAO->listNews() as $news) {
    echo("############ NEWS " . $news->getTitle() . " ############\n");
    echo($news->getBody() . "\n");
    foreach ($commentDAO->listComments() as $comment) {
        if ($comment->getNewsId() == $news->getId()) {
            echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
        }
    }
}

$c = $commentDAO->listComments();
