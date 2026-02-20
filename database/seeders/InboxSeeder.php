<?php

namespace Database\Seeders;

use App\Models\Inbox;
use Illuminate\Database\Seeder;

class InboxSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@techcorp.com',
                'subject' => 'DevOps Engineer Opportunity',
                'message' => 'Hi, I came across your portfolio and I\'m impressed with your experience. We\'re looking for a Senior DevOps Engineer to join our team. Would you be interested in discussing this opportunity?',
                'is_read' => true,
                'replied_at' => '2024-01-20 14:30:00',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'mchen@startup.io',
                'subject' => 'Collaboration on Open Source Project',
                'message' => 'Hello! I saw your contributions to the DevOps community. We\'re working on an open-source infrastructure tool and would love to collaborate. Are you open to discussing this?',
                'is_read' => true,
                'replied_at' => '2024-01-18 10:15:00',
            ],
            [
                'name' => 'Amanda Williams',
                'email' => 'awilliams@cloudtech.com',
                'subject' => 'Consulting Inquiry',
                'message' => 'Hi, our company is planning to migrate to Kubernetes and we\'re looking for a consultant to help us with the transition. Would you be available for a consulting engagement?',
                'is_read' => false,
                'replied_at' => null,
            ],
            [
                'name' => 'David Park',
                'email' => 'dpark@university.edu',
                'subject' => 'Guest Lecture Invitation',
                'message' => 'Dear DevOps Engineer, we would like to invite you to give a guest lecture about modern DevOps practices at our university. Our students would benefit greatly from your industry experience.',
                'is_read' => true,
                'replied_at' => '2024-01-15 16:45:00',
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.r@devconference.com',
                'subject' => 'Conference Speaker Invitation',
                'message' => 'Hello! We\'re organizing the annual DevOps Conference and would love to have you as a speaker. Your expertise in Kubernetes and infrastructure automation would be perfect for our audience.',
                'is_read' => false,
                'replied_at' => null,
            ],
        ];

        foreach ($messages as $msg) {
            Inbox::updateOrCreate(
                [
                    'email' => $msg['email'],
                    'subject' => $msg['subject'],
                ],
                $msg
            );
        }
    }
}
