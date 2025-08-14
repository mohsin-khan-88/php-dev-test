<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;
use Parsedown;

class PostDetails extends Layout
{
    protected function renderPage(Context $context): string
    {

        // Convert Markdown to HTML
        $parsedown = new Parsedown();
        $parsedBody = $parsedown->text($context->post->body);

        return <<<HTML
                <article>
                    <h1>{$context->post->title}</h1>
                    <p><em>By {$context->post->author}</em></p>
                    <div>{$parsedBody}</div>
                    <p><a href="/posts" class="btn btn--primary">
                    &larr; Back to Posts
                    </a></p>
                </article>
            HTML;
    }
}
