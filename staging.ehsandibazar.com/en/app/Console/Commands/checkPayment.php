<?php

namespace App\Console\Commands;

use App\Model\Order;
use App\Utility\checkRestNumber;
use App\Utility\incrementVariation;
use App\Utility\Status;
use Carbon\Carbon;
use Illuminate\Console\Command;

class checkPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkPayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check status and payment in order';

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
     * @return mixed
     */
    public function handle()
    {
        // all order check where status 5
        $allOrderStatusPending = Order::where(
            [
                ['status', '=', Status::PENDING],
                ['expire', '<', Carbon::now()->timestamp],
            ]
        )->get();

        $this->eachItemOrder($allOrderStatusPending);
    }

    /* foreach */
    private function eachItemOrder($orderPending)
    {
        foreach ($orderPending as $itemStatusPending) {
            if (!empty($itemStatusPending->rest_number)) {

                /* che e */
                $resultCheckRestNumber = checkRestNumber::checkRestNumber($itemStatusPending->total_amount, $itemStatusPending->rest_number);
                /* pardakht shode bud */
                if ($resultCheckRestNumber) {
                    Order::where('id', $itemStatusPending->id)->update([
                        'status' => Status::PAID
                    ]);
                } else {
                    /* pardakht nashode bud */
                    $updateOrder = Order::where('id', $itemStatusPending->id)->update([
                        'status' => Status::UNPAID
                    ]);
                    if ($updateOrder) {
                        incrementVariation::incrementVariations($itemStatusPending->id);
                    }
                }

            } else {
                // when rest number is empty
                incrementVariation::incrementVariations($itemStatusPending->id);
                Order::where('id', $itemStatusPending->id)->update([
                    'status' => Status::UNPAID
                ]);
            }
        }
    }
}
