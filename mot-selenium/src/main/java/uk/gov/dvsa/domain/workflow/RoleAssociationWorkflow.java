package uk.gov.dvsa.domain.workflow;

import org.openqa.selenium.Cookie;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.service.CookieService;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.*;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;
import uk.gov.dvsa.ui.pages.vts.VtsChooseARolePage;
import uk.gov.dvsa.ui.pages.vts.VtsSearchForAUserPage;

import java.io.IOException;

public class RoleAssociationWorkflow extends BaseWorkflow {

    private MotAppDriver driver;

    public void setDriver(MotAppDriver driver) {
        this.driver = driver;
    }

    public VtsChooseARolePage asAedmNavigateToVtsChooseARolePage(User user, User tester, String vtsNumber) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(VtsSearchForAUserPage.path, vtsNumber));
        PageLocator.getVtsSearchForAUserPage(driver)
                .fillUserSearchBoxInput(tester.getUsername()).clickSearchButton();

        return new VtsChooseARolePage(driver);
    }

    public VehicleTestingStationPage assignSiteAdminRoleToUser() {
        PageLocator.getVtsChooseARolePage(driver)
                .selectSiteAdminRole()
                .clickSelectButton();
        PageLocator.getVtsSummaryAndConfirmationPage(driver).clickConfirmButton();

        return new VehicleTestingStationPage(driver);
    }

    public VehicleTestingStationPage assignSiteManagerRoleToUser() {
        PageLocator.getVtsChooseARolePage(driver)
                .selectSiteManagerRole()
                .clickSelectButton();
        PageLocator.getVtsSummaryAndConfirmationPage(driver).clickConfirmButton();

        return new VehicleTestingStationPage(driver);
    }

    public HomePage navigateToHomePage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, HomePage.path);

        return new HomePage(driver);
    }

    public NotificationPage acceptNomination() {
        PageLocator.getHomePage(driver)
                .clickOnLastNomination();
        PageLocator.getNotificationPage(driver)
                .clickAcceptButton();

        return new NotificationPage(driver);
    }

    public NotificationPage rejectNomination() {
        PageLocator.getHomePage(driver)
                .clickOnLastNomination();
        PageLocator.getNotificationPage(driver)
                .clickRejectButton();

        return new NotificationPage(driver);
    }

    public NotificationPage loginAndNavigateToLastReceivedNotification(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, HomePage.path);
        PageLocator.getHomePage(driver)
                .clickOnLastNomination();

        return new NotificationPage(driver);
    }

    private void injectOpenAmCookieAndNavigateToPath(User user, String path) throws IOException {
        driver.setUser(user);
        driver.manage().addCookie(getCookieForUser(user));
        driver.navigateToPath(path);
    }
    private Cookie getCookieForUser(User user) throws IOException {
        return CookieService.generateOpenAmLoginCookie(user);
    }
}