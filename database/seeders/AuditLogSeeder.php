<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::role('super admin')->first();
        $adminId = $admin ? $admin->id : null;

        $logs = [
            [
                'action' => 'System Initialization',
                'details' => ['message' => 'Core SchoolSaaS architecture established with Roles & Permissions.'],
            ],
            [
                'action' => 'Feature Implementation',
                'model' => 'SubscriptionPackage',
                'details' => ['message' => 'Subscription Package CRUD and Limit system implemented.'],
            ],
            [
                'action' => 'UI Enhancement',
                'details' => ['message' => 'Integrated DataTables.net for all administrative management tables.'],
            ],
            [
                'action' => 'UX Enhancement',
                'details' => ['message' => 'Integrated Select2 for multi-country and city dependent dropdowns.'],
            ],
            [
                'action' => 'Branding Implementation',
                'model' => 'School',
                'details' => ['message' => 'Added Logo and Favicon support with WebP optimization.'],
            ],
            [
                'action' => 'Public Storefront',
                'model' => 'School',
                'details' => ['message' => 'Launched dedicated public landing pages for schools at /s/{slug}.'],
            ],
            [
                'action' => 'System Visibility',
                'details' => ['message' => 'Audit Logging and System Footer (IP/Version) tracking enabled.'],
            ],
        ];

        foreach ($logs as $log) {
            AuditLog::create(array_merge($log, [
                'user_id' => $adminId,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'System Seeder/1.0',
                'created_at' => now()->subHours(count($logs) - AuditLog::count()),
            ]));
        }
    }
}
