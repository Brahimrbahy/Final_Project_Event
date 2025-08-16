<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseHelper
{
    /**
     * Get the appropriate MONTH function for the current database driver
     */
    public static function monthFunction($column = 'created_at'): string
    {
        $driver = DB::connection()->getDriverName();
        
        switch ($driver) {
            case 'sqlite':
                return "strftime('%m', {$column})";
            case 'mysql':
            case 'mariadb':
                return "MONTH({$column})";
            case 'pgsql':
                return "EXTRACT(MONTH FROM {$column})";
            default:
                return "strftime('%m', {$column})"; // Default to SQLite
        }
    }

    /**
     * Get the appropriate YEAR function for the current database driver
     */
    public static function yearFunction($column = 'created_at'): string
    {
        $driver = DB::connection()->getDriverName();
        
        switch ($driver) {
            case 'sqlite':
                return "strftime('%Y', {$column})";
            case 'mysql':
            case 'mariadb':
                return "YEAR({$column})";
            case 'pgsql':
                return "EXTRACT(YEAR FROM {$column})";
            default:
                return "strftime('%Y', {$column})"; // Default to SQLite
        }
    }

    /**
     * Get the appropriate DATE function for the current database driver
     */
    public static function dateFunction($column = 'created_at'): string
    {
        $driver = DB::connection()->getDriverName();
        
        switch ($driver) {
            case 'sqlite':
                return "date({$column})";
            case 'mysql':
            case 'mariadb':
                return "DATE({$column})";
            case 'pgsql':
                return "DATE({$column})";
            default:
                return "date({$column})"; // Default to SQLite
        }
    }

    /**
     * Get monthly revenue query with cross-database compatibility
     */
    public static function getMonthlyRevenueSelect($amountColumn = 'admin_fee', $dateColumn = 'created_at'): array
    {
        return [
            DB::raw(self::monthFunction($dateColumn) . ' as month'),
            DB::raw(self::yearFunction($dateColumn) . ' as year'),
            DB::raw("SUM({$amountColumn}) as revenue"),
            DB::raw('COUNT(*) as transactions')
        ];
    }

    /**
     * Get daily revenue query with cross-database compatibility
     */
    public static function getDailyRevenueSelect($amountColumn = 'admin_fee', $dateColumn = 'created_at'): array
    {
        return [
            DB::raw(self::dateFunction($dateColumn) . ' as date'),
            DB::raw("SUM({$amountColumn}) as revenue"),
            DB::raw('COUNT(*) as transactions')
        ];
    }
}
