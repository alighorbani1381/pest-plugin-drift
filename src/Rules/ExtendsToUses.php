<?php

declare(strict_types=1);

namespace Pest\Pestify\Rules;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitorAbstract;

/**
 * @internal
 */
final class ExtendsToUses extends NodeVisitorAbstract
{
    private const EXCLUDED_TEST_CASE = [
        'PHPUnit\Framework\TestCase',
        'Tests\TestCase',
    ];

    /**
     * {@inheritDoc}
     */
    public function enterNode(Node $node)
    {
        if (! $node instanceof Class_) {
            return null;
        }
        if (! $node->extends instanceof Name) {
            return null;
        }

        /** @var Name $resolvedName */
        $resolvedName = $node->extends->getAttribute('resolvedName');

        if (in_array($resolvedName->toString(), self::EXCLUDED_TEST_CASE, true)) {
            return null;
        }

        $resolvedName->setAttributes([]);

        $usesStmt = new Expression(
            new FuncCall(
                new Name('uses'),
                [
                    new Arg(new ClassConstFetch($resolvedName, 'class')),
                ]
            )
        );

        array_unshift($node->stmts, $usesStmt);

        return $node;
    }
}
