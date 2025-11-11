<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemDiagnosticsController extends Controller
{
    /**
     * Check system status and database tables
     */
    public function checkSystem()
    {
        $diagnostics = [];
        
        // 1. Check if database is connected
        try {
            DB::connection()->getPdo();
            $diagnostics['database_connected'] = true;
        } catch (\Exception $e) {
            $diagnostics['database_connected'] = false;
            $diagnostics['database_error'] = $e->getMessage();
        }
        
        // 2. Check if tbl_locations table exists
        try {
            $diagnostics['locations_table_exists'] = Schema::hasTable('tbl_locations');
        } catch (\Exception $e) {
            $diagnostics['locations_table_exists'] = false;
            $diagnostics['table_check_error'] = $e->getMessage();
        }
        
        // 3. If table exists, count records
        if ($diagnostics['locations_table_exists'] ?? false) {
            try {
                $count = DB::table('tbl_locations')->count();
                $diagnostics['locations_count'] = $count;
                $diagnostics['locations_status'] = $count > 0 ? 'populated' : 'empty';
                
                // Get sample
                if ($count > 0) {
                    $sample = DB::table('tbl_locations')->limit(3)->get();
                    $diagnostics['sample_data'] = $sample;
                }
            } catch (\Exception $e) {
                $diagnostics['locations_count_error'] = $e->getMessage();
            }
        }
        
        // 4. Check other important tables
        $tables = ['users', 'contractors', 'clients', 'invoices'];
        $diagnostics['tables_status'] = [];
        foreach ($tables as $table) {
            try {
                $exists = Schema::hasTable($table);
                $count = $exists ? DB::table($table)->count() : 0;
                $diagnostics['tables_status'][$table] = [
                    'exists' => $exists,
                    'count' => $count
                ];
            } catch (\Exception $e) {
                $diagnostics['tables_status'][$table] = [
                    'exists' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'diagnostics' => $diagnostics,
            'timestamp' => now()->toISOString()
        ]);
    }
}
