<?php

namespace Complex\Zend2Rocket\Tests\Rector\Controller\Fixture;

class ProductController {
    public function TestFunction()
    {
        // do something
        $this->setNoRender();
        // do something
    }

    public function TestAction()
    {
        // do something
        $this->setNoRender();
        // do something
    }

    public function getArticleInfoFunction()
    {
        // do something
    }

    public function getArticleInfoAction()
    {
        // do something
        echo "123";
    }
}

?>
-----
<?php

namespace Complex\Zend2Rocket\Tests\Rector\Controller\Fixture;

class ProductController {
    public function TestFunction()
    {
        // do something
        $this->setNoRender();
        // do something
    }

    public function TestAction()
    {
        // do something
        $this->setNoRender();
        // do something
    }

    public function getArticleInfoFunction()
    {
        // do something
    }

    public function getArticleInfoAction()
    {
        // do something
        echo "123";
        return $this->currentZendViewResult();
    }
}

?>