<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Detainee;
use Illuminate\Support\Carbon;

class DetaineeSeeder extends Seeder
{
    public function run(): void
    {
        $detainees = [
            [
                'name' => 'محمد أحمد',
                'gender' => 'male',
                'birth_date' => '1982-09-20',
                'location' => 'الرياض',
                'detention_date' => '2024-08-25',
                'status' => 'martyr',
                'detaining_authority' => 'الدعم السريع',
                'prison_name' => 'محمد الهادي',
                'is_forced_disappearance' => true,
                'family_contact_name' => 'احمد الهادي',
                'family_contact_phone' => '001-367-331-1234',
                'notes' => 'تم اعتقاله اثناء عودته من السوق',
                'source' => 'العائلة',
                'is_approved' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]

        ];

        foreach ($detainees as $data) {
            Detainee::create($data);
        }
    }
}
