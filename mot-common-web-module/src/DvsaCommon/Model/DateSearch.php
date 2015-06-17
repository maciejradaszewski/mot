<?php
namespace DvsaCommon\Model;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ArraySerializable")
 * @Annotation\Name("DateSearch")
 */
class DateSearch
{
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateFromDay")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"2"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Day"})
     * @Annotation\Validator({"name":"Digits",
     *                  "options":{
     *                       "messages":{
     *                          "notDigits": "The date value should be a valid number",
     *                      }}})
     * @Annotation\Validator({"name":"Between",
     *                  "options":{
     *                       "min": 1,
     *                       "max": 31,
     *                       "messages":{
     *                          "notBetween": "The days value should be between %min% and %max%",
     *                      }}})
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
     * @Annotation\Validator({"name":"Digits",
     *                  "options":{
     *                       "messages":{
     *                          "notDigits": "The date value should be a valid number",
     *                      }}})
     * @Annotation\Validator({"name":"Between",
     *                  "options":{
     *                       "min": 1,
     *                       "max": 12,
     *                       "messages":{
     *                          "notBetween": "The month value should be between %min% and %max%",
     *                      }}})
     */
    public $dateFromMonth;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateFromYear")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"4"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Year"})
     * @Annotation\Validator({"name":"Digits",
     *                  "options":{
     *                       "messages":{
     *                          "notDigits": "The date value should be a valid number",
     *                      }}})
     * @Annotation\Validator({"name":"Between",
     *                  "options":{
     *                       "min": 2000,
     *                       "max": 2100,
     *                       "messages":{
     *                          "notBetween": "The year value should be between %min% and %max%",
     *                      }}})
     */
    public $dateFromYear;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateToDay")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"2"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Day"})
     * @Annotation\Validator({"name":"Digits",
     *                  "options":{
     *                       "messages":{
     *                          "notDigits": "The date value should be a valid number",
     *                      }}})
     * @Annotation\Validator({"name":"Between",
     *                  "options":{
     *                       "min": 1,
     *                       "max": 31,
     *                       "messages":{
     *                          "notBetween": "The days value should be between %min% and %max%",
     *                      }}})
     */
    public $dateToDay;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateToMonth")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"2"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Month"})
     * @Annotation\Validator({"name":"Digits",
     *                  "options":{
     *                       "messages":{
     *                          "notDigits": "The date value should be a valid number",
     *                      }}})
     * @Annotation\Validator({"name":"Between",
     *                  "options":{
     *                       "min": 1,
     *                       "max": 12,
     *                       "messages":{
     *                          "notBetween": "The month value should be between %min% and %max%",
     *                      }}})
     */
    public $dateToMonth;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateToYear")
     * @Annotation\Attributes({"class":"form-control","pattern":"[0-9]*","maxlength":"4"})
     * @Annotation\AllowEmpty
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Year"})
     * @Annotation\Validator({"name":"Digits",
     *                  "options":{
     *                       "messages":{
     *                          "notDigits": "The date value should be a valid number",
     *                      }}})
     * @Annotation\Validator({"name":"Between",
     *                  "options":{
     *                       "min": 2000,
     *                       "max": 2100,
     *                       "messages":{
     *                          "notBetween": "The year value should be between %min% and %max%",
     *                      }}})
     */
    public $dateToYear;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Name("dateRange")
     * @Annotation\Attributes({"value":"custom"})
     */
    public $dateRange;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->dateFromDay = (!empty($data['dateFromDay'])) ? $data['dateFromDay'] : null;
        $this->dateFromMonth = (!empty($data['dateFromMonth'])) ? $data['dateFromMonth'] : null;
        $this->dateFromYear = (!empty($data['dateFromYear'])) ? $data['dateFromYear'] : null;
        $this->dateToDay = (!empty($data['dateToDay'])) ? $data['dateToDay'] : null;
        $this->dateToMonth = (!empty($data['dateToMonth'])) ? $data['dateToMonth'] : null;
        $this->dateToYear = (!empty($data['dateToYear'])) ? $data['dateToYear'] : null;
        $this->dateRange = (!empty($data['dateRange'])) ? $data['dateRange'] : null;
    }
}
