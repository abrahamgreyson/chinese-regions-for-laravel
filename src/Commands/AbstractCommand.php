<?php

namespace Abe\ChineseRegionsForLaravel\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Helper\ProgressBar;

abstract class AbstractCommand extends Command
{
    protected ProgressBar $bar;

    /**
     * @var ?string
     */
    protected ?string $tableName = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->init();

        return 0;
    }

    abstract public function init();

    /**
     * 确定数据表存在
     *
     *
     * @throws CommandException
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
     * @throws CommandException
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
     * @throws CommandException
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
