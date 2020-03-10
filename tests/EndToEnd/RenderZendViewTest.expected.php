<?php


class ProductController {

    public function TestFunction()
    {
        // do something
        $this->setNoRender();
        // do something
        echo "124";
    }

    public function autocompletedAction()
    {
        // do something
        $this->setNoRender();
        // do something
        echo "124";
    }

    public function getArticleInfoFunction()
    {
        echo "124";
        // do something
    }

    public function getArticleDetailsAction()
    {
        // do something
        echo "124";
        return $this->currentZendViewResult();
    }

    public function multipleImplicitReturnsAction()
    {
        for ($i = 0; $i < 5; $i++) {
            if (i > 2) {
                return false;
            }
            return true;
        }
        return true;
        return $this->currentZendViewResult();
    }

    public function autocompleteAction()
    {
        $this->setNoRender(true);
        $this->disableLayout();
        $this->setNoRender(true);

        $searchTerm = $this->getParam('term');

        $artMapper = new Application_Model_Mapper_Article();
        $all = $artMapper->fetchAllNamesForAutocomplete($searchTerm);

        $articleNames = array();

        foreach ($all as $article) {
            $articleNames[] = array(
                'id'    => $article->getArtid(),
                'label' => $article->getName());
        }

        $this->json($articleNames);
    }
}

?>
