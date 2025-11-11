<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyStatisticsReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public int $totalViews,
        public int $newComments,
        public array $topArticles
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Статистика сайта за день',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-statistics',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
