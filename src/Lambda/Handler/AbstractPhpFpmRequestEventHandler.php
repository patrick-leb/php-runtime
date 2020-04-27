<?php

declare(strict_types=1);

/*
 * This file is part of Placeholder PHP Runtime.
 *
 * (c) Carl Alexander <contact@carlalexander.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Placeholder\Runtime\Lambda\Handler;

use Placeholder\Runtime\FastCgi\FastCgiHttpResponse;
use Placeholder\Runtime\FastCgi\FastCgiRequest;
use Placeholder\Runtime\FastCgi\PhpFpmProcess;
use Placeholder\Runtime\Lambda\InvocationEvent\HttpRequestEvent;
use Placeholder\Runtime\Lambda\Response\HttpResponse;

/**
 * Base Lambda invocation event handler for handlers that use PHP-FPM.
 */
abstract class AbstractPhpFpmRequestEventHandler extends AbstractHttpRequestEventHandler
{
    /**
     * The PHP-FPM process.
     *
     * @var PhpFpmProcess
     */
    private $process;

    /**
     * Constructor.
     */
    public function __construct(PhpFpmProcess $process, string $rootDirectory)
    {
        parent::__construct($rootDirectory);

        $this->process = $process;
    }

    /**
     * {@inheritdoc}
     */
    protected function createLambdaEventResponse(HttpRequestEvent $event): HttpResponse
    {
        return new FastCgiHttpResponse(
            $this->process->handle(FastCgiRequest::createFromInvocationEvent($event, $this->getScriptFilePath($event)))
        );
    }

    /**
     * Get the path to script file to pass to PHP-FPM based on the Lambda invocation event.
     */
    abstract protected function getScriptFilePath(HttpRequestEvent $event): string;

    /**
     * {@inheritdoc}
     */
    protected function isStaticFile(string $path): bool
    {
        return parent::isStaticFile($path) && false === stripos($path, '.php');
    }
}
