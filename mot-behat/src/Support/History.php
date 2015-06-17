<?php

namespace Dvsa\Mot\Behat\Support;

interface History
{
    /**
     * @throws \LogicException
     *
     * @return Response
     */
    public function getLastResponse();

    /**
     * @return Response[]
     */
    public function getAllResponses();

    /**
     * @return null
     */
    public function clear();
}
