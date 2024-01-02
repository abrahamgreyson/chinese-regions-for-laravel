<?php

namespace Abe\ChineseRegionsForLaravel\Commands;

use DB;
use Illuminate\Database\Connection;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class ImportCommand extends AbstractCommand
{
    /**
     * Signature of the command.
     *
     * @var string
     */
    public $signature = 'chinese-regions:import
                         {--T|table=chinese_regions : 指定数据表名称，默认为`chinese_regions`}
                         {--F|force : 强制导入，即使是在`production`环境中}
                         {--O|overwrite : 重写数据表，即使数据表中已经有数据}
                         ';

    /**
     * Description of the command.
     *
     * @var string
     */
    public $description = '导入中国大陆行政区划到数据库';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        parent::handle();

        try {
            $this->ensureNotInProduction();
            $this->ensureTableExists();
            $this->ensureTableIsEmpty();

            return $this->import();
        } catch (Throwable $e) {
            $this->warn($e->getMessage());

            return self::FAILURE;
        }
    }

    private function import(): int
    {
        $sqlite = $this->getSqliteConnection();
        $count = $this->countingAll($sqlite);
        $this->truncateTable();

        $this->bar = $this->output->createProgressBar($count);
        // 自定义格式
        // @see https://symfony.com/doc/current/components/console/helpers/progressbar.html
        ProgressBar::setFormatDefinition('custom', ' %current%/%max%  [%bar%] -- %message%');
        $this->bar->setFormat('custom');
        $this->bar->setMessage('插入数据到地区数据库中...');

        $this->bar->start();
        // 省级（省份、直辖市、自治区）
        $this->insertProvinces($sqlite);
        // 地级（城市）
        $this->insertCities($sqlite);
        // 县级（区县）
        $this->insertAreas($sqlite);
        // 乡级（乡镇、街道）
        $this->insertBlocks($sqlite);
        // 村级（村委会、居委会）
        $this->insertCommunities($sqlite);
        $this->info("\n 都完事儿了，共插入数据 {$count} 条");
        $this->bar->finish();

        return self::SUCCESS;
    }

    /**
     * Province level
     */
    public function insertProvinces(Connection $sqlite): void
    {
        $this->bar->setMessage('插入省份、直辖市、自治区...');
        $this->bar->display();
        $sqlite->table('province')->orderBy('code')->chunk(200, function ($provinces) {
            $provinces->transform(function ($value) {
                return [
                    'code' => $value->code,
                    'name' => $value->name,
                    'level' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            });
            $this->bar->advance($provinces->count());
            DB::table($this->tableName)->insert($provinces->toArray());
        });
    }

    /**
     * City level
     */
    public function insertCities(Connection $sqlite): void
    {
        $this->bar->setMessage('插入地级市');
        $this->bar->display();
        $sqlite->table('city')->orderBy('code')->chunk(200, function ($cities) {
            $cities->transform(function ($value) {
                return [
                    'code' => $value->code,
                    'name' => $value->name,
                    'level' => 2,
                    'province_code' => $value->provinceCode,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            });
            $this->bar->advance($cities->count());
            DB::table($this->tableName)->insert($cities->toArray());
        });
    }

    /**
     * Area level
     */
    public function insertAreas(Connection $sqlite): void
    {
        $this->bar->setMessage('插入县级市、区...');
        $this->bar->display();
        $sqlite->table('area')->orderBy('code')->chunk(200, function ($areas) {
            $areas->transform(function ($value) {
                return [
                    'code' => $value->code,
                    'name' => $value->name,
                    'level' => 3,
                    'province_code' => $value->provinceCode,
                    'city_code' => $value->cityCode,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            });
            $this->bar->advance($areas->count());
            DB::table($this->tableName)->insert($areas->toArray());
        });
    }

    /**
     * Town, street level
     */
    public function insertBlocks(Connection $sqlite): void
    {
        $this->bar->setMessage('插入乡镇、街道...');
        $this->bar->display();
        $sqlite->table('street')->orderBy('code')->chunk(200, function ($blocks) {
            $blocks->transform(function ($value) {
                return [
                    'code' => $value->code,
                    'name' => $value->name,
                    'level' => 4,
                    'province_code' => $value->provinceCode,
                    'city_code' => $value->cityCode,
                    'area_code' => $value->areaCode,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            });
            $this->bar->advance($blocks->count());
            DB::table($this->tableName)->insert($blocks->toArray());
        });
    }

    /**
     * Community, village level
     */
    public function insertCommunities(Connection $sqlite): void
    {
        $this->bar->setMessage('插入村屯、社区...');
        $this->bar->display();
        $sqlite->table('village')->orderBy('code')->chunk(200, function ($communities) {
            $communities->transform(function ($value) {
                return [
                    'code' => $value->code,
                    'name' => $value->name,
                    'level' => 5,
                    'province_code' => $value->provinceCode,
                    'city_code' => $value->cityCode,
                    'area_code' => $value->areaCode,
                    'street_code' => $value->streetCode,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            });
            $this->bar->advance($communities->count());
            DB::table($this->tableName)->insert($communities->toArray());
        });
    }

    /**
     * Counting all data from data source.
     */
    private function countingAll(Connection $sqlite): int
    {
        $countProvinces = $sqlite->table('province')->count('code');
        $countCities = $sqlite->table('city')->count('code');
        $countAreas = $sqlite->table('area')->count('code');
        $countStreet = $sqlite->table('street')->count('code');
        $countVillage = $sqlite->table('village')->count('code');

        return $countProvinces + $countCities + $countAreas + $countStreet + $countVillage;
    }

    /**
     * DB connection for data source.
     */
    public function getSqliteConnection(): Connection
    {
        $sqlitePath = '/vendor/abe/chinese-regions-for-laravel/data/data.sqlite';
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => base_path($sqlitePath),
        ]);

        return DB::connection('sqlite');
    }

    /**
     * Some stuff before handle.
     */
    public function init(): void
    {
        $this->tableName = $this->option('table');
    }

    /**
     * Truncate the table.
     */
    private function truncateTable(): void
    {
        DB::table($this->tableName)->truncate();
    }
}
