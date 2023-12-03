<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;
use React\EventLoop\Loop;
use Illuminate\Support\Facades\DB;
use App\Models\Mod_Batch;
use App\Models\Mod_Valid_No;
use App\Models\Mod_Company;
use App\Models\Mod_Valid_No_HISTORY;

class ChackResultCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_result:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process checking result batch start';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('check result batch command started');
        $loop = Loop::get();
        $sequence = []; //for give source number per batch
        $loop->addPeriodicTimer(1, function () use (&$sequence) 
		{
            $batch = Mod_Batch::query()
                ->where('status', 2)
                ->chunk(100, function ($check) {
                    foreach ($check as $checkId) {
                        $BlastBatch = Mod_Valid_No::query()
                            ->where('batch_id', '=', $checkId->id)
                            ->where('callid', '!=', '')
                            ->where('result_detail', '=', '')
                            ->WhereNotNull('callid')
                            ->WhereNotNull('proses_date')
                            ->chunkById(10, function ($BlastBatch) {
                                foreach ($BlastBatch as $CheckBlastBatch) {
                                    
                                    $now = Carbon::now();

                                    if ($now->diffInSeconds($CheckBlastBatch->proses_date, false) <= -120) {
                                        $history = new Mod_Valid_No_HISTORY();
                                        $history->id = $CheckBlastBatch->id;
                                        $history->customerno = $CheckBlastBatch->customerno;
                                        $history->phone_number = $CheckBlastBatch->phone_number;
                                        $history->callid = $CheckBlastBatch->callid;
                                        $history->result_detail = $CheckBlastBatch->result_detail;
                                        $history->last_status = $CheckBlastBatch->last_status;
                                        $history->dialstatus = $CheckBlastBatch->dialstatus;
                                        $history->laststatus = $CheckBlastBatch->laststatus;
                                        $history->proses_date = $CheckBlastBatch->proses_date;
                                        $history->create_at = $CheckBlastBatch->create_at;
                                        $history->update_at = $CheckBlastBatch->update_at;
                                        $history->batchid = $CheckBlastBatch->batchid;
                                        $history->campaignid = $CheckBlastBatch->campaignid;
                                        $history->typeid = $CheckBlastBatch->typeid;
                                        $history->batch_id = $CheckBlastBatch->batch_id;
                                        
                                        if ($history->save()) {
                                            $CheckBlastBatch->setAttribute('callid', '');
                                            $CheckBlastBatch->save();
                                        }
                                    }
                                    
                                }
                            });
                    }
                });
        });
        return 0;
    }
}
