package uk.gov.dvsa.ui.views;

import org.testng.SkipException;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.service.FeaturesService;
import uk.gov.dvsa.helper.AssertionHelper;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.HomePage;

import java.io.IOException;
import java.net.URISyntaxException;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class JasperServiceTest extends BaseTest {
    FeaturesService service = new FeaturesService();

    @BeforeMethod(alwaysRun = true)
    private void isJasperAsyncEnabled() throws IOException {
        if (!service.getToggleValue("jasper.async")) {
            throw new SkipException("Jasper Async not Enabled");
        }
    }

    @Test(groups = {"BVT", "Regression"})
    public void showAsyncHeaderOnHomePage() throws IOException {

        //When I view my HomePage as a tester
        HomePage homePage = pageNavigator.gotoHomePage(userData.createTester(1));

        //Then I should see the "Your VTS activity" Header
        assertThat(homePage.isVtsActivityLabelDisplayed(), is(true));

        //And the MOT test certificates Link
        assertThat(homePage.isMotCertificateListDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression"})
    public void ShowAsyncSummaryPageAndCertificateListTest() throws IOException, URISyntaxException {

        //When I perform an MOT test as a tester
        User tester = userData.createTester(1);
        Vehicle vehicle = vehicleData.getNewVehicle(tester);

        motUI.normalTest.conductTestPass(tester, vehicle);

        //Then I should see the Mot Certificate Link on the test complete page
        AssertionHelper.assertValue(motUI.normalTest.isMotCertificateLinkDisplayed(), true);

        //And I can click the Mot certificate Link to the Certificates page.
        motUI.normalTest.certificatePage();
    }
}
