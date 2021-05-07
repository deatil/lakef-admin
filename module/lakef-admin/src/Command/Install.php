<?php

declare(strict_types=1);

namespace Lakef\Admin\Command;

use Psr\Container\ContainerInterface;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\DbConnection\Db;

/**
 * 安装脚本
 *
 * php bin/hyperf.php lakef-admin:install
 */
class Install extends HyperfCommand
{
    /**
     * 执行的命令行
     *
     * @var string
     */
    protected $name = 'lakef-admin:install';

    public function handle()
    {
        // 执行数据库
        $installSqlFile = __DIR__.'/../../resources/database/install.sql';
        
        $sqlData = file_get_contents($installSqlFile);
        if (empty($sqlData)) {
            $this->output->writeln("<fg=red>Sql file is empty !</>");
            return;
        }
        
        $dbPrefix = config('databases.default.prefix');
        $sqlContent = str_replace('pre__', $dbPrefix, $sqlData);
        
        Db::unprepared($sqlContent);

        $this->output->writeln('<fg=green>Lakef-admin install successfully.</>');
    }

    protected function configure()
    {
        $this->setDescription('Install lakef admin.');
    }
}
