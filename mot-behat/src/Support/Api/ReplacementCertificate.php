<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Support\HttpClient;

class ReplacementCertificate extends MotApi
{
    const PATH_DRAFT = '/mot-test/{mot_test_number}/replacement-certificate-draft/{draft_id}';
    const PATH_APPLAY_DRAFT = "/apply";

    public function getDraft($motTestNumber, $draftId, $token)
    {
        $path = str_replace(['{mot_test_number}', '{draft_id}'], [$motTestNumber, $draftId], self::PATH_DRAFT);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            $path
        );
    }

    public function createDraft($token, $motTestNumber)
    {
        $path = str_replace(['{mot_test_number}', '/{draft_id}'], [$motTestNumber, ""], self::PATH_DRAFT);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            $path,
            ['motTestNumber' => $motTestNumber]
        );
    }

    public function updateDraft($motTestNumber, $draftId, array $params, $token)
    {
        $path = str_replace(['{mot_test_number}', '{draft_id}'], [$motTestNumber,$draftId], self::PATH_DRAFT);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_PUT,
            $path,
            $params
        );
    }

    public function applyDraft($motTestNumber, $draftId, array $params, $token)
    {
        $path = str_replace(['{mot_test_number}', '{draft_id}'], [$motTestNumber,$draftId], self::PATH_DRAFT) . self::PATH_APPLAY_DRAFT;

        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            $path,
            $params
        );
    }
}
