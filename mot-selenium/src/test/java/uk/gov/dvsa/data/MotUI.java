package uk.gov.dvsa.data;

import org.openqa.selenium.NoSuchElementException;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.AssertionHelper;
import uk.gov.dvsa.module.*;
import uk.gov.dvsa.ui.pages.VehicleSearchPage;
import uk.gov.dvsa.ui.pages.mot.StartTestConfirmationPage;

import java.io.IOException;
import java.net.URISyntaxException;

public class MotUI {

    private PageNavigator pageNavigator = new PageNavigator();
    private String expectedText;

    public final Retest retest;
    public final NormalTest normalTest;
    public final Register register;
    public final ManageRoles manageRoles;
    public final TestLog testLog;
    public final SearchUser searchUser;
    public final SearchSite searchSite;
    public final Vts vts;
    
    public final DuplicateReplacementCertificate duplicateReplacementCertificate;
    public final ContingencyTest contingencyTest;

    public MotUI(MotAppDriver driver) {
        pageNavigator.setDriver(driver);
        retest = new Retest(pageNavigator);
        register = new Register(pageNavigator);
        normalTest = new NormalTest(pageNavigator);
        testLog = new TestLog(pageNavigator);
        manageRoles = new ManageRoles(pageNavigator);
        searchUser = new SearchUser(pageNavigator);
        searchSite = new SearchSite(pageNavigator);
        duplicateReplacementCertificate = new DuplicateReplacementCertificate(pageNavigator);
        vts = new Vts(pageNavigator);
        contingencyTest = new ContingencyTest(pageNavigator);
    }

    public void startTestConfirmationPage(User user, Vehicle vehicle) throws IOException, URISyntaxException {
        StartTestConfirmationPage startTestConfirmationPage = pageNavigator.goToStartTestConfirmationPage(user, vehicle);
        expectedText = startTestConfirmationPage.getVehicleWeight();
    }

    public void searchForVehicle(User user, Vehicle vehicle) throws IOException, URISyntaxException {
       VehicleSearchPage searchPage = pageNavigator.gotoVehicleSearchPage(user).searchVehicle(vehicle);
       expectedText = searchPage.getTestStatus();
    }

    public boolean isTextPresent(String actual) throws NoSuchElementException{
        return AssertionHelper.compareText(expectedText, actual);
    }

    public void certificatePage(User user) throws IOException {
        pageNavigator.gotoMotTestCertificatesPage(user);
    }
}
