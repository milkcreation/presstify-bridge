<?php

namespace tiFy\Kernel\Notices;

/**
 * Trait NoticesTrait
 * @package tiFy\Kernel\Notices
 *
 * @method string noticesAdd(string $type, string $message = '', array $datas = [])
 * @method array noticesAll(string $type)
 * @method array noticesGetDatas(string $type)
 * @method array noticesGetMessages(string $type)
 * @method bool noticesHasType(string $type)
 * @method array noticesQuery(string $type, array $query_args = [])
 * @method void noticesSetType(string $type)
 * @method void noticesSetTypes(array $types = ['error', 'warning', 'info', 'success'])
 */
trait NoticesTrait
{
    /**
     * Instance du controleur de notices.
     * @var Notices
     */
    protected $instance;

    public function __call($name, $arguments)
    {
        if (preg_match('#^notices(.*)#', $name, $matches)) :
            $method = lcfirst($matches[1]);
            if (!$this->instance) :
                $this->instance = app('notices');
            endif;

            if (method_exists($this->instance, $method)) :
                return call_user_func_array([$this->instance, $method], $arguments);
            endif;
        endif;
    }
}
