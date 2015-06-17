<?php
use MotFitnesse\Util\UrlBuilder;

/**
 * VM-4698
 *
 * As a Customer Service Centre Operative I want to be able to find a user's MOT2 account so that I can determine which
 * account I need to authenticate the caller against.
 */
class FindUserAccount
{
    /** @var array [message, username, password, personId] */
    private $helpdeskUser;

    /** @var array */
    private $searchParams;

    /** @var array */
    private $resultData;

    /** @var bool */
    private $error;

    /** @var string */
    private $errorMessage;

    /** @var string */
    private $userRole;

    /** @var string */
    private $town;

    public function reset()
    {
        $this->searchParams = [];
        $this->resultData = [];
        $this->error = false;
        $this->errorMessage = '';
        $this->userRole = '';
        $this->town = '';
    }

    /**
     * @param string $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        if (!empty($dateOfBirth)) {
            $this->searchParams['dateOfBirth'] = $dateOfBirth;
        }
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        if (!empty($firstName)) {
            $this->searchParams['firstName'] = $firstName;
        }
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        if (!empty($lastName)) {
            $this->searchParams['lastName'] = $lastName;
        }
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        if (!empty($postcode)) {
            $this->searchParams['postcode'] = $postcode;
        }
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        if (!empty($username)) {
            $this->searchParams['username'] = $username;
        }
    }

    /**
     * @param string $town
     */
    public function setTown($town)
    {
        if (!empty($town)) {
            $this->searchParams['town'] = $town;
        }
    }

    /**
     * @param string $userRole
     */
    public function setUserRole($userRole)
    {
        $this->userRole = $userRole;
    }

    /**
     * @return int
     */
    public function resultCount()
    {
        return is_array($this->resultData) ? count($this->resultData) : 0;
    }

    /**
     * @return string
     */
    public function foundUsers()
    {
        $personIds = [];
        foreach ($this->resultData as $entry) {
            $personIds[] = $entry['id'];
        }

        return join(' ', $personIds);
    }

    /** @return bool */
    public function error()
    {
        return $this->error;
    }

    /** @return string */
    public function errorMessage()
    {
        return $this->errorMessage;
    }

    public function execute()
    {
        switch($this->userRole) {
            case 'CSCO':
                $this->createCustomerServiceOperativeUser();
                break;
            case 'VE':
                $this->createVehicleExaminerUser();
                break;
            case 'AO':
                $this->createAreaOfficeUser();
                break;
        }

        $apiClient = FitMotApiClient::create($this->helpdeskUser['username'], $this->helpdeskUser['password']);
        $urlBuilder = UrlBuilder::create()->searchPerson();

        foreach ($this->searchParams as $name => $value) {
            $urlBuilder->queryParam($name, $value);
        }

        try {
            $this->resultData = $apiClient->get($urlBuilder);
        } catch (ApiErrorException $ex) {
            $this->error = true;
            $this->errorMessage = $ex->getDisplayMessage();
        }
    }

    private function createCustomerServiceOperativeUser()
    {
        $this->helpdeskUser = (new TestSupportHelper())->createCustomerServiceCentreOperative();
    }

    private function createAreaOfficeUser()
    {
        $this->helpdeskUser = (new TestSupportHelper())->createAreaOffice1User();
    }

    private function createVehicleExaminerUser()
    {
        $this->helpdeskUser = (new TestSupportHelper())->createVehicleExaminer();
    }

}
