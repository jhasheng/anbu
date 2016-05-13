<?php

namespace Purple\Anbu\Controller;

use Purple\Anbu\Purple;
use Purple\Anbu\Repositories\DatabaseRepository;
use Purple\Anbu\Repositories\Repository;
use Purple\Anbu\Services\ModuleHydrator;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    /**
     * Profiler instance.
     *
     * @var Purple
     */
    protected $purple;

    /**
     * Repository instance.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Hydrator instance.
     *
     * @var ModuleHydrator
     */
    protected $hydrator;

    /**
     * Disable profiler for own requests.
     *
     * @param  Purple $purple
     * @param  DatabaseRepository $repository
     * @param  ModuleHydrator $hydrator
     */
    public function __construct(
        Purple $purple,
        DatabaseRepository $repository,
        ModuleHydrator $hydrator
    )
    {
        // Set injected properties.
        $this->purple     = $purple;
        $this->repository = $repository;
        $this->hydrator   = $hydrator;
    }
}
