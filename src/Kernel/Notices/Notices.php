<?php declare(strict_types=1);

namespace tiFy\Kernel\Notices;

use Illuminate\Support\{Arr, Str};
use tiFy\Contracts\Kernel\Notices as NoticesContract;
use tiFy\Support\Proxy\View;

class Notices implements NoticesContract
{
    /**
     * Liste des types de notifications permis. error|warning|info|success.
     * @var array
     */
    protected $types = ['error', 'warning', 'info', 'success'];

    /**
     * Liste des notifications déclarées.
     * @var array
     */
    protected $items = [];

    /**
     * @inheritDoc
     */
    public function add($type, $message = '', $datas = [])
    {
        if (!$this->hasType($type)) :
            return '';
        endif;

        if (!isset($this->items[$type])) :
            $this->items[$type] = [];
        endif;

        $id = Str::random();
        $this->items[$type][$id] = compact('message', 'datas');

        return $id;
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function clear($type = null)
    {
        $type ? Arr::forget($this->items, $type) :  $this->items = [];
    }

    /**
     * @inheritDoc
     */
    public function count($type)
    {
        return ($notices = $this->get($type))
            ? count($notices)
            : 0;
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return !empty($this->items);
    }

    /**
     * @inheritDoc
     */
    public function get($type)
    {
        return Arr::get($this->items, $type, []);
    }

    /**
     * @inheritDoc
     */
    public function has($type)
    {
        return Arr::has($this->items, $type);
    }

    /**
     * @inheritDoc
     */
    public function getDatas($type = null)
    {
        $datas = [];
        if (is_null($type)) :
            foreach($this->getTypes() as $type) :
                $datas[$type] = $this->getDatas($type);
            endforeach;
        else :
            foreach ($this->get($type) as $id => $attrs) :
                $datas[$id] = $attrs['datas'];
            endforeach;
        endif;

        return $datas;
    }

    /**
     * @inheritDoc
     */
    public function getMessages($type = null)
    {
        $messages = [];
        if (is_null($type)) :
            foreach($this->getTypes() as $type) :
                $messages[$type] = $this->getMessages($type);
            endforeach;
        else :
            foreach ($this->get($type) as $id => $attrs) :
                $messages[$id] = $attrs['message'];
            endforeach;
        endif;

        return $messages;
    }

    /**
     * @inheritDoc
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @inheritDoc
     */
    public function hasType($type)
    {
        return in_array($type, $this->types);
    }

    /**
     * @inheritDoc
     */
    public function query($type, $query_args = [])
    {
        $results = [];
        if (!$notices = $this->get($type)) :
            return $results;
        endif;

        foreach ($notices as $id => $attrs) :
            $exists = @array_intersect($attrs['datas'], $query_args);

            if ($exists !== $query_args) :
                continue;
            endif;

            $results[$id] = $attrs;
        endforeach;

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function render($type)
    {
        if($messages = $this->getMessages($type)) {
            return (string)partial('notice', [
                'type'    => $type,
                'content' => (string)$this->viewer('notices', compact('messages', 'type'))
            ]);
        } else {
            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function setType($type)
    {
        if (!$this->hasType($type)) :
            array_push($this->types, $type);
        endif;
    }

    /**
     * @inheritDoc
     */
    public function setTypes($types = ['error', 'warning', 'info', 'success'])
    {
        $this->types = (array)$types;
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        $alias = 'notices.viewer' . spl_object_hash($this);

        if (!app()->has($alias)) {
            /** @var Notices $notices */
            $notices = app('notices');

            $viewer = View::getPlatesEngine([
                'directory' => class_info($notices)->getDirname() . '/views',
                'override_dir' => ($override_dir = class_info($this)->getDirname() . '/views') && is_dir($override_dir)
                    ? $override_dir
                    : null
            ]);
        } else {
            $viewer = app()->get($alias);
        }

        if (is_null($view)) {
            return $viewer;
        }

        return $viewer->render($view, $data);
    }
}
