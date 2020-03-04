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
use Symfony\Component\Console\Style\SymfonyStyle;

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

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(
        SymfonyStyle $symfonyStyle,
        ControllerMethodAnalyzer $controllerMethodAnalyzer,
        IdentifierManipulator $identifierManipulator,
        PropertyNaming $propertyNaming
    ) {
        $this->symfonyStyle = $symfonyStyle;
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

        // check if Method is empty
        if ($this->classMethodIsEmpty($node)) {
            return $node;
        }

        // detect implicit Returns
        $this->detectReturnStatements($node);

        // refactor node
        $returnNode = $this->refactorZendViewRender($node);
        return $returnNode;
    }

    private function classMethodIsEmpty(ClassMethod $classMethod) {
        if (count($classMethod->stmts) == 0) {
            $classNode = $this->$currentClassMethod->getAttribute(AttributeKey::CLASS_NODE);
            $this->symfonyStyle->caution('empty classMethod "'.$classMethod->name.'" in "'. $classNode->name .'" on line '. $classMethod->getLine());
            return true;
        }
        return false;
    }

    private $currentClassMethod;
    private function detectReturnStatements(ClassMethod $classMethod)
    {
        $this->$currentClassMethod = $classMethod;
        $this->betterNodeFinder->find(
            (array) $classMethod->stmts,
            function (Node $node) {
                if ($node instanceof Return_) {
                    $classNode = $this->$currentClassMethod->getAttribute(AttributeKey::CLASS_NODE);
                    $this->symfonyStyle->warning('Return-statement was found inside an action method in Class "'. $classNode->name .'" on line '. $node->getLine());
                }
            }
        );
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
        // build AST of 'return $this->currentZendViewResult();'
        $methodcall = $this->createMethodCall('this', 'currentZendViewResult', []);
        $return = new Return_($methodcall);

        // add newly created node to classmethod
        $node->stmts = array_merge($node->stmts, [$return]);

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
