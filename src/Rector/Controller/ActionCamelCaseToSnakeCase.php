<?php

declare(strict_types=1);

namespace Complex\Zend2RocketRector\Rector\Controller;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class ActionCamelCaseToSnakeCase extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        // what node types we look for?
        // pick any node from https://github.com/rectorphp/rector/blob/master/docs/NodesOverview.md
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node - we can add "MethodCall" type here, because only this node is in "getNodeTypes()"
     */
    public function refactor(Node $node): ?Node
    {
        // we only care about "set*" method names
        if (! $this->isName($node->name, 'set*')) {
            // return null to skip it
            return null;
        }

        $methodCallName = $this->getName($node);
        $newMethodCallName = Strings::replace($methodCallName, '#^set#', 'change');

        $node->name = new Identifier($newMethodCallName);

        // return $node if you modified it
        return $node;
    }

    /**
     * From this method documentation is generated.
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Change method calls from set* to change*.', [
                new CodeSample(
                // code before
                    '$user->setPassword("123456");',
                    // code after
                    '$user->changePassword("123456");'
                ),
            ]
        );
    }
}