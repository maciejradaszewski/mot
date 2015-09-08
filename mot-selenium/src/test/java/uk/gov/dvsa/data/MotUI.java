package uk.gov.dvsa.data;

import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.AssertionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.module.Register;
import uk.gov.dvsa.module.Retest;
import uk.gov.dvsa.ui.pages.VehicleSearchPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class MotUI {

    private MotAppDriver driver;
    private PageNavigator pageNavigator = new PageNavigator();
    private String expectedText;
    private boolean successful = false;

    public final Retest retest;
    public final Register register;

    public MotUI(MotAppDriver driver) {
        this.driver = driver;
        pageNavigator.setDriver(driver);
        retest = new Retest(pageNavigator);
        register = new Register(pageNavigator);
    }

    public void searchForVehicle(User user, Vehicle vehicle) throws IOException, URISyntaxException {
       VehicleSearchPage searchPage = pageNavigator.gotoVehicleSearchPage(user).searchVehicle(vehicle);
       expectedText = searchPage.getTestStatus();
    }

    public boolean isTextPresent(String actual) throws NoSuchElementException{
        return AssertionHelper.compareText(expectedText, actual);
    }

    public void duplicateReplacementPage(User user, Vehicle vehicle) throws IOException {
        pageNavigator.gotoDuplicateCertificatePage(user, vehicle);
    }

    public void isEditButtonDisplayedFor(String motTestNumber, boolean value) {
        By editButton = By.id(String.format("edit-%s", motTestNumber));
        assertThat(PageInteractionHelper.isElementDisplayed(editButton), is(value));
    }
}
