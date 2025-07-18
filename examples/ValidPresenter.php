<?php

// 8.0

namespace Nette\CodingStandard\Examples;

use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;
use Nette\DI\Attributes\Inject;

class ValidPresenter extends Presenter
{
    #[Inject]
    public Explorer $database;
}
