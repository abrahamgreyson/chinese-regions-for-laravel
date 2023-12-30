<?php

namespace Abe\ChineseRegionsForLaravel\Commands;


class CommandException extends \Exception
{
    public static function tableNotFound($table = 'chinese_regions'): static
    {
        return new static("数据表 `{$table}` 不存在，请先执行 `php artisan migrate` 迁移数据表");
    }


    public static function isRunInProduction(): static
    {
        return new static("这个命令将导入到数据库中，你不应该在生产环境中执行，如果非得执行，请使用 --force 参数");
    }

    public static function tableNotEmpty(): static
    {
        return new static("这个命令会全量导入数据，当前数据表不为空，请使用 --force 参数覆写（清空后导入）");
    }
}
