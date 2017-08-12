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
        //this outputs to the browser. check how to get it to output as a download.
        $mpdf = new mPDF();
        $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $mpdf->Output();

        return new ViewModel();
    }

    public function previewAction()
    {
        $schedId = $this->scheduleTable->getLastScheduleId();
        $schedRows = $this->scheduleRowTable->getScheduleRows(29);

        $rowData = [];
        $rowNum = PdfController::ROW_ID_START;

        $scheduleRowModel = new ScheduleRow();
        foreach($schedRows as $rows) {
            $scheduleRowModel->reset();

            $scheduleRowModel
                ->setTradeName($rows['trade_name'])
                ->setTypeOfWork($rows['type_of_work'])
                ->setDayIn($rows['day_in'])
                ->setDayOut($rows['day_out'])
                ->setComments($rows['comments']);
            $rowData['rows'][] = $scheduleRowModel->getArrayForView();
            $rowNum++;
        }

        return new ViewModel($rowData);
    }



}
