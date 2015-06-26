package uk.gov.dvsa.domain.workflow;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public abstract class BaseWorkflow{

    private MotAppDriver driver;

    public void setDriver(MotAppDriver driver) {
        this.driver = driver;
    }
}