package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;

public class FeaturesService extends Service
{
    private static final String FEATURES_PATH = "/testsupport/features/";
    public FeaturesService() {
        super(WebDriverConfigurator.testSupportUrl());
    }

    public boolean getToggleValue(String value) throws IOException {
        Response response = motClient.getFeature(FEATURES_PATH + value);
        return response.body().path("data.toggle");
    }
}
