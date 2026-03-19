<?php

namespace Database\Seeders;

use App\Models\Changelog;
use Illuminate\Database\Seeder;

class ChangelogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Changelog::create([
            'version' => '1.1.0',
            'title' => 'Branding & System Visibility Update',
            'description' => "We've launched several major improvements to the platform:\n\n- **School Branding**: Schools can now upload their own logos and favicons.\n- **Image Optimization**: Automatic WebP conversion for faster loading.\n- **Audit Logging**: Enhanced security tracking for all administrative actions.\n- **System Metadata**: Real-time IP and version tracking in the dashboard footer.\n- **Changelogs & Feedback**: New modules to keep you updated and hear your voice!",
            'type' => 'feature',
            'release_date' => now(),
            'is_published' => true,
        ]);
        
        Changelog::create([
            'version' => '1.0.5',
            'title' => 'UI & UX Enhancements',
            'description' => "- Integrated DataTables for all management modules.\n- Added Select2 for improved dropdown searching.\n- Standardized page titles and headers across the dashboard.",
            'type' => 'improvement',
            'release_date' => now()->subDays(5),
            'is_published' => true,
        ]);
    }
}
