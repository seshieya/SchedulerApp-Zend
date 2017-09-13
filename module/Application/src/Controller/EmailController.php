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

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use Application\Database\ScheduleTable;
use Application\Database\ScheduleRowTable;
use Application\Database\JobTable;

use Application\Model\ScheduleRow;



class EmailController extends AbstractActionController
{

    private $scheduleTable;
    private $scheduleRowTable;
    private $jobTable;

    private $emailInfo;

    const ROW_ID_START = 1;

    public function __construct(ScheduleTable $scheduleTable, ScheduleRowTable $scheduleRowTable, JobTable $jobTable, $emailInfo)
    {
        $this->scheduleTable = $scheduleTable;
        $this->scheduleRowTable = $scheduleRowTable;
        $this->jobTable = $jobTable;
        $this->emailInfo = $emailInfo;

    }

    public function draftAction()
    {
        $htmlSchedule = $this->getRequest()->getPost('pvw-email-input');

        $data = [];

        $jobNumber = trim($this->getRequest()->getPost('pvw-email-job-number'));

        $scheduleInfo = false;

        //check if job number exists in the database if the job number is not null or an emtpy string.
        //returns false to $scheduleInfo if job number doesn't exist:
        if($jobNumber != null && $jobNumber != '') {
            $scheduleInfo = $this->scheduleTable->getScheduleFromJobNumber($jobNumber);
        }

        if ($scheduleInfo !== false) {
            $schedId = $scheduleInfo['sched_id'];
            $tradeInfo = $this->scheduleRowTable->getTradeInfo($schedId);

            $data['jobId'] = $scheduleInfo['job_id'];
            $data['jobAddress'] = $scheduleInfo['address'];

            $scheduleRowModel = new ScheduleRow();
            foreach ($tradeInfo as $trade) {
                $scheduleRowModel->reset();

                $scheduleRowModel
                    ->setTradeName($trade['trade_name'])
                    ->setTradeEmail($trade['trade_email']);

                $data['tradeData'][] = $scheduleRowModel->getTradeDataForView();
            }

            $data['htmlSchedule'] = $htmlSchedule;
        }

        return new ViewModel($data);
    }

    public function emailAction()
    {
        $post = $this->getRequest()->getPost();
        $htmlMarkup = $this->getRequest()->getPost('em-html-email');

        $finalMarkup = [];
        $tradeEmails = [];

        foreach($post as $key => $value) {
            if(strpos($key, 'em-trade-name') !== false && strlen($value) !== 0) {
                $editedMarkup1 = str_replace("&lt;Trade Name&gt;", $value, $htmlMarkup);
                $editedMarkup2 = str_replace("&amp;trade=&amp;ver=", "&trade=" . $value, $editedMarkup1);
                $finalMarkup[] = $editedMarkup2;
            }
            else if(strpos($key, 'em-trade-email') !== false && strlen($value) !== 0) {
                $tradeEmails[] = $value;
            }

        }

        $finalEmailData = array_combine($tradeEmails, $finalMarkup);

        //todo Angela Need to modify the username and password variables below, and replace with authenticated user's username and password from the database
        $transport = new SmtpTransport();
        $options   = new SmtpOptions([
            'name' => 'gmail',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'connection_class'  => 'login',
            'connection_config' => [
                'username' => $this->emailInfo['username'],
                'password' => $this->emailInfo['password'],
                'ssl' => 'tls'
            ],
        ]);

        foreach($finalEmailData as $emailKey => $markupValue) {
            $html = new MimePart($markupValue);
            $html->type     = Mime::TYPE_HTML;
            $html->charset  = 'utf-8';
            $html->encoding = Mime::ENCODING_QUOTEDPRINTABLE;

            $body = new MimeMessage();
            $body->setParts([$html]);


            $message = new Message();

            $message->addFrom('awu.scheduler@gmail.com', 'Angela Wu');
            $message->addTo($emailKey);
            $message->setSubject('Schedule for Job 1076-19000-07 / Citadel Drive');
            $message->setBody($body);

            $transport->setOptions($options);
            $transport->send($message);

        }

        return new ViewModel();
    }

    public function confirmAction()
    {
        $tradeName = $this->getRequest()->getQuery('trade');
        $jobId = $this->getRequest()->getQuery('job');

        //todo Angela complete the code for application to send confirmation email to the user that created the schedule
    }



}
