<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */



namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Http\Request;

use mPDF;



class PdfController extends AbstractActionController
{



    const ROW_ID_START = 1;


    public function downloadAction()
    {

        $trade = $this->getRequest()->getPost('pvw-pdf-input');
        $withoutTrade = $this->getRequest()->getPost('pvw-pdf-wotrades-input');

        if($trade !== null) {
            $html = $trade;
        }
        else {
            $html = $withoutTrade;
        }

        //$html = $this->getRequest()->getPost('pvw-pdf-input');

        //fix the line breaks for html output:
        /*$searchOrder = ["\r\n", "\n", "\r"];
        $replace = '';
        $html = str_replace($searchOrder, $replace, $post);*/


        //this outputs to the browser. check how to get it to output as a download.
        $mpdf = new mPDF();
        $stylesheet = file_get_contents('public/css/pvw-pdf.css');

        $mpdf->WriteHtml($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $mpdf->Output('schedule.pdf', 'D');

        return new ViewModel();
    }




}
