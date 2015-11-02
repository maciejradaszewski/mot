package uk.gov.dvsa.ui.feature.journey;

import org.joda.time.DateTime;
import org.testng.SkipException;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.service.FeaturesService;
import uk.gov.dvsa.helper.ConfigHelper;
import uk.gov.dvsa.ui.BaseTest;

import java.io.IOException;

import static org.hamcrest.core.Is.is;
import static org.hamcrest.MatcherAssert.assertThat;

public class MotTestCertificateForTradeRolesTests extends BaseTest {
    FeaturesService service = new FeaturesService();
    private Site testSite;
    private AeDetails aeDetails;
    private User tester;
    private User siteAdmin;
    private User siteManager;
    private Vehicle vehicle;

    @BeforeMethod(alwaysRun = true)
    public void setUp() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        testSite = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        tester = userData.createTester(testSite.getId());
        siteManager = userData.createSiteManager(testSite.getId(), false);
        siteAdmin = userData.createSiteAdmin(testSite.getId(), false);
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @Test(groups = {"Regression","BVT"})
    public void theSameVtsTradeRoleUserSeeCertificateSuccessfully() throws IOException {
        ConfigHelper.isJasperAsyncEnabled();
        motApi.createTest(tester, testSite.getId(), vehicle, TestOutcome.PASSED, 14000, DateTime.now());
        verifyVehicle(tester, "tester");
        verifyVehicle(siteManager, "manager");
        verifyVehicle(siteAdmin, "admin");
    }

    private void verifyVehicle(User user, String userRole) throws IOException {
        Boolean isVehicleCorrect = pageNavigator.gotoHomePage(user)
                .selectRandomVts()
                .clickOnMotTestRecentCertificatesLink()
                .isVehicleCorrect(vehicle.getMakeModel(), vehicle.getRegistrationNumber(), "Pass");
        assertThat("Data in "+userRole+" Test Recent Certificates Page incorrect", isVehicleCorrect, is(true));
    }
}