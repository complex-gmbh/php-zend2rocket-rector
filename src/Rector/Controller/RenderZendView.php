<?php

declare(strict_types=1);

namespace Complex\Zend2RocketRector\Rector\Controller;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\RectorDefinition\CodeSample;
use Rector\Symfony\Bridge\NodeAnalyzer\ControllerMethodAnalyzer;
use Rector\PHPStan\Type\FullyQualifiedObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Naming\PropertyNaming;
use Rector\Core\PhpParser\Node\Manipulator\IdentifierManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\RectorDefinition;

final class RenderZendView extends AbstractRector
{
    /**
     * @var PropertyNaming
     */
    private $propertyNaming;

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
        IdentifierManipulator $identifierManipulator,
        PropertyNaming $propertyNaming
    ) {
        $this->controllerMethodAnalyzer = $controllerMethodAnalyzer;
        $this->identifierManipulator = $identifierManipulator;
        $this->propertyNaming = $propertyNaming;
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
            return $node;
        }

        //var_dump($node);

        // check if classmethod calls setNoRender
        $subnodes = $node->getSubNodeNames();
        if ( in_array('setNoRender', $subnodes)) {
            return $node;
        }

        // serve Zendview
        return $this->refactorZendViewRender($node);
    }

    private function refactorZendViewRender(Node $node)
    {
        // build AST of 'return $this->currentZendViewResult();'
        $methodcall = $this->createMethodCall('this', 'currentZendViewResult', []);
        $return = new Return_($methodcall);
        $node->setAttribute(AttributeKey::PARENT_NODE, $return);

        return $return;
    }

}
