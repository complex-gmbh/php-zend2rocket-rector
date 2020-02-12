<?php

declare(strict_types=1);

namespace Complex\Zend2RocketRector\Rector\Controller;

use Nette\Utils\Strings;
use PhpParser\Node\Identifier;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\PhpParser\Node\Manipulator\IdentifierManipulator;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Rector\Symfony\Bridge\NodeAnalyzer\ControllerMethodAnalyzer;


final class ActionCamelCaseToSnakeCase extends AbstractRector
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
    public function get_article_info()
    {
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

        // convert node (classmethod-name) to camelcase
        $node = $this->convertCamelToSnake($node);

        return $node;
    }

    /**
     * @return Node
     */
    private function convertCamelToSnake(Node $node): Node
    {
        // check if classmethod is an Action-method
        if (! $this->isAction($node)) {
            return $node;
        }

        // remove action suffix
        $this->identifierManipulator->removeSuffix($node, 'Action');

        // get name of clasmethod-node
        $NodeName = $this->getName($node->name);
        if ($NodeName === null) {
            return $node;
        }

        // convert camel- to snakecase
        $NodeName = strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $NodeName));
        $node->name = new Identifier($NodeName);

        return $node;
    }

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
