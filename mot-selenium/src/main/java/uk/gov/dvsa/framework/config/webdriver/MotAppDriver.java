package uk.gov.dvsa.framework.config.webdriver;

import org.openqa.selenium.remote.RemoteWebDriver;
import uk.gov.dvsa.domain.model.User;

public abstract class MotAppDriver implements MotWebDriver {

    protected RemoteWebDriver remoteWebDriver;
    private User user = null;

    private String baseUrl = "";

    public MotAppDriver(RemoteWebDriver remoteWebDriver){
        this.remoteWebDriver = remoteWebDriver;
    }

    public void setBaseUrl(String baseUrl) {
        this.baseUrl = baseUrl;
    }

    public void loadBaseUrl(){
        remoteWebDriver.get(baseUrl);
    }

    public void navigateToPath(String path){
        remoteWebDriver.get(baseUrl + path);
    }

    public void setUser(User user) {
        this.user = user;
    }

    public User getCurrentUser() {
        return user;
    }
}
