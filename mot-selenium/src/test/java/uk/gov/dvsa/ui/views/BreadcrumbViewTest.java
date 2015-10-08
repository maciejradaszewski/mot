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
import uk.gov.dvsa.ui.pages.VehicleSearchPage;
import uk.gov.dvsa.ui.pages.mot.MotTestCertificatesPage;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static uk.gov.dvsa.matcher.BreadcrumbMatcher.hasBreadcrumbElements;

public class BreadcrumbViewTest extends BaseTest {

    private Vehicle vehicle;
    private User tester;

    @BeforeMethod(alwaysRun = true)
    private void setup() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        Site site = siteData.createNewSite(aeDetails.getId(), "default-site");
        tester = userData.createTester(site.getId());
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @Test(groups = {"Regression"}, description = "Breadcrumb is visible in training test page")
    public void isBreadcrumbVisibleInTrainingTestPage() throws IOException {
        //Given I am logged in as tester & I navigate to the training test vehicle search page
        VehicleSearchPage vehicleSearchPage =
                pageNavigator.goToTrainingTestVehicleSearchPage(tester);
        
        //Then the breadcrumbNames are displayed in breadcrumb top navigation panel
        assertThat(vehicleSearchPage, hasBreadcrumbElements(vehicleSearchPage.getPageHeader()));
    }

    @Test(groups = {"Regression"}, description = "Breadcrumb is visible in training test page")
    public void isBreadcrumbVisibleInTrainingTestFindVehiclePage() throws IOException {
        //Given I am logged in as tester & I navigate to the training test vehicle search page
        // When I search random vehicle and I navigate to find vehicle page
        VehicleSearchPage vehicleSearchPage =
                pageNavigator.goToTrainingTestVehicleSearchPage(tester)
                             .searchVehicle(vehicle);

        //Then the same breadcrumbNames are displayed in breadcrumb top navigation panel
        assertThat(vehicleSearchPage, hasBreadcrumbElements(vehicleSearchPage.getPageHeader()));
    }

    @Test(groups = {"Regression"}, description = "Breadcrumb is visible in vehicle testing station")
    public void isBreadcrumbVisibleInVehicleTestingStationPage() throws IOException, URISyntaxException {
        //Given I am logged in as tester & I navigate to the vehicle testing station page
        //& navigate to random Vts
        VehicleTestingStationPage vehicleTestingStationPage = pageNavigator.gotoHomePage(tester)
                                                                           .selectRandomVts();

        //Then the breadcrumbNames are displayed in breadcrumb top navigation panel
        assertThat(vehicleTestingStationPage,
                hasBreadcrumbElements(vehicleTestingStationPage.getPageHeader()));
    }

    @Test(groups = {"Regression"}, description = "Breadcrumb is visible in mot test certificates page")
    public void isBreadcrumbVisibleInMotTestCertificatesPage() throws IOException, URISyntaxException {
        //Check if Jasper Async flag is on

        //Given I am logged in as tester & I navigate to the vehicle testing station page
        //& navigate to random Vts
        VehicleTestingStationPage vehicleTestingStationPage = pageNavigator.gotoHomePage(tester)
                                                                           .selectRandomVts();

        String aeName = vehicleTestingStationPage.getAuthorisedExaminer();
        String vtsName = vehicleTestingStationPage.getVtsName();

        //& when I navigate to the mot test certificates page
        MotTestCertificatesPage motTestCertificatesPage =
                vehicleTestingStationPage.clickOnMotTestRecentCertificatesLink();

        //Then the breadcrumbNames are displayed in breadcrumb top navigation panel
        assertThat(motTestCertificatesPage,
                hasBreadcrumbElements(aeName, vtsName, MotTestCertificatesPage.PAGE_HEADER)
        );

    }

    @Test(groups = {"Regression"}, description = "Breadcrumb is visible in testing page")
    public void isBreadcrumbVisibleInTestingPage() throws IOException, URISyntaxException {
        //Given I am logged in as tester & I navigate to the vehicle search page
        VehicleSearchPage vehicleSearchPage = pageNavigator.gotoVehicleSearchPage(tester);

         //Then the breadcrumbNames are displayed in breadcrumb top navigation panel
        assertThat(vehicleSearchPage, hasBreadcrumbElements(vehicleSearchPage.getPageHeader()));
    }

    @Test(groups = {"Regression"}, description = "Breadcrumb is visible in testing page")
    public void isBreadcrumbVisibleInTestingFindVehiclePage() throws IOException, URISyntaxException {
        //Given I am logged in as tester & I navigate to the vehicle search page
        //When I search random vehicle and I navigate to find vehicle page
        VehicleSearchPage vehicleSearchPage = pageNavigator.gotoVehicleSearchPage(tester)
                                                           .searchVehicle(vehicle);

        //Then the breadcrumbNames are displayed in breadcrumb top navigation panel
        assertThat(vehicleSearchPage, hasBreadcrumbElements(vehicleSearchPage.getPageHeader()));
    }

    private void isJasperAsyncEnabled() throws IOException {
        FeaturesService service = new FeaturesService();
        if (!service.getToggleValue("jasper.async")) {
            throw new SkipException("Jasper Async not Enabled");
        }
    }


}
