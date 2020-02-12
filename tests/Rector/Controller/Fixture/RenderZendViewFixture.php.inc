<?php

class ArticleController {
    public function TestFunction()
    {
        // do something
        $this->setNoRender();
        // do something
    }

    public function getArticleInformationAction() {
        // do something
    }
}

?>
-----
<?php

class SomeController
{
    public function getProductInfoAction()
    {
        // do something
        $this->setNoRender();
        // do something
    }

    public function getArticleInfoAction()
    {
        // do something
        return $this->currentZendViewResult();
    }
}

?>
