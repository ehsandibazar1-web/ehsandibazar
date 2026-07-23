<?php

namespace App\Console\Commands;

use App\Services\Maintenance\DatabaseBackupService;
use Illuminate\Console\Command;

/**
 * پشتیبان‌گیریِ دیتابیس از خطِ فرمان یا از طریقِ روتِ maintenance/db-backup (روی هاستِ بدون‌شل).
 * یک سرویسِ uptime-pinger می‌تواند این را روزانه بزند تا بکاپِ خودکارِ روزانه داشته باشیم — معادلِ
 * زمان‌بندیِ روزانه‌ی سایتِ انگلیسی، بدونِ نیاز به cron.
 */
class BackupDatabase extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'گرفتنِ یک پشتیبانِ فشرده‌ی دیتابیس (نگه‌داشتنِ ۱۴ نسخه‌ی آخر)';

    public function handle(DatabaseBackupService $service): int
    {
        try {
            $result = $service->backup();
            $this->info('Backup created: '.$result['filename'].' ('.$result['size'].' bytes).');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Backup failed: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
