<?php

namespace silverorange\DevTest\Model;

class Post
{
    public string $id;
    public string $title;
    public string $body;
    public string $created_at;
    public string $modified_at;
    public string $author;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->title = $data['title'] ?? '';
        $this->body = $data['body'] ?? '';
        $this->created_at = $data['created_at'] ?? '';
        $this->modified_at = $data['modified_at'] ?? '';
        $this->author = $data['author'] ?? '';
    }

    // Fetch all posts
    public static function getAll($db): array
    {
        $stmt = $db->query("
            SELECT 
                posts.id,
                posts.title,
                posts.body,
                posts.created_at,
                posts.modified_at,
                authors.full_name AS author
            FROM posts
            INNER JOIN authors ON posts.author = authors.id
            ORDER BY posts.created_at DESC
        ");

        $rows = $stmt->fetchAll($db::FETCH_ASSOC);

        // Map each row to a Post object
        return array_map(fn($row) => new Post($row), $rows);
    }


}
