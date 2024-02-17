<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Dao\CommentDAO;
use App\Dao\NewsDAO;
use App\Utils\ConnectionFactory;
use App\Service\NewsService;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Create database connection
$db = (new ConnectionFactory())->createConnection();

// Instantiate DAOs
$newsDAO = new NewsDAO($db);
$commentDAO = new CommentDAO($db);

// Use the NewsService to display news and comments
$newsService = new NewsService($newsDAO, $commentDAO);
$newsService->displayNewsWithComments();
