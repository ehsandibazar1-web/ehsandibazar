<?php

namespace App\Services\Maintenance;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * پشتیبان‌گیریِ دیتابیس با PHPِ خالص (بدونِ نیاز به mysqldump یا شل — مناسبِ هاستِ cPanelِ بدون‌شل).
 * کلِ جدول‌ها را SHOW CREATE + INSERTهای چندردیفی می‌گیرد و gzip می‌کند. آخرین N نسخه نگه داشته
 * می‌شود و بقیه حذف. دیتابیسِ این سایت کوچک است (بکاپِ فشرده حدودِ صد کیلوبایت)، پس حافظه/سرعت
 * مسئله نیست. فقط-خواندنی از دیتابیس؛ هیچ داده‌ای تغییر نمی‌دهد.
 */
class DatabaseBackupService
{
    private string $disk = 'local';

    private string $dir = 'backups';

    private int $keep = 14;

    /**
     * یک بکاپِ جدید می‌سازد و مسیر/نام/حجمش را برمی‌گرداند.
     *
     * @return array{filename:string, path:string, size:int}
     */
    public function backup(): array
    {
        $conn = DB::connection();
        $pdo = $conn->getPdo();
        $dbName = (string) $conn->getDatabaseName();

        $disk = Storage::disk($this->disk);
        $disk->makeDirectory($this->dir);

        // نامِ دیتابیس را برای استفاده در نامِ فایل امن می‌کنیم (روی SQLite این یک مسیرِ کامل با «/»
        // است؛ روی MySQL یک شناسه‌ی ساده). هر کاراکترِ غیرمجاز به «_» تبدیل می‌شود.
        $safeDb = trim((string) preg_replace('/[^A-Za-z0-9_-]+/', '_', basename($dbName)), '_');
        $filename = 'backup-'.($safeDb !== '' ? $safeDb.'-' : '').date('Ymd-His').'.sql.gz';
        $relPath = $this->dir.'/'.$filename;
        $fullPath = $disk->path($relPath);

        $gz = gzopen($fullPath, 'wb6');
        if ($gz === false) {
            throw new \RuntimeException('نمی‌توان فایلِ بکاپ را برای نوشتن باز کرد.');
        }

        try {
            gzwrite($gz, '-- Backup of `'.$dbName.'` at '.date('c')."\n");
            gzwrite($gz, "SET FOREIGN_KEY_CHECKS=0;\nSET NAMES utf8mb4;\n\n");

            foreach ($this->tables($conn) as $table) {
                $createRow = (array) $conn->select('SHOW CREATE TABLE `'.$table.'`')[0];
                $createSql = array_values($createRow)[1] ?? '';
                gzwrite($gz, "\n-- Table `".$table."`\nDROP TABLE IF EXISTS `".$table."`;\n".$createSql.";\n\n");

                $columns = null;
                $buffer = [];

                foreach ($conn->table($table)->cursor() as $rowObj) {
                    $rowArr = (array) $rowObj;

                    if ($columns === null) {
                        $columns = '`'.implode('`,`', array_keys($rowArr)).'`';
                    }

                    $vals = [];
                    foreach ($rowArr as $v) {
                        if ($v === null) {
                            $vals[] = 'NULL';
                        } elseif (is_int($v) || is_float($v)) {
                            $vals[] = (string) $v;
                        } else {
                            $vals[] = $pdo->quote((string) $v);
                        }
                    }
                    $buffer[] = '('.implode(',', $vals).')';

                    if (count($buffer) >= 200) {
                        gzwrite($gz, 'INSERT INTO `'.$table.'` ('.$columns.") VALUES\n".implode(",\n", $buffer).";\n");
                        $buffer = [];
                    }
                }

                if ($buffer !== [] && $columns !== null) {
                    gzwrite($gz, 'INSERT INTO `'.$table.'` ('.$columns.") VALUES\n".implode(",\n", $buffer).";\n");
                }
            }

            gzwrite($gz, "\nSET FOREIGN_KEY_CHECKS=1;\n");
        } catch (\Throwable $e) {
            // اگر وسطِ کار خطا داد، فایلِ نیمه‌کاره‌ی خراب را حذف کن تا به‌اشتباه «بکاپ» به‌نظر نرسد.
            gzclose($gz);
            $disk->delete($relPath);

            throw $e;
        }

        gzclose($gz);

        $this->prune();

        return [
            'filename' => $filename,
            'path' => $relPath,
            'size' => $disk->size($relPath),
        ];
    }

    /** @return array<int, string> */
    private function tables($conn): array
    {
        $tables = [];
        foreach ($conn->select('SHOW TABLES') as $row) {
            $tables[] = array_values((array) $row)[0];
        }

        return $tables;
    }

    /** نگه‌داشتنِ آخرین N بکاپ، حذفِ بقیه. */
    public function prune(): void
    {
        $files = $this->files();
        foreach (array_slice($files, $this->keep) as $f) {
            Storage::disk($this->disk)->delete($this->dir.'/'.$f['name']);
        }
    }

    /**
     * فهرستِ بکاپ‌ها، جدیدترین اول.
     *
     * @return array<int, array{name:string, size:int, time:int}>
     */
    public function files(): array
    {
        $disk = Storage::disk($this->disk);

        if (! $disk->exists($this->dir)) {
            return [];
        }

        $out = [];
        foreach ($disk->files($this->dir) as $path) {
            if (! str_ends_with($path, '.sql.gz')) {
                continue;
            }
            $out[] = [
                'name' => basename($path),
                'size' => $disk->size($path),
                'time' => $disk->lastModified($path),
            ];
        }

        usort($out, fn (array $a, array $b): int => $b['time'] <=> $a['time']);

        return $out;
    }

    /** @return array{name:string, size:int, time:int}|null */
    public function latest(): ?array
    {
        return $this->files()[0] ?? null;
    }

    public function count(): int
    {
        return count($this->files());
    }

    public function keep(): int
    {
        return $this->keep;
    }

    /** مسیرِ کاملِ فایلِ بکاپ برای دانلود (با پاک‌سازیِ نام)، یا null اگر نبود. */
    public function pathFor(string $name): ?string
    {
        $name = basename($name); // جلوگیری از path traversal
        $rel = $this->dir.'/'.$name;
        $disk = Storage::disk($this->disk);

        return $disk->exists($rel) ? $disk->path($rel) : null;
    }
}
