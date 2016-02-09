package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;
import uk.gov.dvsa.helper.Utilities;

import java.io.IOException;

public class FeaturesService extends Service {
    private static final String FEATURES_PATH = "/testsupport/features/";
    public FeaturesService() {
        super(WebDriverConfigurator.testSupportUrl());
    }

    public boolean getToggleValue(final String value) {
        boolean result = false;
        Response response = motClient.getFeature(FEATURES_PATH + value);

        try {
            result = ServiceResponse.createResponse(response, "toggle", boolean.class);
        } catch (IOException e) {
            Utilities.Logger.LogError(e.getMessage());
        }

        return result;
    }
}
