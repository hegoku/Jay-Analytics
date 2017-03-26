<?php

namespace App\Listeners;

use App\Events\NewUVEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Mongodb;

class BrowserSummary
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewPageEvent  $event
     * @return void
     */
    public function handle(NewUVEvent $event)
    {
        $this->update('day', "Ymd", $event);
        $this->update('month', "Ym", $event);
        $this->update('year', "Y", $event);
    }

    private function update($table_date, $date, $event)
    {
        Mongodb::getInstance()->collection('summary_browser_'.$table_date)->update(
            [
                '$isolated'=>1,
                'project_id'=>$event->page->project_id,
                'create_time'=>(int)Date($date, strtotime($event->page->created_at)),
                'browser'=>$event->page->browser->name
            ],
            ['$inc'=>['count'=>1]],
            ['upsert'=>true]
        );
    }
}
