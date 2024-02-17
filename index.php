<?php

declare(strict_types=1);

use App\Utils\DB;
use App\Dao\NewsDAO;
use App\Dao\CommentDAO;
define('ROOT', __DIR__);
require __DIR__ . '/vendor/autoload.php';

$dsn = 'mysql:host=127.0.0.1;dbname=optimyphptest';
$user = 'root';
$password = '';

$db = new DB($dsn, $user, $password);
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
