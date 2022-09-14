<?php

namespace Abe\ChineseRegions\Commands;

use Illuminate\Console\Command;

class ChineseRegionsCommand extends Command
{
    public $signature = 'chinese-regions:import --via={git|npm}';

    public $description = '导入中国行政区划到数据库';



    public function handle(): int
    {
        // Check if the database table exists.
        if (!$this->tableExists()) {
            $this->error('The database table does not exist, please run the migration first.');
            return self::FAILURE;
        }

        // Check if the npm command executable.
        if (!$this->npmCommandExists()) {
            $this->error('The npm command does not exist, please install npm first.');
            return self::FAILURE;
        }

        // Check if the git command executable.
        if (!$this->gitCommandExists()) {
            $this->error('The git command does not exist, please install git first.');
            return self::FAILURE;
        }

        $this->comment('All done');

        return self::SUCCESS;
    }
}
