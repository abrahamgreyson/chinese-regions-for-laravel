<?php

namespace Abe\ChineseRegions\Commands;

use Illuminate\Console\Command;

class ChineseRegionsCommand extends Command
{
    public $signature = 'chinese-regions';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
