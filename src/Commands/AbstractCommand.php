<?php

namespace Abe\ChineseRegions\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * 包含一系列 Command 中共用的方法
 */
abstract class AbstractCommand extends Command
{
    protected DataSource $dataSource;

    protected DataSourceRepository $repository;

    public function __construct(DataSource $dataSource, DataSourceRepository $repository)
    {
        parent::__construct();
        $this->dataSource = $dataSource;
        $this->repository = $repository;
    }

    abstract public function handle();

    /**
     * 确定数据表存在
     *
     *
     * @throws \Exception
     */
    public function ensureTableExists(): void
    {
        if (! $this->tableExists()) {
            throw new \Exception('数据表不存在，请先执行 `php artisan migrate` 迁移数据表');
        }
    }

    /**
     * 生产环境警告
     *
     * @throws \Exception
     */
    public function ensureNotInProduction()
    {
        if (App::environment() === 'production' && ! $this->option('force')) {
            throw new \Exception('这个命令将下载数据并导入到数据库中，你不应该在生产环境中执行，如果非得执行，请使用 --force 参数');
        }
    }

    /**
     * Check if the database table exists.
     */
    public function tableExists(): bool
    {
        return DB::getSchemaBuilder()->hasTable($this->option('table'));
    }

    /**
     * Check if the database table is not empty.
     */
    public function tableNotEmpty(): bool
    {
        $clazz = new class extends Model
        {
        };

        return $clazz->setTable($this->option('table'))->exists();
    }
}
