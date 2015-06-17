<?php

use MotFitnesse\Util\PersonUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class AssertTestUsersHaveRoles
 *
 * Test suite to assert our demo users have the expected roles
 */
class AssertTestUsersHaveRoles
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $username;

    /**
     * @var array
     */
    private $expectedRoles = [];

    /**
     * @var string
     */
    private $missingRoles = '';

    /**
     * @param $id
     */
    public function setUserId($id)
    {
        $this->userId = (int) $id;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param string $roles
     */
    public function setExpectedRoles($roles)
    {
        $this->expectedRoles = array_map('trim', explode(',', $roles));
    }

    /**
     * Return a comma separated string of roles that are missing
     * @return string
     */
    public function missingRoles()
    {
        $userRoles = $this->getRolesForUser();

        $roles = implode(
            ',',
            array_filter(
                $this->expectedRoles,
                function ($roleName) use ($userRoles) {
                    return !in_array($roleName, $userRoles);
                }
            )
        );

        $this->missingRoles = $roles;

        return $this->missingRoles;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return strlen($this->missingRoles) === 0;
    }

    /**
     * Get the roles for the current user.
     */
    private function getRolesForUser()
    {
        $apiUrl = PersonUrlBuilder::byId($this->userId)->rbacRoles()->toString();

        $ch = curl_init($apiUrl);
        $roles = [];

        TestShared::SetupCurlOptions($ch);
        TestShared::setAuthorizationInHeaderForUser($this->username, TestShared::PASSWORD, $ch);

        $result = TestShared::execCurlForJson($ch);

        // normal roles
        foreach ($result['data']['normal']['roles'] as $roleName) {
            $roles[] = $roleName;
        }

        // sites
        foreach ($result['data']['sites'] as $site) {
            foreach ($site['roles'] as $roleName) {
                $roles[] = $roleName;
            }
        }

        // organsiation
        foreach ($result['data']['organisations'] as $org) {
            foreach ($org['roles'] as $roleName) {
                $roles[] = $roleName;
            }
        }

        // make unique to avoid duplicate values e.g. SITE-MANAGER
        $roles = array_unique($roles);

        debug(__METHOD__, [
            'userId' => $this->userId,
            'roles' => $roles
        ]);

        return $roles;
    }
}
