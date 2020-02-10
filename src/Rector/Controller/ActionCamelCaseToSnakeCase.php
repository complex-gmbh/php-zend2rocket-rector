<?php

declare(strict_types=1);

namespace Complex\Zend2RocketRector\Rector\Controller;

use _HumbugBox3ab8cff0fda0\PhpParser\Node\Identifier;
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
    public function get_article_infoAction()
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
        echo "refactor this shit";
        sprintf("refactor this shit");
        $node = $this->convertCamelToSnake($node);

        return $node;
    }

    /**
     * @return string
     */
    private function convertCamelToSnake(Node $node): Node
    {
        $NodeName = $this->getName($node->name);
        sprintf("refactor this shit: %d", $NodeName);
        if ($NodeName === null) {
            return $node;
        }
        $NodeName = strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $NodeName));

        return $node->name = new Identifier($NodeName);
    }
}