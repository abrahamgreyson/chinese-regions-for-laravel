<?php

namespace Abe\ChineseRegionsForLaravel\Commands;

use Throwable;

class ImportCommand extends AbstractCommand
{
    /**
     * Signature of the command.
     *
     * @var string
     */
    public $signature = 'chinese-regions:import
                         {--T|table=chinese_regions : 指定数据表名称，默认为 `chinese_regions` }
                         {--F|force}';

    /**
     * Description of the command.
     *
     * @var string
     */
    public $description = '导入中国行政区划到数据库';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->ensureNotInProduction();
            $this->ensureTableExists();
            $this->ensureTableIsEmpty();
        } catch (Throwable $e) {
            $this->warn($e->getMessage());

            return self::FAILURE;
        }


        return self::SUCCESS;
    }
}
