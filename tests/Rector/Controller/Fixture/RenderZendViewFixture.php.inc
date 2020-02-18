<?php

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
    }
}

?>
-----
<?php

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
        return $this->currentZendViewResult();
    }
}

?>