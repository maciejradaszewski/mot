package uk.gov.dvsa.domain.service;

import uk.gov.dvsa.domain.api.client.MotClient;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;
import uk.gov.dvsa.helper.JsonHandler;

public abstract class BaseService {

    protected JsonHandler jsonHandler = new JsonHandler();
    protected MotClient motClient;

    public BaseService(String clientUrl) {
        motClient = new MotClient(clientUrl);
    }
}
