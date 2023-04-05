<?php

declare(strict_types=1);

namespace Pest\Pestify\Rules;

/**
 * @internal
 */
final class TearDownAfterClassToAfterAll extends AbstractConvertLifecycleMethod
{
    protected function lifecycleMethodName(): string
    {
        return 'tearDownAfterClass';
    }

    protected function newName(): string
    {
        return 'afterAll';
    }
}
