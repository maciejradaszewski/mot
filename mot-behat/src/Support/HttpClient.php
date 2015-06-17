<?php

namespace Dvsa\Mot\Behat\Support;

interface HttpClient
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function request(Request $request);
}
