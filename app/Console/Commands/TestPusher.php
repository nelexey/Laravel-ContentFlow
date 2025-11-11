<?php

namespace App\Console\Commands;

use App\Events\ArticleCreated;
use App\Events\CommentCreated;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Console\Command;

class TestPusher extends Command
{
    protected $signature = 'test:pusher';
    protected $description = 'Test Pusher broadcasting';

    public function handle()
    {
        $this->info('Testing Pusher configuration...');

        $this->info('Broadcasting driver: ' . config('broadcasting.default'));
        $this->info('Pusher key: ' . config('broadcasting.connections.pusher.key'));
        $this->info('Pusher cluster: ' . config('broadcasting.connections.pusher.options.cluster'));

        $article = Article::with('author')->first();

        if (!$article) {
            $this->error('No articles found in database. Create an article first.');
            return 1;
        }

        $this->info('Dispatching ArticleCreated event...');
        ArticleCreated::dispatch($article);

        $this->info('Event dispatched! Check Pusher Debug Console.');
        $this->info('Channel: articles');
        $this->info('Event: ArticleCreated');

        return 0;
    }
}
