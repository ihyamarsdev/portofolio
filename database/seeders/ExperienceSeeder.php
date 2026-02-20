<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $experiences = [
            [
                'position' => 'Senior DevOps Engineer',
                'company' => 'TechCorp Indonesia',
                'location' => 'Jakarta, Indonesia',
                'employment_type' => 'Full-time',
                'start_date' => '2023-01-01',
                'end_date' => null,
                'description' => 'Leading infrastructure modernization initiatives. Managing Kubernetes clusters serving 1M+ users. Implementing GitOps practices with ArgoCD and improving deployment frequency by 300%.',
                'achievements' => [
                    'Reduced deployment time from 2 hours to 10 minutes',
                    'Achieved 99.99% uptime for critical services',
                    'Built observability platform reducing incident response time by 60%',
                    'Mentored team of 5 junior engineers',
                ],
                'order' => 1,
                'skills' => ['Kubernetes', 'Docker', 'Terraform', 'ArgoCD', 'Prometheus', 'Grafana', 'GitHub Actions'],
            ],
            [
                'position' => 'DevOps Engineer',
                'company' => 'StartupXYZ',
                'location' => 'Bandung, Indonesia',
                'employment_type' => 'Full-time',
                'start_date' => '2021-06-01',
                'end_date' => '2022-12-31',
                'description' => 'Built CI/CD pipelines from scratch using GitLab CI. Managed AWS infrastructure including EC2, ECS, Lambda, and RDS. Implemented infrastructure as code with Terraform.',
                'achievements' => [
                    'Setup complete CI/CD pipeline reducing release cycle by 80%',
                    'Migrated monolithic application to microservices architecture',
                    'Automated backup and disaster recovery processes',
                    'Reduced AWS costs by 40% through resource optimization',
                ],
                'order' => 2,
                'skills' => ['AWS', 'Docker', 'Kubernetes', 'GitLab CI', 'Terraform', 'Ansible'],
            ],
            [
                'position' => 'Junior System Administrator',
                'company' => 'DataCenter Corp',
                'location' => 'Surabaya, Indonesia',
                'employment_type' => 'Full-time',
                'start_date' => '2020-08-01',
                'end_date' => '2021-05-31',
                'description' => 'Managed on-premise Linux servers and network equipment. Implemented monitoring solutions and automated routine tasks with Bash and Python scripts.',
                'achievements' => [
                    'Automated server provisioning reducing setup time by 50%',
                    'Implemented centralized logging with ELK stack',
                    'Maintained 99.9% uptime for 50+ servers',
                    'Created documentation for standard operating procedures',
                ],
                'order' => 3,
                'skills' => ['Linux', 'Bash', 'Python', 'Nginx', 'Docker', 'Ansible'],
            ],
        ];

        foreach ($experiences as $expData) {
            $skills = $expData['skills'];
            unset($expData['skills']);

            $experience = Experience::updateOrCreate(
                [
                    'company' => $expData['company'],
                    'position' => $expData['position'],
                ],
                $expData
            );

            // Attach skills
            foreach ($skills as $skillName) {
                $skill = Skill::where('name', $skillName)->first();
                if ($skill) {
                    $experience->skills()->syncWithoutDetaching($skill->id);
                }
            }
        }
    }
}
