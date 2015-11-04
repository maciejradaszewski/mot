package uk.gov.dvsa.ui.views;

import org.testng.SkipException;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.service.FeaturesService;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;

public class BreadcrumbViewTests extends BaseTest {

    private Vehicle vehicle;
    private User tester;
    AeDetails aeDetails;

    @BeforeMethod(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        Site site = siteData.createNewSite(aeDetails.getId(), "default-site");
        tester = userData.createTester(site.getId());
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @Test(groups = {"Regression"}, description = "Breadcrumb is visible in vehicle testing station")
    public void isBreadcrumbVisibleInVehicleTestingStationPage() throws IOException, URISyntaxException {
        //Given I am logged in as tester & I navigate to the vehicle testing station page
        //& navigate to random Vts
        VehicleTestingStationPage vehicleTestingStationPage = pageNavigator.gotoHomePage(tester)
                                                                           .selectRandomVts();

        //Then the breadcrumbNames are displayed in breadcrumb top navigation panel
        assertThat(vehicleTestingStationPage.getPageHeader(), containsString(aeDetails.getAeName()));
    }

    private void isJasperAsyncEnabled() throws IOException {
        FeaturesService service = new FeaturesService();
        if (!service.getToggleValue("jasper.async")) {
            throw new SkipException("Jasper Async not Enabled");
        }
    }


}
