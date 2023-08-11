<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Spatie\DbDumper\Databases\MySql;
use Illuminate\Http\Request;
use Artisan;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SqlDumpController extends Controller
{

    public function download()
    {
        // Set the database access credentials
        $db_host = env('DB_HOST', '127.0.0.1');
        $db_username = env('DB_USERNAME', 'root');
        $db_password = env('DB_PASSWORD', '');
        $db_name = env('DB_DATABASE');

        $mysqlDumpBinaryPath = $this->getMysqldumpPath();
        $filename = 'database_dump' . date('Y-m-d_His') . '.sql';
        $backup_path = storage_path($filename);

        // Execute the mysqldump command
        $command = '"' . $mysqlDumpBinaryPath . '" --host=' . $db_host . ' --user=' . $db_username . ' --password=' . $db_password . ' ' . $db_name . ' > ' . $backup_path;
        exec($command);

      // Send the backup file as a download
        return response()->download($backup_path, $filename, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function getMysqldumpPath()
    {
        // Determine the operating system
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        // Set the correct path to mysqldump based on the operating system
        return $isWindows
            ? 'D:\xampp\mysql\bin\mysqldump' // Replace this with the correct path on Windows
            : '/usr/bin/mysqldump'; // Replace this with the correct path on Linux/Unix
    }

    public function importSql(Request $request)
    {
        $sqlFile = $request->file('sql_file');

        if ($sqlFile) {
            //   DB::table('hrms_productify')->truncate();
            $stream = fopen($sqlFile->path(), 'r');
            $sql = '';  

            while (!feof($stream)) {
                $line = fgets($stream);

                // Skip comments and empty lines
                if (substr(trim($line), 0, 2) == '--' || trim($line) == '') {
                    continue;
                }

                $sql .= $line;

                // Execute SQL statement if it ends with a semicolon
                if (substr(trim($line), -1, 1) == ';') {
                    DB::unprepared($sql);
                    $sql = ''; // Reset the SQL buffer
                }
            }

            fclose($stream);

            return redirect()->back()->with('success', 'SQL data imported successfully.');
        } else {
            return redirect()->back()->with('error', 'Please upload an SQL file.');
        }
    }
}
