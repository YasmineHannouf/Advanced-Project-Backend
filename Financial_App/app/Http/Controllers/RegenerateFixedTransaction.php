<?php

namespace App\Jobs;

use App\Models\FixedModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegenerateFixedTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fixedId;

    /**
     * Create a new job instance.
     *
     * @param int $fixedId The ID of the fixed transaction to regenerate
     */
    public function __construct(int $fixedId)
    {
        $this->fixedId = $fixedId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Retrieve the fixed transaction that needs to be regenerated
        $fixed = FixedModel::findOrFail($this->fixedId);
        
        // Create a new fixed transaction with the same data as the original,
        // but with a different ID and date/time for the next occurrence
        $newFixed = new FixedModel;
        $newFixed->title = $fixed->title;
        $newFixed->description = $fixed->description;
        $newFixed->amount = $fixed->amount;
        $newFixed->category_id = $fixed->category_id;
        $newFixed->key_id = $fixed->key_id;
        $newFixed->is_paid = $fixed->is_paid;
        $newFixed->type = $fixed->type;
        $newFixed->scheduled_date = $fixed->scheduled_date;
        $newFixed->date_time = $this->calculateScheduledDate($fixed->scheduled_date);
        $newFixed->save();
    }

    /**
     * Calculate the date/time when the fixed transaction should occur
     *
     * @param string $scheduledDate The scheduled date/time for the fixed transaction
     * @return \Illuminate\Support\Carbon The calculated date/time
     */
    protected function calculateScheduledDate($scheduledDate)
{
    $now = now();
    switch ($scheduledDate) {
        case 'year':
            $dateTime = $now->addYear();
            break;
        case 'month':
            $dateTime = $now->addMonth();
            break;
        case 'week':
            $dateTime = $now->addWeek();
            break;
        case 'day':
            $dateTime = $now->addDay();
            break;
        case 'hour':
            $dateTime = $now->addHour();
            break;
        case 'minute':
            $dateTime = $now->addMinute();
            break;
        case 'second':
            $dateTime = $now->addSecond();
            break;
        default:
            $dateTime = $now;
    }

    return $dateTime;
}

}
