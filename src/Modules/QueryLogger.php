<?php

namespace Purple\Anbu\Modules;

use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;

class QueryLogger extends AbstractModule
{
    protected $template = 'queries';
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
        'add', 'all', 'alter', 'analyze', 'and', 'as', 'asc', 'asensitive', 'before', 'between', 'bigint',
        'binary', 'blob', 'both', 'by', 'call', 'cascade', 'case', 'change', 'char', 'character', 'check',
        'collate', 'column', 'condition', 'constraint', 'continue', 'convert', 'create', 'cross',
        'current_date', 'current_time', 'current_timestamp', 'current_user', 'cursor', 'database',
        'databases', 'day_hour', 'day_microsecond', 'day_minute', 'day_second', 'dec', 'decimal', 'declare',
        'default', 'delayed', 'delete', 'desc', 'describe', 'deterministic', 'distinct', 'distinctrow', 'div',
        'double', 'drop', 'dual', 'each', 'else', 'elseif', 'enclosed', 'escaped', 'exists', 'exit',
        'explain', 'false', 'fetch', 'float', 'float4', 'float8', 'for', 'force', 'foreign', 'from',
        'fulltext', 'grant', 'group', 'having', 'high_priority', 'hour_microsecond', 'hour_minute',
        'hour_second', 'if', 'ignore', 'in', 'index', 'infile', 'inner', 'inout', 'insensitive', 'insert',
        'int', 'int1', 'int2', 'int3', 'int4', 'int8', 'integer', 'interval', 'into', 'is', 'iterate', 'join',
        'key', 'keys', 'kill', 'leading', 'leave', 'left', 'like', 'limit', 'lines', 'load', 'localtime',
        'localtimestamp', 'lock', 'long', 'longblob', 'longtext', 'loop', 'low_priority', 'match',
        'mediumblob', 'mediumint', 'mediumtext', 'middleint', 'minute_microsecond', 'minute_second', 'mod',
        'modifies', 'natural', 'not', 'no_write_to_binlog', 'null', 'numeric', 'on', 'optimize', 'option',
        'optionally', 'or', 'order', 'out', 'outer', 'outfile', 'precision', 'primary', 'procedure', 'purge',
        'read', 'reads', 'real', 'references', 'regexp', 'release', 'rename', 'repeat', 'replace', 'require',
        'restrict', 'return', 'revoke', 'right', 'rlike', 'schema', 'schemas', 'second_microsecond', 'select',
        'sensitive', 'separator', 'set', 'show', 'smallint', 'soname', 'spatial', 'specific', 'sql',
        'sqlexception', 'sqlstate', 'sqlwarning', 'sql_big_result', 'sql_calc_found_rows', 'sql_small_result',
        'ssl starting', 'straight_join', 'table', 'terminated', 'then', 'tinyblob', 'tinyint', 'tinytext',
        'to', 'trailing', 'trigger true', 'undo', 'union', 'unique', 'unlock', 'unsigned', 'update', 'usage',
        'use using', 'utc_date', 'utc_time', 'utc_timestamp', 'values', 'varbinary', 'varchar',
        'varcharacter', 'varying when', 'where', 'while', 'with', 'write', 'xor year_month', 'zerofill',
        'asensitive', 'call', 'condition', 'connection', 'continue', 'cursor', 'declare deterministic',
        'each', 'elseif', 'exit', 'fetch', 'goto', 'inout', 'insensitive', 'iterate', 'label', 'leave',
        'loop', 'modifies', 'out', 'reads', 'release repeat', 'return', 'schema', 'schemas', 'sensitive',
        'specific', 'sql', 'sqlexception', 'sqlstate', 'sqlwarning', 'trigger undo', 'upgrade', 'while'
    ];

    /**
     * Executed before the profiled request.
     *
     * @param Application $app
     */
    public function before(Application $app)
    {
    }

    /**
     * Highlight the executed query.
     *
     * @param  string $query
     * @return string
     */
    protected function highlightQuery($query)
    {
//        $hits = [];
//        foreach ($this->keywords as $keyword) {
////            var_dump(preg_match('/\s?' . $keyword . '\s?/', $query));
//            if (preg_match('/\s?' . $keyword . '\s?/', $query)) {
//                array_push($hits, $keyword);
//            }
////            $query = preg_replace('/\s?(' . $keyword . ')\s?/', '<span class="sql-keyword">$1</span>', $query, 1);
//        }
//
//        foreach ($hits as $key) {
//            $query = preg_replace('/\s?(' . $key . ')\s?/', '<span class="sql-keyword">$1</span>', $query, 1);
//        }
//
//        $query = preg_replace('/\`(.*?)\`/', '`<span class="sql-value">$1</span>`', $query);
        return $query;
    }

    /**
     * Executed after the profiled request.
     *
     * @param  Application $app
     * @param  Response $response
     */
    public function after(Application $app, Response $response)
    {
        $connections           = $app['db']->getConnections();
        $logs                  = $connections['mysql']->getQueryLog();
        $this->data['queries'] = [];

        foreach ($logs as $log) {
            array_push($this->data['queries'], [$this->highlightQuery($log['query']), $log['bindings'], $log['time']]);
        }
        unset($log);
        $this->badge = count($this->data['queries']);
    }
}
