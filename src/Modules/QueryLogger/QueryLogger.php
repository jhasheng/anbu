<?php

namespace Purple\Anbu\Modules\QueryLogger;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Events\Dispatcher;
use Purple\Anbu\Modules\Module;

class QueryLogger extends Module
{
    /**
     * The display name of the module.
     *
     * @var string
     */
    protected $name = 'Queries';

    /**
     * The short or URL friendly name of the module.
     *
     * @var string
     */
    protected $slug = 'queries';

    /**
     * A description of the modules purpose.
     *
     * @var string
     */
    protected $description = 'Log of executed SQL queries for the current request.';

    /**
     * Icon for side menu.
     *
     * @var string
     */
    protected $icon = 'database';

    /**
     * SQL keywords to highlight.
     *
     * @var array
     */
    protected $keywords = [
        'create',
        'from',
        'where',
        'select',
        'limit'
    ];

    /**
     * Executed before the profiled request.
     *
     * @return void
     */
    public function before()
    {
        /**
         * @var $event Dispatcher
         */
        $event = $this->app['events'];
        $this->data['queries'] = [];
        $event->listen(QueryExecuted::class, [$this, 'queryEventFired']);
    }

    /**
     * Handler for database query event.
     *
     * @return void
     */
    public function queryEventFired()
    {
        $args = func_get_args();
        /**
         * @var $result QueryExecuted
         */
        $result = $args[0];
        $this->data['queries'][] = [$this->highlightQuery($result->sql), $result->connectionName, floatval($result->time)];
    }

    /**
     * Highlight the executed query.
     *
     * @param  string $query
     * @return string
     */
    protected function highlightQuery($query)
    {
        foreach ($this->keywords as $keyword) {
            $query = preg_replace("/({$keyword})/", '<span class="sql-keyword">$1</span>', $query);
        }
        $query = preg_replace('/\`(.*?)\`/', '`<span class="sql-value">$1</span>`', $query);
        return $query;
    }

    /**
     * Executed after the profiled request.
     *
     * @param  Symfony/Component/HttpFoundation/Request  $response
     * @param  Symfony/Component/HttpFoundation/Response $response
     * @return void
     */
    public function after($request, $response)
    {
        $this->badge = count($this->data['queries']);
    }
}
