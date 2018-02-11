<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */



namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Zend\Http\Request;


use Application\Database\ScheduleTable;
use Application\Database\ScheduleRowTable;
use Application\Database\JobTable;
use Application\Database\EmployeeTable;

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
    private $sessionManager;
    private $sessionContainer;


    public function __construct(ScheduleTable $scheduleTable, ScheduleRowTable $scheduleRowTable, JobTable $jobTable, EmployeeTable $employeeTable, $sessionManager, $sessionContainer)
    {
        $this->scheduleTable = $scheduleTable;
        $this->scheduleRowTable = $scheduleRowTable;
        $this->jobTable = $jobTable;
        $this->employeeTable = $employeeTable;

        $this->sessionManager = $sessionManager;
        $this->sessionContainer = $sessionContainer;

    }


    public function createAction()
    {

        $sessionData = [];
        $sessionData['coordinatorName'] = $this->sessionContainer->coordinatorName;
        $sessionData['coordinatorEmail'] = $this->sessionContainer->coordinatorEmail;
        $sessionData['coordinatorPhone'] = $this->sessionContainer->coordinatorPhone;


        return new ViewModel($sessionData);
    }

    public function draftAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        //redirect authenticated users to the create page if no data posted:
        if(!$request->isPost()) {
            $this->redirect()->toRoute('create');
        }

        $post = $request->getPost();

        $startdate = $this->request->getPost('sc-startdate');

        $data = [];

        $jobAndRowsArray = $this->_separateJobAndRows($post);

        $generatedDates = $this->_autoGenerateDates($jobAndRowsArray['rowDayInDayOut'], $startdate, ScheduleController::ROW_ID_START);

        $daysNeeded = $this->_getDaysNeeded($post);

        $data = ['job' => $jobAndRowsArray['job'], 'rowOther' => $jobAndRowsArray['rowOther'], 'rowDayInDayOut' => $generatedDates, 'rowDaysNeeded' => $daysNeeded];

        return new ViewModel($data);
    }

    public function saveAction()
    {

        $post = $this->getRequest()->getPost();

        $jobArray = [];
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


        $data['message'] = 'Schedule is saved';

        return new ViewModel($data);
    }


    public function datesAction()
    {

        $followingDaysNeeded = $this->getRequest()->getPost('followingDaysNeeded');

        $selectedStartDate = $this->getRequest()->getPost('selectedStartDate');

        $rowId = $this->getRequest()->getPost('rowId');

        $daysNeededDecoded = json_decode($followingDaysNeeded, true);

        $data = $this->_autoGenerateDates($daysNeededDecoded, $selectedStartDate, $rowId);


        //$data['finalDayIn'] = 'hi';
        //$data['finalDayOut'] = 'bye';

        return new JsonModel($data);
    }

    public function previewAction()
    {
        $jobNumber = trim($this->getRequest()->getQuery('pvw-job-number'));

        $scheduleInfo = false;

        //check if job number exists in the database if the job number is not null or an emtpy string.
        //returns false to $scheduleInfo if job number doesn't exist:
        if($jobNumber != null && $jobNumber != '') {
            $scheduleInfo = $this->scheduleTable->getScheduleFromJobNumber($jobNumber);
        }

        $schedData = [];
        $schedData['scheduleExists'] = false;

        if ($scheduleInfo !== false) {

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
            foreach ($schedRows as $rows) {
                $scheduleRowModel->reset();

                $scheduleRowModel
                    ->setTradeName($rows['trade_name'])
                    ->setTypeOfWork($rows['type_of_work'])
                    ->setDayIn($rows['day_in'])
                    ->setDayOut($rows['day_out'])
                    ->setComments($rows['comments']);
                $schedData['rows'][] = $scheduleRowModel->getArrayForView();
            }
            $schedData['scheduleExists'] = true;
        }
        else if($scheduleInfo === false && $jobNumber != null && $jobNumber != '') {
            $schedData['message'] = 'Job number or schedule not found.';
        }

        return new ViewModel($schedData);
    }


    //todo Angela implement schedule version generation. need to complete and fix the below code
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

    //todo Angela complete _generateModifiedDate function for the schedule
    private function _generateModifiedDate() {

    }


    //separate job info and rows into their own array:
    private function _separateJobAndRows($post)
    {
        $job = [];
        $rowDayInDayOut = [];
        $rowOther = [];
        $rowNum = ScheduleController::ROW_ID_START;


        //todo Angela NEED TO ADD VALIDATION TO THE POST VALUES!!//

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



                //first day in:
                if($i == 0) {
                    $seconds = $this->_calculateSeconds($daysIncremented) + $startDateInSeconds;
                    $dayInOutArray['sc-row' . $rowNum . '-dayIn'] = $this->_convertToDateString($seconds);


                    //If $daysNeeded is equal to 1 day, then Day Out would be the same as Day In:
                    if ($daysNeeded == ScheduleController::MIN_DAYS_NEEDED) {
                        $dayInOutArray['sc-row' . $rowNum . '-dayOut'] = $dayInOutArray['sc-row' . $rowNum . '-dayIn'];
                    }
                    //If $daysNeeded is more than 1 day and falls on a Saturday:
                    else if ($daysNeeded != ScheduleController::MIN_DAYS_NEEDED && $dayOfWeek == ScheduleController::DAY_OF_WK_SATURDAY) {
                        $daysIncremented += ScheduleController::INCREMENT_ONE_DAY;
                    }
                }
                //last day out:
                else if($i == ($daysNeeded - 1)) {
                    $seconds = $this->_calculateSeconds($daysIncremented) + $startDateInSeconds;
                    $dayInOutArray['sc-row' . $rowNum . '-dayOut'] = $this->_convertToDateString($seconds);
                }
                else {
                    if($dayOfWeek == ScheduleController::DAY_OF_WK_SATURDAY) {
                        $dayOfWeek = ScheduleController::DAY_OF_WK_MONDAY;
                        $daysIncremented += ScheduleController::INCREMENT_TWO_DAYS;
                    }
                    else if($dayOfWeek == ScheduleController::DAY_OF_WK_SUNDAY) {
                        $dayOfWeek = ScheduleController::DAY_OF_WK_MONDAY;
                        $daysIncremented += ScheduleController::INCREMENT_ONE_DAY;
                    }
                }






                $dayOfWeek++;
                $daysIncremented++;
            }
            $rowNum++;
        }

        return $dayInOutArray;
    }




    //generate dates from days needed:
    private function _autoGenerateDatesVer2($daysNeededArray, $startDate, $rowIdStart) {
        $startDateInSeconds = $this->_getStartDateInSeconds($startDate);
        $dayOfWeek = $this->_getStartDateAsDayOfWeekNumber($startDate);
        $daysIncremented = 0;
        $dayInOutArray = [];
        $rowNum = $rowIdStart;

        foreach($daysNeededArray as $key => $daysNeeded) {
            for($i = 0; $i < $daysNeeded; $i++) {
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


                if($dayOfWeek == ScheduleController::DAY_OF_WK_SATURDAY) {
                    $dayOfWeek = ScheduleController::DAY_OF_WK_MONDAY;
                    $daysIncremented += ScheduleController::INCREMENT_TWO_DAYS;
                }
                else if($dayOfWeek == ScheduleController::DAY_OF_WK_SUNDAY) {
                    $dayOfWeek = ScheduleController::DAY_OF_WK_MONDAY;
                    $daysIncremented += ScheduleController::INCREMENT_ONE_DAY;
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
