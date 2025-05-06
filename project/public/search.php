<?php
require 'config.php';

header('Content-Type: application/json');

try 
{
    $pdo = new PDO(
        "mysql:host=".DB_CONFIG['host'].";dbname=".DB_CONFIG['student'].";charset=".DB_CONFIG['charset'],
        DB_CONFIG['root'],
        DB_CONFIG[''],
        DB_CONFIG['options']
    );

    $query = $_GET['query'] ?? '';
    
    if (strlen($query) < 3) {
        throw new InvalidArgumentException('Минимум 3 символа');
    }

    $searchTerm = $pdo->quote('%'.$query.'%');
    
    $sql = "SELECT p.title, c.body 
            FROM comments c
            JOIN posts p ON c.post_id = p.id
            WHERE c.body LIKE :query
            LIMIT 20";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['query' => "%$query%"]);

    $results = array_map(function($item) use ($query) {
        return [
            'title' => $item['title'],
            'content' => preg_replace(
                "/($query)/i", 
                '<span class="highlight">$1</span>', 
                $item['body']
            )
        ];
    }, $stmt->fetchAll());

    echo json_encode($results);

} catch(Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}