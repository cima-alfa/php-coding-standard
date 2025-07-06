<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Action;

use PhpCsFixer\Error\ErrorsManager;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class Fix
{
    private ErrorsManager $errorsManager;
    private EventDispatcher $eventDispatcher;

    public function __construct()
    {
        $this->errorsManager = new ErrorsManager;
        $this->eventDispatcher = new EventDispatcher;
    }
}