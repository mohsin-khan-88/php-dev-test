<?php

// Enable strict type checking
declare(strict_types=1);

namespace silverorange\DevTest;

// Autoload dependencies
require __DIR__ . '/../vendor/autoload.php';


$config = new Config();
$db = (new Database($config->dsn))->getConnection();

echo "Starting post import \n";

// JSON files
$files = glob(__DIR__ . '/../data/*.json');

if (!$files) {
    echo "No JSON files found in /data.\n";
    exit(0);
}

// Loop through each JSON file
foreach ($files as $file) {
    echo "Processing: {$file}\n";

    $content = file_get_contents($file);
    if ($content === false) {
        echo "Could not read file: {$file}\n";
        continue;
    }

    // Decode JSON
    $data = json_decode($content, true);

    // Check JSON errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON decode error in {$file}: " . json_last_error_msg() . "\n";
        continue;
    }

    // Ensure required keys exist
    $requiredKeys = ['id', 'title', 'body', 'created_at', 'modified_at', 'author'];
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key])) {
            echo "Missing key '{$key}' in {$file}. Skipping.\n";
            continue 2;
        }
    }

    try {
        // Insert or update post
        $stmt = $db->prepare("
            INSERT INTO posts (id, title, body, created_at, modified_at, author)
            VALUES (:id, :title, :body, :created_at, :modified_at, :author)
            ON CONFLICT (id) DO UPDATE
            SET title = EXCLUDED.title,
                body = EXCLUDED.body,
                modified_at = EXCLUDED.modified_at,
                author = EXCLUDED.author
        ");

        // Bind values
        $stmt->execute([
            ':id'         => $data['id'],
            ':title'      => $data['title'],
            ':body'       => $data['body'],
            ':created_at' => $data['created_at'],
            ':modified_at' => $data['modified_at'],
            ':author'     => $data['author'],
        ]);

        echo "Imported post: {$data['title']}\n";
    } catch (\PDOException $e) {
        echo "Failed to import {$file}: " . $e->getMessage() . "\n";
    }
}

echo "Import complete \n";
