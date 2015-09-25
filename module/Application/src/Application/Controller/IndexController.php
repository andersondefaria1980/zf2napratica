<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbSelectAdapter;

/**
 * Controlador que gerencia os posts
 * 
 * @category Application
 * @package Controller
 * @author  Elton Minetto <eminetto@coderockr.com>
 */
class IndexController extends ActionController
{
    /**
     * Mostra os posts cadastrados
     * @return void
     */
    public function indexAction()
    {
        
        $post = $this->getTable('Application\Model\Post');
        $sql = $post->getSql();
        $select = $sql->select();

        $paginatorAdapter = new PaginatorDbSelectAdapter($select, $sql);
        $paginator = new Paginator($paginatorAdapter);
        
        $cache = $this->getServiceLocator()->get('Cache');
        $cache = $this->getService('Cache');
        $paginator->setCache($cache);
        
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);

        return new ViewModel(array(
            'posts' => $paginator
        ));
    }

    public function postAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0) {
            throw new \Exception("Código obrigatório");
        }
        $post = $this->getTable('Application\Model\Post')
                     ->get($id)
                     ->toArray();
        $comments = $this->getTable('Application\Model\Comment')
                         ->fetchAll(null, 'post_id = ' . $post['id'])
                         ->toArray();
        $post['comments'] =  $comments;

        return new ViewModel(array(
            'post' => $post
        ));
    }
}