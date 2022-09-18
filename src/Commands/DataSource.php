<?php

namespace Abe\ChineseRegions\Commands;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DataSource
{
    /**
     * The approach to download the data source.
     *
     * @var string
     */
    public string $via;

    /**
     * The NPM package name.
     */
    public static string $npmPackageName = 'china-division';

    /**
     * The GitHub repository url.
     *
     * @var string
     */
    public static string $github = 'https://github.com/modood/Administrative-divisions-of-China.git';

    /**
     * The Gitee repository url.
     *
     * @var string
     */
    public static string $gitee = 'https://gitee.com/modood/Administrative-divisions-of-China.git';

    public function __construct($via = 'npm')
    {
        $this->setVia($via);
    }

    /**
     * THe setter of $via.
     *
     * @param  string  $via
     *
     * @throws \Exception
     */
    public function setVia(string $via): void
    {
        if (! in_array($via, ['npm', 'github', 'gitee'])) {
            throw new \Exception('Via must be one of npm, github, gitee');
        }

        $this->via = $via;
    }

    /**
     * Dispatch the download process.
     *
     * @return string
     */
    public function dispatch()
    {
    }

    /**
     * Pull the data source from remote repository.
     *
     * @param  string  $via
     * @return bool
     *
     * @throws ProcessFailedException
     */
    public function download(string $via = 'npm'): bool
    {
        $process = match ($via) {
            'github' => new Process(['git', 'clone', static::$github, 'chinese-regions-data-source', '--progress']),
            'npm' => new Process(['npm', 'i', static::$npmPackageName, '--no-save']),
            default => new Process(['git', 'clone', static::$gitee, 'chinese-regions-data-source', '--progress']),
        };

        $this->removeDataFromDisk($via);
        // Set some options.
        $process->setWorkingDirectory(base_path());
        $process->setTimeout(600);
        $process->setIdleTimeout(120);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return true;
    }

    /**
     * Remove the data source folder from local.
     *
     * @param  string  $via
     */
    private function removeDataFromDisk(string $via = 'npm'): void
    {
        $via = match ($via) {
            'github' => base_path('chinese-regions-data-source'),
            'npm' => base_path('node_modules/china-division'),
            default => base_path('chinese-regions-data-source'),
        };

        @\unlink($via);
    }

    /**
     * Check if NPM command exists.
     */
    public function npmCommandExists(): bool
    {
        return $this->commandExecutable('npm');
    }

    /**
     * Check if GIT command exists.
     */
    public function gitCommandExists(): bool
    {
        return $this->commandExecutable('git');
    }

    /**
     * Check if the command executable.
     */
    public function commandExecutable(string $command): bool
    {
        $test = $this->onWindows() ? 'where' : 'which';

        return is_executable(trim((string) `$test $command`));
    }

    /**
     * @return bool
     */
    public function onWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
