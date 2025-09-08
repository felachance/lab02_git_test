<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Assignment;
use Carbon\Carbon;

class DeactivateInactiveEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deactivate-inactive-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desactive les employés qui n\'ont pas été assignés à une shift depuis 2 mois';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoff = Carbon::now()->subMonths(value: 2);

        // Get IDs of users with assignments in the last 2 months
        $recentlyAssignedUserIds = Assignment::where('assigned_at', '>=', $cutoff)
            ->pluck('id_user')
            ->unique();

        // Deactivate users who are still active but not recently assigned
        $usersToDeactivate = User::where('active', true)
            ->whereNotIn('id', $recentlyAssignedUserIds)
            ->get();

        foreach ($usersToDeactivate as $user) {
            if ($user->created_at > $cutoff) {
                $this->info("Skipped: {$user->name} (ID: {$user->id}) - Created in the last 2 month");
                continue;
            }
            $user->active = 0;
            $user->save();
            $this->info("✅ Deactivated: {$user->name} (ID: {$user->id})");
        }

        $this->info("Finished: " . $usersToDeactivate->count() . " users deactivated.");
    }
}
