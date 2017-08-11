<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;
use Zend\Session\SessionManager;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $sessionManager = new SessionManager();
        $sessionContainer = new Container('schedulerContainer', $sessionManager);

        $sessionData = [];
        $sessionData['username'] = $sessionContainer->username;

        /*$post = $this->getRequest()->getPost();
        $job = [];
        $rowOther = [];
        $rowDayInDayOut = [];
        $data = [];

        if(isset($post)) {
            $job = json_decode($post['job'], true);
            $rowOther = json_decode($post['rowOther'], true);
            $rowDayInDayOut = json_decode($post['rowDayInDayOut'], true);
            $data = ['job' => $job, 'rowOther' => $rowOther, 'rowDayInDayOut' => $rowDayInDayOut];
        }*/

        /*
        $job = $this->getRequest()->getPost('job');
        $rowOther = $this->getRequest()->getPost('rowOther');
        $rowDayInDayOut = $this->getRequest()->getPost('rowDayInDayOut');
        $data = [];

        if (isset($job) && isset($rowOther) && isset($rowDayInDayOut)) {
            $jobDecoded = json_decode($job);
            $rowOtherDecoded = json_decode($rowOther);
            $rowDayInDayOutDecoded = json_decode($rowDayInDayOut);
            $data = ['job' => $jobDecoded, 'rowOther' => $rowOtherDecoded, 'rowDayInDayOut' => $rowDayInDayOutDecoded];
        }*/

        return new ViewModel($sessionData);
    }
}
