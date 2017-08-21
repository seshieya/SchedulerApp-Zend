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

use Application\Database\ScheduleTable;
use Application\Database\ScheduleRowTable;
use Application\Database\JobTable;

use Application\Model\Job;
use Application\Model\Schedule;
use Application\Model\ScheduleRow;



class PdfController extends AbstractActionController
{

    private $scheduleTable;
    private $scheduleRowTable;
    private $jobTable;

    const ROW_ID_START = 1;

    public function __construct()
    {

        //NEED TO PUT THESE SETTINGS INTO THE MODULE CONFIG LIKE WHAT GARY SHOWED.

        $this->scheduleTable = new ScheduleTable('scheduler', 'root', '');
        $this->scheduleRowTable = new ScheduleRowTable('scheduler', 'root', '');
        $this->jobTable = new JobTable('scheduler', 'root', '');
    }

    public function downloadAction()
    {

        $html = $this->getRequest()->getPost('pvw-pdf');

        //fix the line breaks for html output:
        /*$searchOrder = ["\r\n", "\n", "\r"];
        $replace = '';
        $html = str_replace($searchOrder, $replace, $post);*/

        //$html = file_get_contents('module/Application/view/application/pdf/preview.phtml');


        //this outputs to the browser. check how to get it to output as a download.
        $mpdf = new mPDF();
        $stylesheet = file_get_contents('public/css/pvw-pdf.css');

        $mpdf->WriteHtml($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);
        $mpdf->Output('schedule.pdf', 'D');

        return new ViewModel();
    }

    public function previewAction()
    {
        //need to figure out how to get a custom job number here! maybe add a if there is POST request statement?
        $scheduleInfo = $this->scheduleTable->getScheduleInfo(107619000);

        $schedData = [];

        $scheduleModel = new Schedule();
        $scheduleModel
            ->setJobNumber($scheduleInfo['job_id'])
            ->setVersionNum($scheduleInfo['version_num'])
            ->setModifiedDate($scheduleInfo['modified_date'])
            ->setJobAddress($scheduleInfo['address'])
            ->setJobAccess($scheduleInfo['access']);


        $schedData['schedInfo'] = $scheduleModel->getArrayForView();


        $schedId = $scheduleInfo['sched_id'];
        $schedRows = $this->scheduleRowTable->getScheduleRows($schedId);

        $scheduleRowModel = new ScheduleRow();
        foreach($schedRows as $rows) {
            $scheduleRowModel->reset();

            $scheduleRowModel
                ->setTradeName($rows['trade_name'])
                ->setTypeOfWork($rows['type_of_work'])
                ->setDayIn($rows['day_in'])
                ->setDayOut($rows['day_out'])
                ->setComments($rows['comments']);
            $schedData['rows'][] = $scheduleRowModel->getArrayForView();
        }

        /*$sessionManager = new SessionManager();
        $sessionContainer = new Container('schedulerContainer', $sessionManager);
        $sessionContainer->schedData = $schedData;*/

        return new ViewModel($schedData);
    }



}
