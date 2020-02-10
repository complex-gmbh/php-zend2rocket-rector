<?php

declare(strict_types=1);

namespace Complex\Zend2RocketRector\Rector\Controller;

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
        $this->convertCamelToSnake($node);

        return $node;
    }

    /**
     * @return string
     */
    private function convertCamelToSnake($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}