<?php

return [
    'radius_meters' => env('PATROL_RADIUS_METERS', 30),
    'late_minutes' => env('PATROL_LATE_MINUTES', 15),
    'app_name' => env('APP_NAME', 'Patroli Security'),
    'shifts' => [
        'morning' => ['start' => '06:00', 'end' => '14:00', 'label' => 'Pagi'],
        'afternoon' => ['start' => '14:00', 'end' => '22:00', 'label' => 'Siang'],
        'night' => ['start' => '22:00', 'end' => '06:00', 'label' => 'Malam'],
    ],
    'emergency_types' => [
        'sos' => 'SOS Darurat',
        'fire' => 'Kebakaran',
        'theft' => 'Pencurian',
        'accident' => 'Kecelakaan',
        'medical' => 'Medis',
        'other' => 'Lainnya',
    ],
];
