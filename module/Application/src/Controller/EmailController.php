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

use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use Application\Database\ScheduleTable;
use Application\Database\ScheduleRowTable;
use Application\Database\JobTable;

use Application\Model\Job;
use Application\Model\Schedule;
use Application\Model\ScheduleRow;



class EmailController extends AbstractActionController
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

    public function draftAction()
    {
        $htmlSchedule = $this->getRequest()->getPost('em-html-email');

        $data = [];

        $scheduleInfo = $this->scheduleTable->getScheduleInfo(107619000);
        $schedId = $scheduleInfo['sched_id'];
        $tradeInfo = $this->scheduleRowTable->getTradeInfo($schedId);

        $data['jobId'] = $scheduleInfo['job_id'];
        $data['jobAddress'] = $scheduleInfo['address'];

        $scheduleRowModel = new ScheduleRow();
        foreach($tradeInfo as $trade) {
            $scheduleRowModel->reset();

            $scheduleRowModel
                ->setTradeName($trade['trade_name'])
                ->setTradeEmail($trade['trade_email']);

            $data['tradeData'][] = $scheduleRowModel->getTradeDataForView();
        }

        $data['htmlSchedule'] = $htmlSchedule;

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

        $transport = new SmtpTransport();
        $options   = new SmtpOptions([
            'name' => 'gmail',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'connection_class'  => 'login',
            'connection_config' => [
                'username' => 'awu.scheduler@gmail.com',
                'password' => 'iloveprogramming',
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

        /*$emailList = [];

        foreach($post as $key => $value) {
            if(strpos($key, 'em-trade-email') !== false && strlen($value) !== 0) {
                $emailList[] = $value;
            }
        }*/

        /*$html = new MimePart($htmlMarkup);
        $html->type     = Mime::TYPE_HTML;
        $html->charset  = 'utf-8';
        $html->encoding = Mime::ENCODING_QUOTEDPRINTABLE;

        $body = new MimeMessage();
        $body->setParts([$html]);

        $message = new Message();

        $message->addFrom('awu.scheduler@gmail.com', 'Angela Wu');
        $message->addTo($emailList);
        $message->setSubject('Schedule for Job 1076-19000-07 / Citadel Drive');
        $message->setBody($body);*/

        /*$contentTypeHeader = $message->getHeaders()->get('Content-Type');
        $contentTypeHeader->setType('multipart/related');*/

        /*ini_set("SMTP","ssl://smtp.gmail.com");
        ini_set("smtp_port","465");

        $transport = new SendmailTransport();
        $transport->send($message);*/





        /*$transport = new SmtpTransport();
        $options   = new SmtpOptions([
            'name' => 'gmail',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'connection_class'  => 'login',
            'connection_config' => [
                'username' => 'awu.scheduler@gmail.com',
                'password' => 'iloveprogramming',
                'ssl' => 'tls'
            ],
        ]);
        $transport->setOptions($options);
        $transport->send($message);*/

        return new ViewModel();
    }

    public function tradeAction()
    {
        /*$scheduleInfo = $this->scheduleTable->getScheduleInfo(107619000);

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
        }*/

        /*$sessionManager = new SessionManager();
        $sessionContainer = new Container('schedulerContainer', $sessionManager);
        $sessionContainer->schedData = $schedData;*/

        //return new ViewModel($schedData);
    }



}
