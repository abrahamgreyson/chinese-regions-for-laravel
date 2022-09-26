<?php

namespace Abe\ChineseRegions\Commands;

use Throwable;

class ChineseRegionsCommand extends AbstractCommand
{
    /**
     * Signature of the command.
     *
     * @var string
     */
    public $signature = 'chinese-regions:import
                         {--A|via= : 指定下载数据的方式，可选值为 npm、github 和 gitee }
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
     *
     * @return int
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

        $this->info('开始从网络下载数据...');
        if ($via = $this->option('via')) {
            // Check if the npm command executable.
            if ($via === 'npm' && ! $this->npmCommandExists()) {
                $this->error('NPM 命令不存在，请先安装 NPM，或使用 --via=gitee 或 --via=github 选项下载数据');

                return self::FAILURE;
            }

            // Check if the git command executable.
            if (! in_array($via, ['github', 'gitee']) && ! $this->gitCommandExists()) {
                $this->error('Git 命令不存在，请先安装 Git，或使用 --via=npm 选项使用 NPM 下载数据');

                return self::FAILURE;
            }
            $this->pullData('gitee');

//            $this->error('--via 的可选值只能是 npm 或 git');
//            return self::FAILURE;
        } else {
            $via = $this->npmCommandExists() ? 'npm' : 'git';

            $this->info("未指定下载方式，自动选择 {$via} 方式下载");
            // use symfony process to execute npm install china-division
        }

        $this->info('导入完成，共导入 '. 1234 .' 条数据');

        return self::SUCCESS;
    }

    /**
     * 确定数据表为空
     *
     * @throws \Exception
     */
    public function ensureTableIsEmpty()
    {
        if ($this->tableNotEmpty() && ! $this->option('overwrite')) {
            throw new \Exception('这个命令会全量导入数据，当前数据表不为空，请使用 --overwrite 参数覆写（清空后导入）');
        }
    }
}
