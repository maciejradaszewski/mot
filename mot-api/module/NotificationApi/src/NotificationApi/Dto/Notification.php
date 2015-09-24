<?php
namespace NotificationApi\Dto;

/**
 * Data transfer object for Notification
 */
class Notification
{
    /** values from database `notification_template` */
    const TEMPLATE_TESTER_APPLICATION_APPROVED = 1;
    const TEMPLATE_TESTER_APPLICATION_REJECTED = 2;
    const TEMPLATE_TESTER_INITIAL_TRAINING_PASSED = 3;
    const TEMPLATE_TESTER_INITIAL_TRAINING_FAILED = 4;
    const TEMPLATE_SITE_NOMINATION = 5;
    const TEMPLATE_SITE_NOMINATION_DECISION = 6;
    const TEMPLATE_ORGANISATION_NOMINATION = 7;
    const TEMPLATE_ORGANISATION_NOMINATION_DECISION = 8;
    const TEMPLATE_ORGANISATION_NOMINATION_GIVEN = 9;
    const TEMPLATE_ORGANISATION_POSITION_REMOVED = 10;
    const TEMPLATE_SITE_POSITION_REMOVED = 11;
    const TEMPLATE_TESTING_OUTSIDE_OPENING_HOURS = 12;
    const TEMPLATE_MOT_TEST_STATUS_CHANGED_BY_ANOTHER_USER = 13;
    const TEMPLATE_TESTER_QUALIFICATION_STATUS = 14;
    const TEMPLATE_DVSA_USER_LINK_SITE_TO_AE = 19;
    const TEMPLATE_AE_UNLINK_SITE = 18;
    const TEMPLATE_DVSA_ASSIGN_ROLE = 16;
    const TEMPLATE_DVSA_REMOVE_ROLE = 17;
	const TEMPLATE_TESTER_STATUS_CHANGE = 20;
    const TEMPLATE_TESTER_STATUS_CHANGE_NEW = 22;

    /** @var $template int */
    private $template;

    /** @var $recipient int */
    private $recipient;

    /** @var $fields array */
    private $fields;

    public function __construct()
    {
        $this->fields = [];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'template'  => $this->getTemplate(),
            'recipient' => $this->getRecipient(),
            'fields'    => $this->getFields()
        ];
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return Notification
     */
    public function addField($key, $value)
    {
        $this->fields[$key] = $value;
        return $this;
    }

    /**
     * @param array $fields
     *
     * @return Notification
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param mixed $recipient
     *
     * @return $this
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $template
     *
     * @return Notification
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
