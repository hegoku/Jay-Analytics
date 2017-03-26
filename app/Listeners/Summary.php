<?php

namespace App\Listeners;

use App\Events\NewPageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Mongodb;
use App\Events\NewUVEvent;

class Summary
{
    public $isNewUV=false;
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
        $this->updatePV('hour', "YmdH", $event);
        $this->updatePV('day', "Ymd", $event);
        $this->updatePV('month', "Ym", $event);
        $this->updatePV('year', "Y", $event);

        if ($this->isNewUV) {
            event(new NewUVEvent($event->page));
        }
    }

    private function updatePV($table_date, $date, $event)
    {
        /*DB::collection('summary_'.$table_date)->whereRaw(
            [
                '$isolated'=>1,
                'project_id'=>$event->page->project_id,
                'create_time'=>(int)Date($date, strtotime($event->page->created_at))
            ]
        )->update(
            ['$inc'=>["pv"=>1]],
            ['upsert'=>true]
        );*/
        $inc=["pv"=>1];
        if ($this->checkUV($table_date, $date, $event)>0) {
            $inc["uv"]=1;
            $this->isNewUV=true;
        }

        Mongodb::getInstance()->collection('summary_'.$table_date)->update(
            [
                '$isolated'=>1,
                'project_id'=>$event->page->project_id,
                'create_time'=>(int)Date($date, strtotime($event->page->created_at))
            ],
            ['$inc'=>$inc],
            ['upsert'=>true]
        );
    }

    private function checkUV($table_date, $date, $event)
    {
        $result=Mongodb::getInstance()->collection('summary_uv_'.$table_date)->update(
            [
                '$isolated'=>1,
                'project_id'=>$event->page->project_id,
                'create_time'=>(int)Date($date, strtotime($event->page->created_at)),
                'visiter_id'=>$event->page->cookie->visiter_id
            ],
            ['$inc'=>["count"=>1]],
            ['upsert'=>true]
        );
        return $result->getUpsertedCount();
    }
}
