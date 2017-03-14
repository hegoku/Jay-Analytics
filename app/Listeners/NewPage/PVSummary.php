<?php

namespace App\Listeners\NewPage;

use App\Events\NewPageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class PVSummary
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
    public function handle(NewPageEvent $event)
    {
        /*DB::collection('summary_pv_hour')->raw(function($collection) use ($event) {
            $collection->update([
                [
                    'project_id'=>$event->page->project_id,
                    'create_time'=>(int)Date("YmdH",strtotime($event->page->created_at))
                ],
                ['$inc'=>["pv"=>1]],
                ['upsert'=>true]
            ]);
        });*/
        DB::collection('summary_pv_hour')->where([
            'project_id'=>$event->page->project_id,
            'create_time'=>(int)Date("YmdH",strtotime($event->page->created_at))
        ])->update(
            ['$inc'=>["pv"=>1]],
            ['upsert'=>true]
        );

    }
}
