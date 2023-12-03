<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use App\Libs\Voice\Voice;
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
use Illuminate\Support\Facades\Log;

class BatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process checking and start batch';

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
        $this->info('batch command started');
        DB::connection()->unsetEventDispatcher();
        $loop = Loop::get();
        $sequence = []; //for give source number per batch
        
        $loop->addPeriodicTimer(1.0, function () use (&$sequence) 
		{
            $now = Carbon::now();

            $batch = Mod_Batch::query()
                    ->where('status', 2)
					->where('fupload', 1) //Add by Unang
                    ->chunk(10, function ($batch) {
                        foreach ($batch as $batchs) {

                            $ccs = Mod_Company::query()
							->where('customerno', $batchs->customerno)
							->distinct()
							->firstOrFail();

                            $remain_capacity = $ccs->quotatrial - $ccs->remainquota;

                            $concurrent	= $remain_capacity >= $ccs->concurrent ? $ccs->concurrent : $remain_capacity;

                            if ($concurrent === 0 ) {
                                $batchs->setAttribute('status', 4);
                                $batchs->save();
                            }

                            $CheckCallId = Mod_Valid_No::query()
                                ->where('batch_id', $batchs->id)
                                ->whereNotNull('callid')
                                ->where('callid', '!=', '')
                                ->count();                          

                            $CheckLastStatus = Mod_Valid_No::query()
                                ->where('batch_id', $batchs->id)
                                ->where('result_detail', '!=', '')
                                ->where('last_status', '!=', '')
                                ->count();

                            $totalbatchid = Mod_Valid_No::query()
                                ->where('batch_id', $batchs->id)
                                ->count();
                            
                            $batchid = Mod_Valid_No::query()
                                    ->where('batch_id', $batchs->id)
                                    ->count();

                            if((int) $batchid === (int) $CheckLastStatus)
                            {
                                Mod_Batch::where('id', $batchs->id)->update(['status' => 3]);
                            }

                            $date = date('D');
                            $hour = date('H');

                            if ($date == 'Sat' || $date == 'Sun') {
                            	Mod_Batch::where('id', $batchs->id)->update(['status' => 0]);
                            } elseif ($hour < '08' || $hour > '17') {
                            	Mod_Batch::where('id', $batchs->id)->update(['status' => 0]);
                            }

                            if ((int) $CheckCallId === (int) $CheckLastStatus) 
                            {
                                Mod_Valid_No::query()
                                    ->where('batch_id', $batchs->id)
                                    ->where('callid', '=', '')
                                    ->orWhereNull('callid')
                                    ->chunkById($concurrent, function ($blasts) {
                                    
                                        foreach ($blasts as $blast) {

                                            if(substr($blast->phone_number, 2) == "62")
                                            {
                                                $nol		= 0;
                                                $dn			= $nol . substr($blast->phone_number, 2);
                                            }
                                            else
                                            {
                                                $dn			= $blast->phone_number;
                                            }

                                            $api = Voice::load('Mdn');
                                            $api->setBaseUrl(env('cek_call'));
                                            $api->setToken(env('authorization_key'));
                                            $response = $api->sendMessage($dn);
                                           
                                            if ($response->successful()) {
                                                $json = $response->json();
                                                dd($json);
                                                if (isset($json['callid'])) {
                                                    $blast->setAttribute('callid', $json['callid']);
                                                    $blast->setAttribute('proses_date', date('Y-m-d H:i:s'));
                                                    $blast->save();
                                                }
                                                
                                                $company = Mod_Company::query()
                                                    ->where('customerno', $blast->customerno)
                                                    ->firstOrFail();

                                                $quuota = $company->remainquota + 1;

                                                $company->setAttribute('remainquota', $quuota);
                                                $company->save();

                                            } else {
                                                Log::info('Gagal Blast');
                                                Log::info('Batch Id: '.$blast->batch_id);
                                                Log::info('Batch Detail Id: '.$blast->id);
                                            }
                                            
                                            $this->info(sprintf('%s get memory peak: %d at line %d', date('Y-m-d H:i:s'), memory_get_usage(), __LINE__));
                                        }
                                        return false;
                                    });
                            }
                        }
                    });
        });

        Command::SUCCESS;
    }
}
