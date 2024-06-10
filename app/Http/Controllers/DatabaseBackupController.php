<?php

namespace App\Http\Controllers;

use App\Models\DatabaseBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupController extends Controller
{

    public function index(Request $request)
    {
        $limit = $request->input('per_page', 10);
        $collection = DatabaseBackup::query()
            ->latest('id')
            ->when($request->field && $request->keyword, function ($query) use ($request) {
                $query->where($request->field, 'like', '%' . $request->keyword . '%');
            })
            ->paginate($limit);
        return view('database_backups', compact('collection'));
    }


    public function create()
    {
        try {
            $fileName = 'backup_' . now()->format('Ymd_His') . '.sql';
            $filePath = storage_path('app/public/backups/') . $fileName;

            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $filePath
            );

            // Using proc_open to execute the command
            $process = proc_open(
                $command,
                [
                    1 => ['pipe', 'w'], // STDOUT
                    2 => ['pipe', 'w'], // STDERR
                ],
                $pipes
            );

            if (is_resource($process)) {
                $stdout = stream_get_contents($pipes[1]);
                $stderr = stream_get_contents($pipes[2]);
                fclose($pipes[1]);
                fclose($pipes[2]);

                $return_value = proc_close($process);

                if ($return_value !== 0) {
                    throw new \Exception("Backup failed: " . $stderr);
                }
            } else {
                throw new \Exception("Could not initiate the backup process.");
            }

            $fileSize = Storage::size('public/backups/' . $fileName);

            $log = [
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file' => $filePath,
                'backup_by' => Auth::id(),
            ];

            DatabaseBackup::create($log);
            session()->flash('success', 'Successfully Backup');
            return response()->download($filePath);

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

    }
}
