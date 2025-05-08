<?php

namespace App\Jobs;

use App\Mail\OrderCreatedMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SendOrderCreatedEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        Mail::to($this->order->user->email)->send(new OrderCreatedMail($this->order));
    }
}
