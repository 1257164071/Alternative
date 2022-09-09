<?php

namespace App\Console\Commands;

use App\Services\MonkeyService;
use Illuminate\Console\Command;

class Order extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:recharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $service = new MonkeyService();
        \App\Models\Order::query()->where(['recharge_status'=>\App\Models\Order::RECHARGE_STATUS_PENDING,'closed'=>0,'refund_status'=>\App\Models\Order::REFUND_STATUS_PENDING])->where('paid_at','!=','')->orderBy('id')->chunk(10,function ($orders)use($service){
            foreach ($orders as $item){
                $service->recharge($item);
                $this->info('order:'.$item->no);
            }
        });
        $this->info(':)');
        return true;
    }
}
