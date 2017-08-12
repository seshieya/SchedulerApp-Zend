<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 02/08/2017
 * Time: 11:08 PM
 */

namespace Application\Model;

class ScheduleRow implements ArrayForDatabase
{

    //private $rowId;
    private $schedId;
    private $tradeName;
    private $tradeEmail;
    private $typeOfWork;
    private $dayIn;
    private $dayOut;
    private $comments;
    private $unitNum;

    public function reset() {
        $this->schedId = 0;
        $this->tradeName = '';
        $this->tradeEmail = '';
        $this->typeOfWork = '';
        $this->dayIn = '';
        $this->dayOut = '';
        $this->comments = '';
        $this->unitNum = '';
    }

    /**
     * @return mixed
     */
    /*public function getRowId()
    {
        return $this->rowId;
    }*/

    /**
     * @param mixed $rowId
     */
    /*public function setRowId($rowId)
    {
        $this->rowId = $rowId;
    }*/

    /**
     * @return mixed
     */
    public function getSchedId()
    {
        return $this->schedId;
    }

    /**
     * @param mixed $schedId
     */
    public function setSchedId($schedId)
    {
        $this->schedId = $schedId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTradeName()
    {
        return $this->tradeName;
    }

    /**
     * @param mixed $tradeName
     */
    public function setTradeName($tradeName)
    {
        $this->tradeName = $tradeName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTradeEmail()
    {
        return $this->tradeEmail;
    }

    /**
     * @param mixed $tradeEmail
     */
    public function setTradeEmail($tradeEmail)
    {
        $this->tradeEmail = $tradeEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeOfWork()
    {
        return $this->typeOfWork;
    }

    /**
     * @param mixed $typeOfWork
     */
    public function setTypeOfWork($typeOfWork)
    {
        $this->typeOfWork = $typeOfWork;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDayIn()
    {
        return $this->dayIn;
    }

    /**
     * @param mixed $dayIn
     */
    public function setDayIn($dayIn)
    {
        $this->dayIn = $dayIn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDayOut()
    {
        return $this->dayOut;
    }

    /**
     * @param mixed $dayOut
     */
    public function setDayOut($dayOut)
    {
        $this->dayOut = $dayOut;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitNum()
    {
        return $this->unitNum;
    }

    /**
     * @param mixed $unitNum
     */
    public function setUnitNum($unitNum)
    {
        $this->unitNum = $unitNum;
        return $this;
    }

    public function getArrayForDatabase() {
        return [
            'sched_id' => $this->getSchedId(),
            'trade_name' => $this->getTradeName(),
            'trade_email' => $this->getTradeEmail(),
            'type_of_work' => $this->getTypeOfWork(),
            'day_in' => $this->getDayIn(),
            'day_out' => $this->getDayOut(),
            'comments' => $this->getComments(),
        ];
    }

}