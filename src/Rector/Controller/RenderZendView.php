<?php

declare(strict_types=1);

namespace Complex\Zend2RocketRector\Rector\Controller;

use PhpParser\Node\Identifier;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\PhpParser\Node\Manipulator\IdentifierManipulator;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Rector\Symfony\Bridge\NodeAnalyzer\ControllerMethodAnalyzer;


final class RenderZendView extends AbstractRector
{
    /**
     * @var ControllerMethodAnalyzer
     */
    private $controllerMethodAnalyzer;

    /**
     * @var IdentifierManipulator
     */
    private $identifierManipulator;

    public function __construct(
        ControllerMethodAnalyzer $controllerMethodAnalyzer,
        IdentifierManipulator $identifierManipulator
    ) {
        $this->controllerMethodAnalyzer = $controllerMethodAnalyzer;
        $this->identifierManipulator = $identifierManipulator;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Converts camel-case Action methods to snake-case', [
            new CodeSample(
                <<<'PHP'
class SomeController
{
    public function getArticleInfoAction()
    {
    }
}
PHP
                ,
                <<<'PHP'
class SomeController
{
    public function getArticleInfoAction()
    {
        return $this->currentZendViewResult();
    }
}
PHP
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function refactor(Node $node): ?Node
    {
        // identify if classmethod is an Actionmethod
        if (! $this->controllerMethodAnalyzer->isAction($node)) {
            return null;
        }

        // check if classmethod calls setNoRender
        $subnodes = $node->getSubNodeNames();
        if (! in_array('setNoRender', $subnodes)) {
            return null;
        }

        // serve Zendview

        return $node;
    }
    
}
