package uk.gov.dvsa.ui.feature.journey.mot;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.MotTest;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class ReplacementCertificatesTest extends BaseTest {

    private Site site;
    private Vehicle vehicle;
    private User tester;

    @BeforeMethod(alwaysRun = true)
    private void setUp() throws IOException {
        site = siteData.createSite();
        tester = userData.createTester(site.getId());
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @DataProvider(name = "usersWhoCanPrintCertificate")
    public Object[][] usersWhoCanPrintCertificate() throws IOException {
        return new Object[][]
                {
                        {userData.createCustomerServiceOfficer(false)},
                        {userData.createDvlaOfficer("DVLA")}
                };
    }

    @Test(groups = {"BVT", "Regression"}, dataProvider = "usersWhoCanPrintCertificate")
    public void issuedByUser(User user) throws IOException, URISyntaxException {

        //Given I have a PASSED Mot
        MotTest motTest = motApi.createTest(tester, site.getId(), vehicle, TestOutcome.PASSED, 123456, DateTime.now());

        //When I attempt to reprint a duplicate certificate
        motUI.certificate.printReplacementPage(user, vehicle, motTest.getMotTestNumber());

        //Then I should see the option to print
        assertThat(motUI.certificate.isReprintButtonDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression"})
    public void editedAndIssuedByDvsaUser() throws IOException, URISyntaxException {

        //Given I have a PASSED Mot
        MotTest motTest = motApi.createTest(tester, site.getId(), vehicle, TestOutcome.PASSED, 123456, DateTime.now());

        //When I attempt to reprint a duplicate certificate
        motUI.certificate.updateCertificate(userData.createAreaOfficeOne("Ao1"), vehicle, motTest.getMotTestNumber())
                .setOdometerToNull();

        //That the edit is successful and Print button is displayed
        assertThat(motUI.certificate.isReprintButtonDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression"})
    public void odometerCannotBeEditedAfter7DaysOfFromIssueDate() throws IOException, URISyntaxException {

        //Given I conducted an mot test 8 days ago
        MotTest motTest = motApi.createTest(tester, site.getId(), vehicle, TestOutcome.PASSED, 12345, DateTime.now().minusDays(8));

        //When I attempt to edit a certificate
        motUI.certificate.updateCertificate(tester, vehicle, motTest.getMotTestNumber());

        //Then I should be denied the option to edit
       assertThat(motUI.certificate.isEditButtonDisplayed(), is(false));
    }

    @Test(groups = {"BVT", "Regression"})
    public void testVeCanReprintCertificate() throws IOException, URISyntaxException {

        //Given a Site has an mot test done
        MotTest motTest = motApi.createTest(tester, site.getId(), vehicle, TestOutcome.PASSED, 12345, DateTime.now());

        //When I view the test as a Vehicle Examiner
        motUI.certificate.viewSummaryAsVehicleExaminer(
                userData.createVehicleExaminer("Veprint", false), site.getSiteNumber(), motTest.getMotTestNumber());

        //Then I should see the option to print certificate
        assertThat(motUI.certificate.isReprintButtonDisplayed(), is(true));
    }
}
