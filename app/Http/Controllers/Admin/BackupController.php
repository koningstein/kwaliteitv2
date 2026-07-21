<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Middleware\RoleMiddleware;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleMiddleware::using('admin')),
        ];
    }

    public function index()
    {
        return view('admin.backup.index');
    }

    public function download(): StreamedResponse
    {
        $dbName   = config('database.connections.mysql.database');
        $filename = 'backup_' . $dbName . '_' . now()->format('Y-m-d_H-i-s') . '.sql';

        return response()->stream(function () use ($dbName) {
            $this->writeSqlDump($dbName);
        }, 200, [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ]);
    }

    private function writeSqlDump(string $dbName): void
    {
        $pdo = DB::connection()->getPdo();

        echo "-- Database backup: {$dbName}\n";
        echo "-- Generated: " . now()->toDateTimeString() . "\n";
        echo "-- Laravel backup tool\n\n";
        echo "SET FOREIGN_KEY_CHECKS=0;\n";
        echo "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n";
        echo "SET NAMES utf8mb4;\n\n";

        $tables = array_column(
            DB::select('SHOW FULL TABLES WHERE Table_type = ?', ['BASE TABLE']),
            'Tables_in_' . $dbName
        );

        foreach ($tables as $table) {
            $this->dumpTable($pdo, $table);
            flush();
            ob_flush();
        }

        echo "SET FOREIGN_KEY_CHECKS=1;\n";
    }

    private function dumpTable(\PDO $pdo, string $table): void
    {
        $quotedTable = "`{$table}`";

        echo "-- ----------------------------\n";
        echo "-- Table structure for {$table}\n";
        echo "-- ----------------------------\n";
        echo "DROP TABLE IF EXISTS {$quotedTable};\n";

        $createRow = DB::select("SHOW CREATE TABLE {$quotedTable}");
        echo $createRow[0]->{'Create Table'} . ";\n\n";

        $count = DB::table($table)->count();
        if ($count === 0) {
            return;
        }

        echo "-- ----------------------------\n";
        echo "-- Records of {$table}\n";
        echo "-- ----------------------------\n";

        $chunkSize = 500;
        DB::table($table)->orderByRaw('1')->chunk($chunkSize, function ($rows) use ($table, $pdo) {
            $quotedTable = "`{$table}`";
            $rowsArray   = array_map(fn($r) => (array) $r, $rows->all());

            if (empty($rowsArray)) {
                return;
            }

            $columns = '`' . implode('`, `', array_keys($rowsArray[0])) . '`';

            foreach ($rowsArray as $row) {
                $values = implode(', ', array_map(function ($value) use ($pdo) {
                    if ($value === null) {
                        return 'NULL';
                    }
                    return $pdo->quote((string) $value);
                }, $row));

                echo "INSERT INTO {$quotedTable} ({$columns}) VALUES ({$values});\n";
            }

            flush();
            ob_flush();
        });

        echo "\n";
    }
}
