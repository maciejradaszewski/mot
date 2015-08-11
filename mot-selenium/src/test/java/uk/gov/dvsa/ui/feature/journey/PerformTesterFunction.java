package uk.gov.dvsa.ui.feature.journey;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.MotTest;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.ChangeDetailsPage;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.PerformanceDashBoardPage;
import uk.gov.dvsa.ui.pages.ProfilePage;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static org.hamcrest.core.IsEqual.equalTo;

public class PerformTesterFunction extends BaseTest {
    private User tester;
    private Site testSite;
    private AeDetails aeDetails;
    private Vehicle vehicle;

    @BeforeMethod(alwaysRun = true)
    private void setUp() throws IOException {
        aeDetails = aeData.createNewAe("My_Test_AE", 100);
        testSite = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        tester = userData.createTester(testSite.getId());
        vehicle = vehicleData.getNewVehicle(tester);
    }

    @Test (groups = {"BVT"})
    public void editDetailsWithoutNeedingApproval() throws IOException {
        String newEmail = "email@domaingreat.com";
        String postCode = "BS33 5TT";

        //Given I'm on the ChangeDetails page
        ChangeDetailsPage changeDetailsPage = pageNavigator.gotoChangeDetailsPage(tester);

        //When I edit my postcode and email details
        changeDetailsPage.editPostCode(postCode).editEmailAndConfirmEmail(newEmail, newEmail);

        //Then I should be able to save with entering my pin
        ProfilePage profilePage = changeDetailsPage.update();

       //And my details should be changed
        assertThat(profilePage.verifyPostCodeIsChanged(postCode), is(true));
        assertThat(profilePage.verifyEmailIsChanged(newEmail), is(true));
    }

    @Test (groups = {"BVT", "Regression"})
    public void viewPerformanceDashboard() throws IOException {
        //Given I have done only 1 mot test
        motApi.createTest(tester, testSite.getId(),
                vehicleData.getNewVehicle(tester), TestOutcome.PASSED, 14000, DateTime.now());

        //When I navigate to my performance dashboard page
        PerformanceDashBoardPage performanceDashBoardPage = pageNavigator.gotoPerformanceDashboardPage(tester);

        //Then I should see my test conducted is 1
        assertThat(performanceDashBoardPage.getTestConductedText(), equalTo("1"));

        //And passed test should be 1
        assertThat(performanceDashBoardPage.getPassedTestText(), equalTo("1"));
    }

    @Test (groups = {"BVT", "Regression"})
    public void verifyTheCorrectAeAndVtsIsDisplayed() throws IOException {
        //Given I am registered to My Test AE

        //And I am tester at Test Site

        //When I view my homepage
        HomePage homePage = pageNavigator.gotoHomePage(tester);

        //Then my AE should be My Test AE
        assertThat(homePage.getAeName(), equalTo( aeDetails.getAeName()));

        //And my Vts should be Test Site
        assertThat(homePage.getSiteName(), equalTo(testSite.getSiteNameAndNumberInHomePageFormat()));
    }

    @Test (groups = {"BVT", "Regression"})
    public void myRoleIsDisplayedAsTester() throws IOException {
        //Given I am on my homepage
        HomePage homePage = pageNavigator.gotoHomePage(tester);

        //I expect to see my role displayed
        assertThat(homePage.getRole(), equalTo("Tester".toUpperCase()));
    }
}
