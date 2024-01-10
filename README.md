# Laravel 中国行政区划


[![Latest Version on Packagist](https://img.shields.io/packagist/v/abe/chinese-regions-for-laravel.svg?style=flat-square)](https://packagist.org/packages/abe/chinese-regions-for-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/abe/chinese-regions-for-laravel.svg?style=flat-square)](https://packagist.org/packages/abe/chinese-regions-for-laravel)
[![tests](https://github.com/abrahamgreyson/chinese-regions-for-laravel/actions/workflows/run-tests.yml/badge.svg)](https://github.com/abrahamgreyson/chinese-regions-for-laravel/actions/workflows/run-tests.yml)
[![Coverage](https://github.com/abrahamgreyson/chinese-regions-for-laravel/blob/coverage-badge/coverage.svg)](https://github.com/abrahamgreyson/chinese-regions-for-laravel/actions/workflows/tests.yml)

## 数据来源

> * [中华人民共和国国家统计局-统计用区划和城乡划分代码](http://www.stats.gov.cn/sj/tjbz/qhdm/)
> * [中华人民共和国国家统计局-统计用区划代码和城乡划分代码编制规则](http://www.stats.gov.cn/sj/tjbz/gjtjbz/202302/t20230213_1902741.html)
> * 项目更新至: [2023年统计用区划代码和城乡划分代码（截止时间：2023-06-30，发布时间：2023-09-11）](http://www.stats.gov.cn/sj/tjbz/tjyqhdmhcxhfdm/2023/index.html)
> * 数据源作者: https://github.com/modood/Administrative-divisions-of-China
> * 数据量约为 60 万条, 请耐心等待导入完成


## Installation

##### 先决条件
- ext_pdo_sqlite 我们需要读取数据源的 SQLite 文件导入到自己的数据库中,一旦导入完成就可以禁用扩展


You can install the package via composer:

```bash
composer require abe/chinese-regions-for-laravel
```

安装后,运行数据库迁移命令,以创建数据表

```bash
php artisan migrate
```

数据表创建完成后,执行导入命令,导入数据

```bash
php artisan chinese-regions:import
```

## 数据库结构

## 使用

我附带了一个简单的实体类, 你也可以自己实现

```php
$chineseRegion = new \Abe\ChineseRegionsForLaravel\ChineseRegion();
/** @var \Illuminate\Database\Eloquent\Collection $provinces */
$provinces = $chineseRegion->provinces; // 所有省份
/** @var \Illuminate\Database\Eloquent\Collection $cities */
$cities = $provinces->first()->cities; // 获取第一个省份的所有城市
/** @var \Illuminate\Database\Eloquent\Collection $areas */
$areas = $cities->first()->areas; // 获取第一个城市的所有区县
/** @var \Illuminate\Database\Eloquent\Collection $streets */
$streets = $areas->first()->streets; // 获取第一个区县的所有街道
/** @var \Illuminate\Database\Eloquent\Collection $villages */
$villages = $streets->first()->towns; // 获取第一个街道的所有乡镇
/** @var \Illuminate\Database\Eloquent\Collection $street */
$street = $villages->parent; // 获取上级
** @var \Illuminate\Database\Eloquent\Collection $villages */
$villages = $street->children; // 获取**下一级**

// 请注意,我们只支持一级下级,平均每个省份有 2 万个村,我不认为有必要返回多级,你可以多次请求接口,或者自行实现
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [abraham greyson](https://github.com/abrahamgreyson)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
