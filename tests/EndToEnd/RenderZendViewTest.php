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
        echo "124";
        // do something
    }

    /* -> rector issue: https://github.com/rectorphp/rector/issues/2994 / https://github.com/rectorphp/rector/issues/2991
    public function getArticleBackDetailsAction()
    {
        // do something
    }
    */

    public function multipleImplicitReturnsAction()
    {
        for ($i = 0; $i < 5; $i++) {
            if (i > 2) {
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * count artfarbenids in aset of articles (used in basket and stock)
     *
     * @param unknown $array
     * @return multitype:number
     */
    /* -> rector issue: https://github.com/rectorphp/rector/issues/2989
    protected function _getCountByArtfarbeid($array)
    {
        $count = array();
        foreach ($array as $item) {
            if (array_key_exists($item->getArtfarbeid(), $count)) {
                $count[$item->getArtfarbeid()] ++;
            } else {
                $count[$item->getArtfarbeid()] = 1;
            }
        }
        return $count;
    }
    */

    public function getArticleInfoAction()
    {
        echo "124";
        // do something
        //bestaende
        $vetechs = array(
            //Transferdruck
            4 => array(
                'description' => $translations[$this->t_getLanguage()->getKuerzel()][4],
                'image' => '/img/vearten/ink.svg'
            ),

            //Stick
            5 => array(
                'description' => $translations[$this->t_getLanguage()->getKuerzel()][5],
                'image' => '/img/vearten/bestickbar.svg'
            )
        );
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
