<?php

namespace Tests\Feature;

use App\Filament\Widgets\LatestMessagesWidget;
use App\Filament\Widgets\SystemStatusWidget;
use App\Models\Inbox;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardRevampTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_renders_with_hero_header(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertSuccessful()
            ->assertSee('Control')
            ->assertSee('Center')
            ->assertSee('New Project')
            ->assertSee('Build CV');
    }

    public function test_latest_messages_widget_renders_inbox_entries(): void
    {
        Inbox::create([
            'name' => 'Jane Recruiter',
            'email' => 'jane@example.com',
            'subject' => 'DevOps role',
            'message' => 'We would love to chat.',
            'is_read' => false,
        ]);

        Livewire::test(LatestMessagesWidget::class)
            ->assertSuccessful()
            ->assertSee('Jane Recruiter')
            ->assertSee('DevOps role');
    }

    public function test_system_status_widget_renders_fallback_services(): void
    {
        Livewire::test(SystemStatusWidget::class)
            ->assertSuccessful()
            ->assertSee('System Status')
            ->assertSee('Application')
            ->assertSee('Database');
    }
}
