<?php

namespace Purple\Anbu\Controller;

use View;
use Exception;
use Purple\Anbu\Modules\Module;
use Purple\Anbu\Models\Storage;
use Purple\Anbu\Services\MenuBuilder;

class ProfilerController extends BaseController
{
    /**
     * Show the profiler.
     * @param  int    $key
     * @param  string $module
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($key = null, $module = 'dashboard')
    {
        try {
            $record = $this->repository->get($key);
            $this->hydrator->hydrate($record);
            $module = $this->purple->getModule($module);
            $data = $this->buildViewData($record, $module);
            return view('anbu.index', $data);
        } catch (Exception $exception) {
//            throw new Exception($exception->getMessage());
            return view('anbu.error', ['error' => $exception->getMessage() . $exception->getLine()]);
        }
    }

    /**
     * Build the profiler view data array.
     *
     * @param  Storage $record
     * @param  Module  $module
     * @return array
     */
    protected function buildViewData(Storage $record, Module $module)
    {
        $data = $this->getGlobalData($record);
        array_set($data, 'child', $this->renderModule($module));
        array_set($data, 'storage', $record);
        array_set($data, 'current', $module);
        return $data;
    }

    /**
     * Get the global data collection.
     * @param  Storage $record
     * @return array
     */
    protected function getGlobalData(Storage $record)
    {
        $global = [];
        $modules = $this->purple->getModules();
        foreach ($modules as $module) {
            $data = $module->getGlobal();
            $global = array_merge($data, $global);
        }
        $global['menu'] = with(new MenuBuilder)->build($modules, $record->id);
        return $global;
    }

    /**
     * Render the child view for a module.
     *
     * @param  Module $module
     * @return View
     */
    protected function renderModule(Module $module)
    {
        View::addNamespace('anbu_module', $module->getPath());
        $module->live();
        $template = $module->getTemplate();
        return View::make("anbu_module::{$template}", $module->getData());
    }
}
