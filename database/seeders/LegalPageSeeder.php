<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AdminMember;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\AdminManagenent;
use App\Models\LegalPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LegalPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        LegalPage::create([
            'page_type' => 'PrivacyPolicy',
            'description' => '<p>Privacy Policy</p>',
        ]);
        
        LegalPage::create([
            'page_type' => 'TermsAndCondition',
            'description' => '<p>Terms And Condition</p>',
        ]);
        
        LegalPage::create([
            'page_type' => 'CancellationAndReturnPolicy',
            'description' => '<p>Cancellation And Return Policy</p>',
        ]);
        
        LegalPage::create([
            'page_type' => 'AboutUs',
            'description' => '<p>About Us</p>',
        ]);
    
    }
}
