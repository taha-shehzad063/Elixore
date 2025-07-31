<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\CartItem;

class DeleteExpiredCartItems extends Command
{
    protected $signature = 'cart:clean-expired-items';
    protected $description = 'Delete cart items older than 45 minutes';

    public function handle(): int
    {
        $deleted = CartItem::where('created_at', '<', Carbon::now()->subMinutes(45))->delete();

        $this->info("✅ Deleted $deleted expired cart items.");
        return self::SUCCESS;
    }
}
