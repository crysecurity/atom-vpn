<?php

namespace Cr4sec\AtomVPN\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Cr4sec\AtomVPN\Models\Session;

class ClearingUnusedSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atom-vpn:clearing-unused-sessions {--hard : Delete found sessions without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clearing unused sessions';

    private function getConfirmationText(int $count): string
    {
        return sprintf(
            'We found %d unused sessions. Do you really want to delete them?',
            $count
        );
    }

    private function isIgnoreWarning(): bool
    {
        return env('APP_ENV') === 'local' || $this->option('hard');
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function handle(): int
    {
        $sessions = Session::whereNull('started_at')
            ->whereNull('closed_at')
            ->where('created_at', '<=', Carbon::now()->subMinutes(5))
            ->orWhere(function (Builder $query) {
                $query
                    ->whereNull('closed_at')
                    ->where(
                        'started_at',
                        '<=',
                        Carbon::now()->subHours(config('atom_vpn.session_lifetime_hours'))
                    );
            })
            ->get();

        if (!$sessions->count()) {
            $this->info('We didn\'t find any unused sessions');

            return self::SUCCESS;
        }

        if ($this->isIgnoreWarning() || $this->confirm($this->getConfirmationText($sessions->count()))) {
            $counter = 0;

            $sessions->each(function (Session $session) use (&$counter) {
                if (!$session->delete()) {
                    $this->error('Couldn\'t delete session with ID ' . $session->id);
                } else {
                    $counter++;
                }
            });

            if ($counter) {
                $this->info("$counter sessions were successfully deleted");
            }
        }

        return self::SUCCESS;
    }
}
