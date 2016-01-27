package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import org.apache.http.HttpStatus;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;
import uk.gov.dvsa.domain.api.exception.InvalidToggleParameterException;

public class FeaturesService extends Service {
    private static final String FEATURES_PATH = "/testsupport/features/";
    public FeaturesService() {
        super(WebDriverConfigurator.testSupportUrl());
    }

    public boolean getToggleValue(final String value) {
        Response response = motClient.getFeature(FEATURES_PATH + value);

        if (response.statusCode() != HttpStatus.SC_OK) {
            throw new InvalidToggleParameterException(
                    response.body().path("errors.exception.message").toString());
        }

        return response.body().path("data.toggle");
    }
}
