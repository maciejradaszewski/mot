package uk.gov.dvsa.ui.feature.journey.mot;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeClass;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.MotTest;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.DslTest;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class ReplacementCertificatesTest extends DslTest {

    private Site site;

    @BeforeClass(alwaysRun = true)
    private void setUp() throws IOException {
        site = siteData.createSite();
    }

    @Test(groups = {"BVT"}, dataProvider = "usersWhoCanPrintCertificate")
    public void issuedByUserRoleWithPrintCertificatePermission(User user) throws IOException, URISyntaxException {

        //Given I have a PASSED Mot
        User tester = userData.createTester(site.getId());
        Vehicle vehicle = vehicleData.getNewVehicle(tester);
        MotTest motTest = motApi.createTest(userData.createTester(site.getId()), site.getId(), vehicle,
                TestOutcome.PASSED, 123456, DateTime.now());

        //When I attempt to reprint a duplicate certificate
        motUI.certificate.printReplacementPage(user, vehicle, motTest.getMotTestNumber());

        //Then I should see the option to print
        assertThat(motUI.certificate.isReprintButtonDisplayed(), is(true));
    }

    @Test(groups = {"BVT"})
    public void editedAndIssuedByDvsaUser() throws IOException, URISyntaxException {

        //Given I have a PASSED Mot
        User tester = userData.createTester(site.getId());
        Vehicle vehicle = vehicleData.getNewVehicle(tester);
        MotTest motTest = motApi.createPassedTestForVehicle(tester, site.getId(), vehicle);

        //When I attempt to reprint a duplicate certificate
        motUI.certificate.updateCertificate(userData.createAreaOfficeOne("Ao1"), vehicle,
                motTest.getMotTestNumber()).setOdometerToNull();

        //That the edit is successful and Print button is displayed
        assertThat(motUI.certificate.isReprintButtonDisplayed(), is(true));
    }

    @Test(groups = {"BVT"})
    public void odometerCannotBeEditedAfter7DaysOfFromIssueDate() throws IOException, URISyntaxException {

        //Given I conducted an mot test 8 days ago as a tester
        User tester = userData.createTester(site.getId());
        Vehicle vehicle = vehicleData.getNewVehicle(tester);
        MotTest motTest = motApi.createTest(tester, site.getId(), vehicle,
                TestOutcome.PASSED, 12345, DateTime.now().minusDays(8));

        //When I attempt to edit a certificate
        motUI.certificate.updateCertificate(tester, vehicle, motTest.getMotTestNumber());

        //Then I should be denied the option to edit
       assertThat(motUI.certificate.isEditButtonDisplayed(), is(false));
    }

    @Test(groups = {"BVT"})
    public void testVeCanReprintCertificate() throws IOException, URISyntaxException {

        //Given a Site has an mot test done
        User tester = userData.createTester(site.getId());
        MotTest motTest = motApi.createPassedTest(tester, site.getId());

        //When I view the test as a Vehicle Examiner
        motUI.certificate.viewSummaryAsVehicleExaminer(
                userData.createVehicleExaminer("Veprint", false), site.getSiteNumber(), motTest.getMotTestNumber());

        //Then I should see the option to print certificate
        assertThat(motUI.certificate.isReprintButtonDisplayed(), is(true));
    }

    @Test(testName = "2fa", groups = {"BVT", "Regression"})
    public void pinBoxNotShownWhenTwoFactorUserEditCertificate() throws IOException, URISyntaxException {

        //Given I create a test as a 2FA user
        int siteId = siteData.createSite().getId();
        User twoFactorTester = userData.createTester(siteId);
        motUI.authentication.registerAndSignInTwoFactorUser(twoFactorTester);

        Vehicle vehicle = vehicleData.getNewVehicle(twoFactorTester);
        MotTest test = motApi.createTest(twoFactorTester, siteId, vehicle, TestOutcome.PASSED, 123456, DateTime.now());

        //When I review the updated Certificate
        motUI.certificate.updateAndReviewCeritficate(twoFactorTester, vehicle, test.getMotTestNumber());

        //Then I should NOT see the PIN Box on the Review Page
        assertThat("Pin Box is not Displayed", motUI.certificate.isPinBoxDisplayed(), is(false));
    }

    @Test(groups = {"BVT", "Regression"})
    public void pinBoxShownWhenNonTwoFactorUserEditCertificate() throws IOException, URISyntaxException {

        //Given I create a test as a non 2fa tester
        int siteId = siteData.createSite().getId();
        User tester = userData.createTester(siteId);

        Vehicle vehicle = vehicleData.getNewVehicle(tester);
        String testId = motApi.createTest(tester, siteId, vehicle, TestOutcome.PASSED, 123456, DateTime.now()).getMotTestNumber();

        //When I review the updated Certificate
        motUI.certificate.updateAndReviewCeritficate(tester, vehicle, testId);

        //Then I should see the PIN Box on the Review Page
        assertThat("Pin Box is Displayed", motUI.certificate.isPinBoxDisplayed(), is(true));
    }

    @DataProvider(name = "usersWhoCanPrintCertificate")
    public Object[][] usersWhoCanPrintCertificate() throws IOException {
        return new Object[][]
                {
                        {userData.createCustomerServiceOfficer(false)},
                        {userData.createDvlaOfficer("DVLA")}
                };
    }
}
