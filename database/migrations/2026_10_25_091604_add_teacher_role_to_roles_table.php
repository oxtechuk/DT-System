<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Insert the 'teacher' role if it doesn't exist yet
        Role::firstOrCreate(
            ['slug' => 'teacher'],
            [
                'name' => 'مدرس',
                'description' => 'مدرس له لوحة تحكم خاصة وكوميشن على الزيارات',
            ]
        );
    }

    public function down(): void
    {
        Role::where('slug', 'teacher')->delete();
    }
};
