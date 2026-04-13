<?php

namespace App\Console\Commands;

use App\Models\CourseSession;
use App\Models\Room;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateRoomStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset room statuses and mark in-use based on currently active sessions';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = now()->toDateString();

        // Step 1: Reset all non-maintenance rooms to "empty"
        $reset = Room::where('status', '!=', Room::STATUS_MAINTENANCE)
            ->update(['status' => Room::STATUS_EMPTY]);

        $this->info("Reset {$reset} active room(s) to empty.");

        // Step 2: Find today's sessions that are currently active
        $activeSessions = CourseSession::with('room')
            ->where('session_date', $today)
            ->where('status', CourseSession::STATUS_SCHEDULED)
            ->get()
            ->filter(fn(CourseSession $s) => $s->isCurrentlyActive());

        if ($activeSessions->isEmpty()) {
            $this->info('No active sessions found. All rooms remain empty.');
            return Command::SUCCESS;
        }

        // Step 3: Mark rooms as in-use
        $updated = 0;
        foreach ($activeSessions as $session) {
            if ($session->room_id && $session->room && !$session->room->isMaintenance()) {
                Room::where('id', $session->room_id)
                    ->where('status', '!=', Room::STATUS_MAINTENANCE)
                    ->update(['status' => Room::STATUS_IN_USE]);

                $updated++;
                Log::info("rooms:update-status — Room {$session->room->name} marked in-use for session #{$session->id}");
            }
        }

        $this->info("Marked {$updated} room(s) as in-use based on active sessions.");
        return Command::SUCCESS;
    }
}
