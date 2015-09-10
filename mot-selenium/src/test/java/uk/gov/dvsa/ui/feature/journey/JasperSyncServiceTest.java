package uk.gov.dvsa.ui.feature.journey;

import org.openqa.selenium.NoSuchElementException;
import org.testng.SkipException;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.service.FeaturesService;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.exception.PageInstanceNotFoundException;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class JasperSyncServiceTest extends BaseTest {
    FeaturesService service = new FeaturesService();

    @BeforeMethod(alwaysRun = true)
    private void isJasperAsyncEnabled() throws IOException {
        if (service.getToggleValue("jasper.async")) {
            throw new SkipException("Jasper Async Enabled");
        }
    }

    @Test(groups = {"BVT", "Regression"}, expectedExceptions = NoSuchElementException.class)
    public void showAsyncHeaderNotShownOnHomePage() throws IOException {

        //When I view my HomePage as a tester
        HomePage homePage = pageNavigator.gotoHomePage(userData.createTester(1));

        //Then I should NOT see the "Your VTS activity" Header
        assertThat(homePage.isVtsActivityLabelDisplayed(), is(false));

        //And I should NOT see the Mot Certificate Link
        assertThat(homePage.isMotCertificateListDisplayed(), is(false));
    }

    @Test(groups = {"BVT", "Regression"})
    public void printButtonIsDisplayed() throws IOException, URISyntaxException {

        //When I perform an MOT test as a tester
        User tester = userData.createTester(1);
        Vehicle vehicle = vehicleData.getNewVehicle(tester);

        motUI.normalTest.conductTestPass(tester, vehicle);

        //Then I should see the Print Button
        assertThat(motUI.normalTest.isPrintButtonDisplayed(), is((true)));
    }

    @Test(groups = {"BVT", "Regression"}, expectedExceptions = PageInstanceNotFoundException.class)
    public void returnPageNotFoundForCertificateLink() throws IOException, URISyntaxException {

        //When I navigate to the mot certificate page
        motUI.certificatePage(userData.createTester(1));

        //Then I should get a Page not Found Exception
    }
}
