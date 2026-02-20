<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $certificates = [
            [
                'name' => 'AWS Certified Solutions Architect',
                'issuer' => 'Amazon Web Services',
                'credential_id' => 'AWS-SA-12345678',
                'credential_url' => 'https://aws.amazon.com/verification',
                'issue_date' => '2023-06-15',
                'expiry_date' => '2026-06-15',
                'is_active' => true,
            ],
            [
                'name' => 'Certified Kubernetes Administrator',
                'issuer' => 'Cloud Native Computing Foundation',
                'credential_id' => 'CKA-123456-1234',
                'credential_url' => 'https://www.cncf.io/certification/cka/',
                'issue_date' => '2023-03-20',
                'expiry_date' => '2026-03-20',
                'is_active' => true,
            ],
            [
                'name' => 'HashiCorp Certified: Terraform Associate',
                'issuer' => 'HashiCorp',
                'credential_id' => 'TFA-123456',
                'credential_url' => 'https://certification.hashicorp.com/',
                'issue_date' => '2022-11-10',
                'expiry_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'GitHub Actions Certificate',
                'issuer' => 'GitHub',
                'credential_id' => 'GHA-987654',
                'credential_url' => 'https://github.com/certifications',
                'issue_date' => '2023-08-05',
                'expiry_date' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Google Cloud Digital Leader',
                'issuer' => 'Google Cloud',
                'credential_id' => 'GCP-DL-456789',
                'credential_url' => 'https://cloud.google.com/certification',
                'issue_date' => '2022-09-01',
                'expiry_date' => '2025-09-01',
                'is_active' => true,
            ],
            [
                'name' => 'Ansible Certified Expert',
                'issuer' => 'Red Hat',
                'credential_id' => 'ANS-EX-123456',
                'credential_url' => 'https://www.redhat.com/en/services/certification',
                'issue_date' => '2023-01-15',
                'expiry_date' => null,
                'is_active' => true,
            ],
        ];

        foreach ($certificates as $cert) {
            Certificate::updateOrCreate(
                ['credential_id' => $cert['credential_id']],
                $cert
            );
        }
    }
}
