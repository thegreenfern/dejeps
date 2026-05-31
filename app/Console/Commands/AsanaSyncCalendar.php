<?php

namespace App\Console\Commands;

use App\Models\CalendarEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AsanaSyncCalendar extends Command
{
    protected $signature   = 'asana:sync';
    protected $description = 'Sync calendar events from the Asana project';

    private const PROJECT_GID = '1213110688540443';
    private const FIELDS = 'gid,name,due_on,start_on,completed,memberships.section.name,custom_fields,assignee.name';

    public function handle(): int
    {
        $token = env('ASANA_TOKEN');
        if (!$token) {
            $this->error('ASANA_TOKEN not set in .env');
            return 1;
        }

        $this->info('Fetching tasks from Asana…');

        $offset = null;
        $synced = 0;

        do {
            $params = ['opt_fields' => self::FIELDS, 'limit' => 100];
            if ($offset) $params['offset'] = $offset;

            $response = Http::withToken($token)
                ->get('https://app.asana.com/api/1.0/projects/' . self::PROJECT_GID . '/tasks', $params);

            if (!$response->successful()) {
                $this->error('Asana API error: ' . $response->status());
                return 1;
            }

            $body = $response->json();

            foreach ($body['data'] as $task) {
                $section = collect($task['memberships'] ?? [])
                    ->map(fn($m) => $m['section']['name'] ?? null)
                    ->filter()
                    ->first();

                $type = collect($task['custom_fields'] ?? [])
                    ->map(fn($cf) => $cf['enum_value']['name'] ?? null)
                    ->filter()
                    ->first();

                CalendarEvent::updateOrCreate(
                    ['asana_gid' => $task['gid']],
                    [
                        'name'          => $task['name'],
                        'section'       => $section,
                        'event_type'    => $type,
                        'start_on'      => $task['start_on'] ?? null,
                        'due_on'        => $task['due_on'] ?? null,
                        'completed'     => $task['completed'] ?? false,
                        'assignee_name' => $task['assignee']['name'] ?? null,
                        'asana_url'     => 'https://app.asana.com/0/' . self::PROJECT_GID . '/' . $task['gid'],
                        'synced_at'     => now(),
                    ]
                );
                $synced++;
            }

            $offset = $body['next_page']['offset'] ?? null;
        } while ($offset);

        $this->info("Synced {$synced} events.");
        return 0;
    }
}
