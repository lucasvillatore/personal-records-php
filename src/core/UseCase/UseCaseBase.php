<?php

namespace App\Core\UseCase;

abstract class UseCaseBase {
    public abstract function execute($data): mixed;
}