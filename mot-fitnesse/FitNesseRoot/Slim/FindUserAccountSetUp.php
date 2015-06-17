<?php

/**
 * Create user accounts to have something to search in.
 *
 * @see \FindUserAccount
 */
class FindUserAccountSetUp
{
    private $userData;
    private $personId;

    public function reset()
    {
        $this->userData = [];
        $this->personId = null;
    }

    /**
     * @param string $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        if (!empty($dateOfBirth)) {
            $this->userData['dateOfBirth'] = $dateOfBirth;
        }
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        if (!empty($firstName)) {
            $this->userData['firstName'] = $firstName;
        }
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        if (!empty($lastName)) {
            $this->userData['surname'] = $lastName;
        }
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        if (!empty($postcode)) {
            $this->userData['postcode'] = $postcode;
        }
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        if (!empty($username)) {
            $this->userData['username'] = $username;
        }
    }

    public function personId()
    {
        return $this->personId;
    }

    public function execute()
    {
        $testSupport = new TestSupportHelper();
        $response = $testSupport->createUser($this->userData);
        $this->personId = $response['personId'];
    }
}
