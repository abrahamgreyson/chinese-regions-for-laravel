<?php

namespace Abe\ChineseRegionsForLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * 共用的方法
 */
abstract class AbstractCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
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
            throw CommandException::tableNotFound($this->option('table'));
        }
    }

    /**
     * 生产环境警告
     *
     * @throws \Exception
     */
    public function ensureNotInProduction(): void
    {
        if (App::environment() === 'production' && ! $this->option('force')) {
            throw CommandException::isRunInProduction();
        }
    }

    /**
     * 确定数据表为空
     *
     * @throws \Exception
     */
    public function ensureTableIsEmpty(): void
    {
        if ($this->tableNotEmpty() && ! $this->option('overwrite')) {
            throw CommandException::tableNotEmpty();
        }
    }

    /**
     * Check if the database table exists.
     */
    public function tableExists(): bool
    {
        return \DB::getSchemaBuilder()->hasTable($this->option('table'));
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
