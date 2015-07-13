<?php
namespace DvsaCommon\Date;

interface DateTimeHolderInterface
{
    public function getCurrent($withMilliseconds = false);

    public function getCurrentDate();
}
