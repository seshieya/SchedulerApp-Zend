<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */



namespace Application\Controller;


use Application\Database\BaseTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Zend\Http\Request;



use Application\Database\ScheduleTable;
use Application\Database\ScheduleRowTable;
use Application\Database\JobTable;
use Application\Database\EmployeeTable;
use Application\Database\TradeTable;

use Application\Model\Job;
use Application\Model\Schedule;
use Application\Model\ScheduleRow;



class ScheduleController extends AbstractActionController
{
    const SECONDS_PER_DAY = 86400;
    const ROW_ID_START = 1;
    const VERSION_NUMBER_INCREMENT = 1;
    const DAY_OF_WK_SUNDAY = 0;
    const DAY_OF_WK_SATURDAY = 6;
    const DAY_OF_WK_MONDAY = 1;
    const INCREMENT_TWO_DAYS = 2;
    const INCREMENT_ONE_DAY = 1;
    const MIN_DAYS_NEEDED = 1;

    private $scheduleTable;
    private $scheduleRowTable;
    private $jobTable;
    private $employeeTable;
    //private $tradeTable;

    private $baseTable;

//    protected $post;
//    protected $startdate;

    public function __construct(ScheduleTable $scheduleTable, ScheduleRowTable $scheduleRowTable, JobTable $jobTable, EmployeeTable $employeeTable)
    {
        $this->scheduleTable = $scheduleTable;
        $this->scheduleRowTable = $scheduleRowTable;
        $this->jobTable = $jobTable;
        $this->employeeTable = $employeeTable;


        /*$this->post = $this->getRequest()->getPost();
        $this->startdate = $this->request->getPost('sc-startdate');*/

        //NEED TO PUT THESE SETTINGS INTO THE MODULE CONFIG LIKE WHAT GARY SHOWED.


        /*$this->scheduleTable = new ScheduleTable('scheduler', 'root', '');
        $this->scheduleRowTable = new ScheduleRowTable('scheduler', 'root', '');
        $this->jobTable = new JobTable('scheduler', 'root', '');
        $this->employeeTable = new EmployeeTable('scheduler', 'root', '');*/
        //$this->tradeTable = new TradeTable('scheduler', 'root', '');

    }

    public function draftAction()
    {
        $post = $this->getRequest()->getPost();
        $startdate = $this->request->getPost('sc-startdate');

        $data = [];

        /** @var Request $request */
        //$request = $this->getRequest();

        $jobAndRowsArray = $this->_separateJobAndRows($post);

        $generatedDates = $this->_autoGenerateDates($jobAndRowsArray['rowDayInDayOut'], $startdate, ScheduleController::ROW_ID_START);

        $daysNeeded = $this->_getDaysNeeded($post);

        $data = ['job' => $jobAndRowsArray['job'], 'rowOther' => $jobAndRowsArray['rowOther'], 'rowDayInDayOut' => $generatedDates, 'rowDaysNeeded' => $daysNeeded];

        return new ViewModel($data);
    }

    public function saveAction()
    {
        //consider moving saveAction to it's own controller. because putting save and draft in same controller seems like there is too much code
        $post = $this->getRequest()->getPost();

        $jobArray = [];
        $tradeArray = [];
        $scheduleArray = [];
        $scheduleRowArray = [];

        $data = [];

        $jobAndRowsArray = $this->_separateJobAndRows($post);

        $jobModel = new Job();
        $jobModel
            ->setJobNumber($jobAndRowsArray['job']['sc-job-number'])
            ->setEmpId(0)
            ->setAddress($jobAndRowsArray['job']['sc-job-address'])
            ->setAccess($jobAndRowsArray['job']['sc-job-access']);
        $jobArray = $jobModel->getArrayForDatabase();
        //insert into Job Table:
        $this->jobTable->insertJobData($jobArray);


        $scheduleModel = new Schedule();
        $scheduleModel
            ->setJobNumber($jobAndRowsArray['job']['sc-job-number'])
            ->setVersionNum(1);
        $scheduleArray = $scheduleModel->getArrayForDatabase();
        //insert into Schedule Table:
        $this->scheduleTable->insertScheduleData($scheduleArray);


        $scheduleRowModel = new ScheduleRow();
        $rowNum = ScheduleController::ROW_ID_START;
        while(true) {
            if(isset($jobAndRowsArray['rowOther']['sc-row' . $rowNum . '-type'])) {
                $scheduleRowModel->reset();

                $scheduleId = $this->scheduleTable->getLastScheduleId();

                $scheduleRowModel
                    ->setSchedId($scheduleId)
                    ->setTradeName($jobAndRowsArray['rowOther']['sc-row' . $rowNum . '-trade'])
                    ->setTradeEmail($jobAndRowsArray['rowOther']['sc-row' . $rowNum . '-trade-email'])
                    ->setTypeOfWork($jobAndRowsArray['rowOther']['sc-row' . $rowNum . '-type'])
                    ->setDayIn($jobAndRowsArray['rowOther']['sc-row' . $rowNum . '-dayIn'])
                    ->setDayOut($jobAndRowsArray['rowOther']['sc-row' . $rowNum . '-dayOut'])
                    ->setComments($jobAndRowsArray['rowOther']['sc-row' . $rowNum . '-comments']);

                $scheduleRowArray = $scheduleRowModel->getArrayForDatabase();
                //insert into Schedule_Row Table:
                $this->scheduleRowTable->insertScheduleRowData($scheduleRowArray);
                $rowNum++;
            }
            else {
                break;
            }

        }



/*        for($i=0; $i < sizeof($jobAndRowsArray['job']); $i++) {
            $jobModel->reset();

            $jobModel
                ->setJobNumber($jobAndRowsArray['job']['sc-job-number'])
                ->setEmpId(0)
                ->setAddress($jobAndRowsArray['job']['sc-job-address'])
                ->setAccess($jobAndRowsArray['job']['sc-job-access']);

            $jobArray = $jobModel->getArrayForDatabase();
        }*/

/*        foreach($jobAndRowsArray['job'] as $job) {
            $jobModel->reset();

            $jobModel
                ->setJobNumber($job['job']['sc-job-number'])
                ->setEmpId(0)
                ->setAddress($job['job']['sc-job-address'])
                ->setAccess($job['job']['sc-job-access']);

            $jobArray = $jobModel->getArrayForDatabase();
        }*/


        /*foreach($post as $key => $value) {
            if($key == 'sc-job-number') {
                $jobArray['job_id'] = $value;
                $scheduleArray['job_id'] = $value;
                //temp emp_id for testing:
                //$jobArray['emp_id'] = 1;
            }
            if($key == 'sc-job-address') {
                $jobArray['address'] = $value;
            }
            if($key == 'sc-job-access') {
                $jobArray['access'] = $value;
            }

        }

        $rowNum = 1;
        foreach($post as $key => $value) {
            if(preg_match('/^sc-row' . $rowNum . '/', $key)) {
                $scheduleRowArray[$key] = $value;
            }
        }*/

        //$scheduleArray['version_num'] = 1;

        //should use a function here to set the version number and modified date.....





        //$scheduleRowArray['sched_id'] = $this->scheduleTable->getLastScheduleId();



        $data['message'] = 'Schedule is saved';

        return new ViewModel($data);
    }


    public function datesAction()
    {
        //$daysIn = $this->getRequest()->getPost('initialDaysIn');
        //$daysOut = $this->getRequest()->getPost('initialDaysOut');


        $followingDaysNeeded = $this->getRequest()->getPost('followingDaysNeeded');

        $selectedStartDate = $this->getRequest()->getPost('selectedStartDate');

        $rowId = $this->getRequest()->getPost('rowId');

        $daysNeededDecoded = json_decode($followingDaysNeeded, true);

        $data = $this->_autoGenerateDates($daysNeededDecoded, $selectedStartDate, $rowId);



        //$data['finalDayIn'] = 'hi';
        //$data['finalDayOut'] = 'bye';

        //$data = 'hi';

        //echo $data;

        return new JsonModel($data);
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


    private function _generateVersionNumber($schedId) {
        $versionNumber = 0;
        $id = $this->scheduleTable->getVersionNumber($schedId);

        if($id == null) {
            $versionNumber = 1;
        }
        else {
            $versionNumber += ScheduleController::VERSION_NUMBER_INCREMENT;
        }

        return $versionNumber;
    }

    private function _generateModifiedDate() {

    }


    //separate job info and rows into their own array:
    private function _separateJobAndRows($post)
    {
        $job = [];
        $rowDayInDayOut = [];
        $rowOther = [];
        $rowNum = ScheduleController::ROW_ID_START;

        //$data['post'] = $post;

        //NEED TO ADD VALIDATION TO THE POST VALUES!!//

        foreach ($post as $key => $value) {
            if(preg_match('/^sc-row([0-9]|[0-9]{2})-days$/', $key)) {
                $rowDayInDayOut[$key] = $value;
            }
            else if (strpos($key, 'sc-row' . $rowNum) !== false) {
                $rowOther[$key] = $value;
            }
            else if (strpos($key, 'sc-row' . ($rowNum + 1)) !== false) {
                $rowOther[$key] = $value;
                $rowNum++;
            }
            else {
                $job[$key] = $value;
            }

            /*
            if (strpos($key, 'sc-row' . $rowNum) !== false) {
                $row[$key] = $value;

                // if(count($row[$rowNum]) === 6) {
                //     $rowNum++;
                // }
            }
            else if (strpos($key, 'sc-row' . ($rowNum + 1)) !== false) {
                $row[$key] = $value;
                $rowNum++;
            }
            else {
                $job[$key] = $value;
            }
            */
        }

        return ['job' => $job, 'rowOther' => $rowOther, 'rowDayInDayOut' => $rowDayInDayOut];
//            $data['job'] = $job;
//            $data['row'] = $row;
        //or can do: $data =['job' => $job, 'row' => $row];
    }

    //generate dates from days needed:
    private function _autoGenerateDates($daysNeededArray, $startDate, $rowIdStart) {
        $startDateInSeconds = $this->_getStartDateInSeconds($startDate);
        $dayOfWeek = $this->_getStartDateAsDayOfWeekNumber($startDate);
        $daysIncremented = 0;
        $dayInOutArray = [];
        $rowNum = $rowIdStart;

        foreach($daysNeededArray as $key => $daysNeeded) {
            for($i = 0; $i < $daysNeeded; $i++) {
                if($dayOfWeek == ScheduleController::DAY_OF_WK_SATURDAY) {
                    $dayOfWeek = ScheduleController::DAY_OF_WK_MONDAY;
                    $daysIncremented += ScheduleController::INCREMENT_TWO_DAYS;
                }
                else if($dayOfWeek == ScheduleController::DAY_OF_WK_SUNDAY) {
                    $dayOfWeek = ScheduleController::DAY_OF_WK_MONDAY;
                    $daysIncremented += ScheduleController::INCREMENT_ONE_DAY;
                }

                //first day in:
                if($i == 0) {
                    $seconds = $this->_calculateSeconds($daysIncremented) + $startDateInSeconds;
                    $dayInOutArray['sc-row' . $rowNum . '-dayIn'] = $this->_convertToDateString($seconds);

                    //If $daysNeeded is equal to 1 day, then Day Out would be the same as Day In:
                    if($daysNeeded == ScheduleController::MIN_DAYS_NEEDED) {
                        $dayInOutArray['sc-row' . $rowNum . '-dayOut'] = $dayInOutArray['sc-row' . $rowNum . '-dayIn'];
                    }

                }
                //last day out:
                else if($i == ($daysNeeded - 1)) {
                    $seconds = $this->_calculateSeconds($daysIncremented) + $startDateInSeconds;
                    $dayInOutArray['sc-row' . $rowNum . '-dayOut'] = $this->_convertToDateString($seconds);
                }

                $dayOfWeek++;
                $daysIncremented++;
            }
            $rowNum++;
        }

        return $dayInOutArray;
    }

    private function _getStartDateInSeconds($startdate) {
        $startdateInSeconds = strtotime($startdate);
        return $startdateInSeconds;
    }

    private function _getStartDateAsDayOfWeekNumber($startdate) {
        $startdateInSeconds = $this->_getStartDateInSeconds($startdate);
        $dayOfWeekAsNumber = date('w', $startdateInSeconds);

        return $dayOfWeekAsNumber;
    }

    /*private function _calculateDayInDayOutAsNumbers($array) {
        $rowNum = ScheduleController::ROW_ID_START;

        $dayInOutArray = [];
        $dayIn = 0;
        $dayOut = 0;

        //CODE TO CALCULATE DAY IN AND DAY OUT IN INTEGERS and push to an array
        foreach($array as $key => $daysNeeded) {
            //first row calculation:
            if(preg_match('/^sc-row1-days$/', $key)) {
                $dayIn = ScheduleController::MIN_DAY_OF_WEEK; //first day of work
                $dayOut = $dayIn + ($daysNeeded - 1);

                /*if($dayOut > ScheduleController::MAX_DAY_OF_WEEK) {
                    $dayOut = $dayOut%ScheduleController::MAX_DAY_OF_WEEK;
                }

//                echo 'row ' . $rowNum . ' day in: ' . $dayIn . '<br>';
//                echo 'row 1 day out: ' . $dayOut . '<br>';

//        $dayInArray[$rowNum] = $dayIn;
//        $dayOutArray[$rowNum] = $dayOut;

                $dayInOutArray[$rowNum . 'dayIn'] = $dayIn;
                $dayInOutArray[$rowNum . 'dayOut'] = $dayOut;

                $rowNum++;
            }
            //all other rows calculation:
            else if(preg_match('/^sc-row([0-9]|[0-9]{2})-days$/', $key)) {
                $dayIn = $dayOut + 1;
                $dayOut = $dayIn + ($daysNeeded - 1);

                /*if($dayIn > ScheduleController::MAX_DAY_OF_WEEK) {
                    $dayIn = $dayIn%ScheduleController::MAX_DAY_OF_WEEK;
                }
                else if($dayOut > ScheduleController::MAX_DAY_OF_WEEK) {
                    $dayOut = $dayOut%ScheduleController::MAX_DAY_OF_WEEK;
                }

//                echo 'row ' . $rowNum . ' day in: ' . $dayIn . '<br>';
//                echo 'row ' . $rowNum . ' day out: ' . $dayOut . '<br>';

//        $dayInArray[$rowNum] = $dayIn;
//        $dayOutArray[$rowNum] = $dayOut;

                $dayInOutArray[$rowNum . 'dayIn'] = $dayIn;
                $dayInOutArray[$rowNum . 'dayOut'] = $dayOut;

                $rowNum++;

            }
        }

        return $dayInOutArray;

    }*/

    /*private function _autoGenerateDatesFromNumbers($daysArray, $startdateInSeconds) {

        $dayIncrement = 0;
        $startDateDayOfWeek = date('w', $startdateInSeconds);

//    $startdateInSeconds = strtotime($_POST['sc-startdate']);

        foreach($daysArray as $key => $day) {

            /*$addStartDay = $day + $startDateDayOfWeek;

            $timesDivisible = floor($addStartDay/ScheduleController::MAX_DAY_OF_WEEK);
            $calculatedDay = ($timesDivisible * 2);

            if($key == '1dayIn'){
                $finalDay = '';
            }
            else {
                $newDayInSeconds = $this->_calculateSeconds($day) + $this->_calculateSeconds($newDay) + $startdateInSeconds;
            }



            $newDayInSeconds = $this->_calculateSeconds($day) + $startdateInSeconds + $dayIncrement;
            $dayOfWeek = date('w', $newDayInSeconds);

            //echo 'day of week' . $dayOfWeek .'<br>';



            if($dayOfWeek == ScheduleController::MAX_DAY_OF_WEEK) {
                $dayIncrement += (ScheduleController::SECONDS_PER_DAY*2);
                $newDate = $this->_calculateSeconds($day) + $startdateInSeconds + $dayIncrement;
            }
            else if($dayOfWeek == ScheduleController::MIN_DAY_OF_WEEK) {
                $dayIncrement += ScheduleController::SECONDS_PER_DAY;
                $newDate = $this->_calculateSeconds($day) + $startdateInSeconds + $dayIncrement;
            }



            $dateString = $this->_convertToDateString($newDayInSeconds);
            $daysArray[$key] = $dateString;

//        var_dump($seconds);
//        var_dump($dateString);

        }
        return $daysArray;
    }*/

    private function _calculateSeconds($numOfDays) {
        // days in seconds = 60 seconds * 60 minutes * 24 hours = 86400 seconds
        $secondsData = ScheduleController::SECONDS_PER_DAY * $numOfDays;
        return $secondsData;
    }

    private function _convertToDateString($time) {
        return date('D M j', $time);
    }

    private function _getDaysNeeded($post) {
        $daysNeededArray = [];
        foreach($post as $key => $value) {
            if(preg_match('/^sc-row([0-9]|[0-9]{2})-days$/', $key)) {
                $daysNeededArray[$key] = $value;
            }
        }

        return $daysNeededArray;
    }




}
