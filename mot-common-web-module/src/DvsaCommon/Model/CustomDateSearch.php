<?php

namespace DvsaCommon\Model;

use Zend\Form\Annotation;
use DvsaCommon\Utility\ArrayUtils;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Attributes({"class":"form-vertical","id":"CustomDateSearch","method":"GET"})
 * @Annotation\Name("CustomDateSearch")
 */
class CustomDateSearch
{

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateFromDay")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"2"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Day"})
     */
    public $dateFromDay;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateFromMonth")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"2"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\
     * @Annotation\Options({"label":"Month"})
     */
    public $dateFromMonth;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateFromYear")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"4"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Year"})
     */
    public $dateFromYear;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateToDay")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"2"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Day"})
     */
    public $dateToDay;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateToMonth")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"2"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Month"})
     */
    public $dateToMonth;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateToYear")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"4"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Year"})
     */
    public $dateToYear;

    /**
     * @Annotation\Type("Zend\Form\Element\Hidden")
     * @Annotation\Name("dateRange")
     * @Annotation\Attributes({"value":"custom"})
     */
    public $dateRange;

    /**
     * @Annotation\Type("Zend\Form\Element\Hidden")
     * @Annotation\Name("sortColumnId")
     * @Annotation\Attributes({"value":"0"})
     */
    public $sortColumnId;

    /**
     * @Annotation\Type("Zend\Form\Element\Hidden")
     * @Annotation\Name("sortDirection")
     * @Annotation\Attributes({"value":"DESC"})
     */
    public $sortDirection;

    /**
     * @Annotation\Type("Zend\Form\Element\Hidden")
     * @Annotation\Name("rowCount")
     * @Annotation\Attributes({"value":"10"})
     */
    public $rowCount;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Name("submit")
     * @Annotation\Attributes({"class":"btn btn-primary","value":"Apply"})
     */
    public $submit;

    public function exchangeArray($data)
    {
        $fields = [
            'dateFromDay',
            'dateFromMonth',
            'dateFromYear',
            'dateToDay',
            'dateToMonth',
            'dateToYear',
            'dateRange',
            'sortColumnId',
            'sortDirection',
            'rowCount'
        ];

        foreach ($fields as $field) {
            $this->$field = ArrayUtils::tryGet($data, $field);
        }
    }
}
