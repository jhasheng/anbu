<?php
namespace Purple\Anbu\Command;

use Illuminate\Console\Command;
use Purple\Anbu\Repositories\Repository;

class ClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'purple:clear';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the Anbu request history.';
    /**
     * Storage repository instance.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Create a new command instance.
     *
     * @param  Repository $repository
     */
    public function __construct(Repository $repository)
    {
        // Call parent constructor.
        parent::__construct();
        // Inject repository.
        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Output start.
        $this->info('Clearing Anbu request history...');
        // Clear history using repository.
        $this->repository->clear();
        // Output end.
        $this->info('Done!');
    }
}