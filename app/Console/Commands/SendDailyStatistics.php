<?php

namespace App\Console\Commands;

use App\Mail\DailyStatisticsReport;
use App\Models\Comment;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendDailyStatistics extends Command
{
    protected $signature = 'statistics:send-daily';
    protected $description = 'Send daily statistics report to moderators';

    public function handle()
    {
        $yesterday = now()->subDay();

        $totalViews = DB::table('article_views')
            ->whereDate('viewed_at', $yesterday)
            ->count();

        $newComments = Comment::whereDate('created_at', $yesterday)->count();

        $topArticles = DB::table('article_views')
            ->select('articles.id', 'articles.title', DB::raw('COUNT(*) as views_count'))
            ->join('articles', 'article_views.article_id', '=', 'articles.id')
            ->whereDate('article_views.viewed_at', $yesterday)
            ->groupBy('articles.id', 'articles.title')
            ->orderByDesc('views_count')
            ->limit(5)
            ->get()
            ->toArray();

        $moderators = Role::where('name', 'moderator')
            ->first()
            ?->users()
            ->get() ?? collect();

        foreach ($moderators as $moderator) {
            Mail::to($moderator->email)->send(
                new DailyStatisticsReport($totalViews, $newComments, $topArticles)
            );
        }

        $this->info('Daily statistics report sent to ' . $moderators->count() . ' moderators.');

        return 0;
    }
}
