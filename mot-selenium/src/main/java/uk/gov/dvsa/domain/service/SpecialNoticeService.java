package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.domain.api.request.SpecialNoticeRequest;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;

public class SpecialNoticeService extends Service {

    private static final String BROADCAST_NOTICE_PATH = "/testsupport/special-notice/broadcast";

    public SpecialNoticeService(){
        super(WebDriverConfigurator.testSupportUrl());
    }

    public boolean broadcastSpecialNotice(int specialNoticeId, String username, boolean isAcknowledged) throws
        IOException {
        String request = jsonHandler.convertToString(new SpecialNoticeRequest(specialNoticeId,
            username, isAcknowledged));
        Response response = motClient.broadcastSpecialNotice(request, BROADCAST_NOTICE_PATH);

        return response.path("data") != null;
    }
}
