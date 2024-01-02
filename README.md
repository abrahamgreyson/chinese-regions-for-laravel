# 中国行政区划


[![Latest Version on Packagist](https://img.shields.io/packagist/v/abe/chinese-regions-for-laravel.svg?style=flat-square)](https://packagist.org/packages/abe/chinese-regions-for-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/abe/chinese-regions-for-laravel/run-tests?label=tests)](https://github.com/abe/chinese-regions-for-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/abe/chinese-regions-for-laravel/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/abe/chinese-regions-for-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/abe/chinese-regions-for-laravel.svg?style=flat-square)](https://packagist.org/packages/abe/chinese-regions-for-laravel)

Laravel 中国行政区划, 包含了五级行政区划数据,数据源为国家统计局,数据更新时间为 2023年6月10日.

## Installation

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
$chineseRegion = new Abe\ChineseRegionsForLaravel\Models\ChineseRegion();
/** @var \Illuminate\Database\Eloquent\Collection $provinces */
$provinces = $chineseRegion->getProvinces(); // 获取所有省份
/** @var \Illuminate\Database\Eloquent\Collection $cities */
$cities = $provinces->first()->cities; // 获取第一个省份的所有城市
// or 
$cities = $chineseRegion->getCities($provinces->first()); // 获取省份ID为1的所有城市

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
