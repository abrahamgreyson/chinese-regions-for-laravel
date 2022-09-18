<?php

namespace Abe\ChineseRegions\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ChineseRegionsCommand extends Command
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

    protected DataSource $dataSource;
    protected DataSourceRepository $repository;

    public function __construct(DataSource $dataSource, DataSourceRepository $repository)
    {
        parent::__construct();
        $this->dataSource = $dataSource;
        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // 生产环境警告
        if (App::environment() === 'production' && ! $this->option('force')) {
            $this->warn(
                '这个命令将下载数据并导入到数据库中，你不应该在生产环境中执行，如果非得执行，请使用 --force 参数'
            );

            return self::FAILURE;
        }

        // 确定数据表存在
        if (! $this->tableExists()) {
            $this->warn('数据表不存在，请先执行 `php artisan migrate` 迁移数据表');

            return self::FAILURE;
        }
        // 确定数据表为空
        if ($this->tableNotEmpty() && ! $this->option('overwrite')) {
            $this->warn('这个命令会全量导入数据，当前数据表不为空，请使用 --overwrite 参数覆写（清空后导入）');

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
     * Check if the database table exists.
     *
     * @return bool
     */
    public function tableExists(): bool
    {
        return DB::getSchemaBuilder()->hasTable($this->option('table'));
    }

    /**
     * Check if the database table is not empty.
     *
     * @return bool
     */
    public function tableNotEmpty(): bool
    {
        $clazz = new class extends Model
        {
        };

        return $clazz->setTable($this->option('table'))->exists();
    }
}
