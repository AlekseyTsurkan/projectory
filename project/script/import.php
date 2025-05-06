<?php
require __DIR__.'/../public/config.php';

$pdo = new PDO(
    "mysql:host=".DB_CONFIG['host'].";dbname=".DB_CONFIG['student'].";charset=".DB_CONFIG['charset'],
    DB_CONFIG['root'],
    DB_CONFIG[''],
    DB_CONFIG['options']
);


$postsData = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/posts'), true);
$commentsData = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/comments'), true);

$totalPosts = 0;
$totalComments = 0;

try 
{
   
    $pdo->beginTransaction();
    $stmtPosts = $pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
    
    foreach ($postsData as $post) 
    {
        $stmtPosts->execute([
            $post['userId'],
            substr($post['title'], 0, 255),
            $post['body']
        ]);
        $totalPosts++;
    }
    $pdo->commit();

    // Импорт комменов
    $pdo->beginTransaction();
    $stmtComments = $pdo->prepare("INSERT INTO comments (post_id, name, email, body) VALUES (?, ?, ?, ?)");
    
    foreach ($commentsData as $comment)
     {
        $stmtComments->execute([
            $comment['postId'],
            substr($comment['name'], 0, 255),
            filter_var($comment['email'], FILTER_SANITIZE_EMAIL),
            $comment['body']
        ]);
        $totalComments++;
    }
    $pdo->commit();

    echo "Загружено $totalPosts записей и $totalComments комментариев\n";

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Ошибка импорта: " . $e->getMessage());
}