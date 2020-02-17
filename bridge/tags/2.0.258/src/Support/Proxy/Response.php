<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Psr\Http\Message\ResponseInterface as PsrResponse;
use tiFy\Contracts\Http\Response as ResponseContract;

/**
 * @method static ResponseContract instance($content = '', int $status = 200, array $headers = [])
 * @method static PsrResponse psr()
 * @method static ResponseContract send()
 */
class Response extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return ResponseContract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier()
    {
        return 'response';
    }
}