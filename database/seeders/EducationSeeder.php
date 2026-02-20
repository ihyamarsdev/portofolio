<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $educations = [
            [
                'degree' => 'Bachelor of Computer Science',
                'field_of_study' => 'Computer Science',
                'institution' => 'Universitas Indonesia',
                'start_date' => '2016-08-01',
                'end_date' => '2020-07-31',
                'grade' => '3.85',
                'activities' => 'Graduated with honors. Specialized in distributed systems and cloud computing. Final project: Building scalable microservices architecture.',
                'order' => 1,
            ],
            [
                'degree' => 'Master of Information Technology',
                'field_of_study' => 'Cloud Computing',
                'institution' => 'Institut Teknologi Bandung',
                'start_date' => '2020-09-01',
                'end_date' => '2022-06-30',
                'grade' => '3.92',
                'activities' => 'Focus on cloud infrastructure, DevOps practices, and containerization. Thesis: Automated Deployment Strategies for Multi-Cloud Environments.',
                'order' => 2,
            ],
        ];

        foreach ($educations as $edu) {
            Education::updateOrCreate(
                [
                    'institution' => $edu['institution'],
                    'degree' => $edu['degree'],
                ],
                $edu
            );
        }
    }
}
