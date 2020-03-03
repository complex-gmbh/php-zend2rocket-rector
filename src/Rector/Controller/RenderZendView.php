<?php

declare(strict_types=1);

namespace Complex\Zend2RocketRector\Rector\Controller;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\PHPStan\Type\FullyQualifiedObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Naming\PropertyNaming;
use Rector\Core\PhpParser\Node\Manipulator\IdentifierManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\Symfony\Bridge\NodeAnalyzer\ControllerMethodAnalyzer;

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
        if (! $this->isAction($node)) {
            return $node;
        }


        // if it does not get called, return
        if (!$this->hasSetNoRenderMethodCall($node)) {
            return $node;
        }

        // refactor node
        $returnNode = $this->refactorZendViewRender($node);
        return $returnNode;
    }

    private function hasSetNoRenderMethodCall(ClassMethod $classMethod)
    {
        // check if setNoRender gets called
        $hasSetNoRenderMethodCall = $this->betterNodeFinder->findFirst(
            (array) $classMethod->stmts,
            function (Node $node): bool {
                if (! $node instanceof MethodCall) {
                    return false;
                }
                return $this->isName($node->name, 'setNoRender');
            }
        );
        return !$hasSetNoRenderMethodCall;
    }

    private function refactorZendViewRender(ClassMethod $node)
    {
        //fwrite(STDERR, 'Penis');exit();
        // build AST of 'return $this->currentZendViewResult();'
        $methodcall = $this->createMethodCall('this', 'currentZendViewResult', []);
        $return = new Return_($methodcall);

        $node->stmts = array_merge($node->stmts, [$return]);
        //$node->setAttribute(AttributeKey::PARENT_NODE, $return);

        return $node;
    }

    /**
     * Detect if is <some>Action() in Controller
     */
    public function isAction(Node $node): bool
    {
        if (! $node instanceof ClassMethod) {
            return false;
        }

        /*
        $parentClassName = (string) $node->getAttribute(AttributeKey::PARENT_CLASS_NAME);
        if (Strings::endsWith($parentClassName, 'Controller')) {
            return true;
        }
        */

        if (Strings::endsWith((string) $node->name, 'Action')) {
            return true;
        }

        return false;
    }

}
