<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboratoriumSeeder extends Seeder
{
    public function run(): void
    {
        $labs = [
            ['kode_lab' => 'P101', 'nama_lab' => 'Lab Programming 1', 'gedung' => 'P'],
            ['kode_lab' => 'P102', 'nama_lab' => 'Lab Programming 2', 'gedung' => 'P'],
            ['kode_lab' => 'P103', 'nama_lab' => 'Lab Database', 'gedung' => 'P'],
            ['kode_lab' => 'T201', 'nama_lab' => 'Lab Network', 'gedung' => 'T'],
            ['kode_lab' => 'T202', 'nama_lab' => 'Lab Hardware', 'gedung' => 'T'],
        ];

        foreach ($labs as $lab) {
            DB::table('laboratorium')->insert([
                'kode_lab' => $lab['kode_lab'],
                'nama_lab' => $lab['nama_lab'],
                'gedung' => $lab['gedung'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}