<?php
namespace Purple\Anbu\Modules;

use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;

interface ModuleInterface
{
    public function getName();

    public function getVersion();

    public function getData();
    
    public function getSlug();

    public function register(Application $app);

    public function before(Application $app);

    public function after(Application $app, Response $response);
}