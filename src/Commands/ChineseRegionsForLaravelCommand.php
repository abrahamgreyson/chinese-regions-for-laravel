<?php

namespace ChineseRegionsForLaravel\ChineseRegionsForLaravel\Commands;

use Illuminate\Console\Command;

class ChineseRegionsForLaravelCommand extends Command
{
    public $signature = 'chinese-regions-for-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
