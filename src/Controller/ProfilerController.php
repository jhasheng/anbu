<?php

namespace Purple\Anbu\Controller;

use Purple\Anbu\Modules\ModuleInterface;
use Purple\Anbu\Storage\StorageInterface;
use Purple\Anbu\Services\MenuBuilder;

class ProfilerController extends BaseController
{
    /**
     * Show the profiler.
     * @param  int $key
     * @param  string $module
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($key = null, $module = 'dashboard')
    {
//        try {
        $record = $this->repository->get($key);

        $this->hydrator->hydrate($record);
        $module = $this->purple->getModule($module);
        $data   = $this->buildViewData($record, $module);
        $data['uri'] = '';
        $data['version'] = '';
        return view('anbu.index', $data);
//        } catch (Exception $exception) {
//            throw new Exception($exception->getMessage());
//            return view('anbu.error', ['error' => $exception->getMessage() . $exception->getLine()]);
//        }
    }

    /**
     * Build the profiler view data array.
     *
     * @param  StorageInterface $record
     * @param  ModuleInterface $module
     * @return array
     */
    protected function buildViewData(StorageInterface $record, ModuleInterface $module)
    {
        $data = $this->getGlobalData($record);
        array_set($data, 'child', $this->renderModule($module));
        array_set($data, 'storage', $record);
        array_set($data, 'current', $module);
        return $data;
    }

    /**
     * Get the global data collection.
     * @param  StorageInterface $record
     * @return array
     */
    protected function getGlobalData(StorageInterface $record)
    {
        $global  = [];
        $modules = $this->purple->getModules();
        foreach ($modules as $module) {
            $data   = $module->getGlobal();
            $global = array_merge($data, $global);
        }
        $global['menu'] = with(new MenuBuilder)->build($modules, $record->id);
        return $global;
    }

    /**
     * Render the child view for a module.
     *
     * @param  ModuleInterface $module
     * @return \View
     */
    protected function renderModule(ModuleInterface $module)
    {
        $module->live();
        $template = $module->getTemplate();
        return view("anbu.modules.{$template}", $module->getData())->render();
    }
}
