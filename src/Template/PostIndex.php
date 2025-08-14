<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostIndex extends Layout
{
    protected function renderPage(Context $context): string
    {
        // If no posts
        if (empty($context->posts)) {
            return '<p>No posts available.</p>';
        }

        $html = '<h1>All Posts</h1>';
        $html .= '<div class="total-posts">Total posts:' . $context->content . '</div>';
        $html .= '<ul class="post-list">';
        // Loop each post
        foreach ($context->posts as $post) {
            $title = htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8');
            $author = htmlspecialchars($post->author, ENT_QUOTES, 'UTF-8');
            $id = urlencode($post->id);

            $html .= <<<HTML
                    <li>
                        <a href="/posts/{$id}">{$title}</a>
                        <div class="author">by {$author}</div>
                    </li>
                HTML;
        }

        $html .= '</ul>';

        return $html;
    }
}
