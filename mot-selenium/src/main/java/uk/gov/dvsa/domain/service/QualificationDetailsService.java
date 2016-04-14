package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.domain.api.request.*;
import uk.gov.dvsa.domain.model.QualificationCertificate;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;

public class QualificationDetailsService extends Service {
    private static final String CREATE_CERTIFICATE_PATH = "/testsupport/qualification-details";
    protected static final String GROUP_A = "A";
    protected static final String GROUP_B = "B";
    private AuthService authService = new AuthService();

    protected QualificationDetailsService() {
        super(WebDriverConfigurator.testSupportUrl());
    }

    protected QualificationCertificate createQualificationCertificate(
        User user,
        String groupCode,
        String certificateNumber,
        String dateOfQualification,
        String siteNumber
    ) throws IOException {
        String request =
            jsonHandler.convertToString(new CreateQualificationCertificateRequest(
                user.getId(),
                user.getUsername(),
                user.getPassword(),
                groupCode,
                certificateNumber,
                dateOfQualification,
                siteNumber)
            );

        String token = authService.createSessionTokenForUser(user);
        Response response = motClient.createQualificationCertificate(request, CREATE_CERTIFICATE_PATH, token);

        return ServiceResponse.createResponse(response, QualificationCertificate.class);
    }
}
