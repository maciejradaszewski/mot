package uk.gov.dvsa.ui.user.journeys;

import org.joda.time.DateTime;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.*;
import uk.gov.dvsa.domain.service.ServiceLocator;
import uk.gov.dvsa.helper.TestDataHelper;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.*;

import java.io.IOException;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static org.hamcrest.core.IsEqual.equalTo;

public class PerformTesterFunction extends BaseTest {

    @Test (groups = {"BVT"})
    public void editDetailsWithoutNeedingApproval() throws IOException {
        String newEmail = "email@domaingreat.com";
        String postCode = "BS33 5TT";

        //Given I'm on the ChangeDetails page
        ChangeDetailsPage changeDetailsPage = pageNavigator().gotoChangeDetailsPage(TestDataHelper.createTester());

        //When I edit my postcode and email details
        changeDetailsPage.editPostCode(postCode).editEmailAndConfirmEmail(newEmail, newEmail);

        //Then I should be able to save with entering my pin
        ProfilePage profilePage = changeDetailsPage.update();

       //And my details should be changed
        assertThat(profilePage.verifyPostCodeIsChanged(postCode), is(true));
        assertThat(profilePage.verifyEmailIsChanged(newEmail), is(true));
    }

    @Test (groups = {"BVT"}, dataProvider = "createSiteAndTester")
    public void viewPerformanceDashboard(Site site, User tester, AeDetails aeDetails) throws IOException {
        //Given I have done only 1 mot test
        MotTest motTest = ServiceLocator.getMotTestService().createMotTest(tester, site.getId(),
                TestDataHelper.getNewVehicle(), TestOutcome.PASSED, 14000, DateTime.now());

        //When I navigate to my performance dashboard page
        PerformanceDashBoardPage performanceDashBoardPage = pageNavigator().gotoPerformanceDashboardPage(
                tester);

        //Then I should see my test conducted is 1
        assertThat(performanceDashBoardPage.getTestConductedText(), equalTo("1"));

        //And passed test should be 1
        assertThat(performanceDashBoardPage.getPassedTestText(), equalTo("1"));
    }

    @Test (groups = {"BVT"}, dataProvider = "createSiteAndTester")
    public void verifyTheCorrectAeAndVtsIsDisplayed(Site site, User tester, AeDetails aeDetails) throws IOException {
        //Given I am registered to My Test AE

        //And I am tester at Test Site

        //When I view my homepage
        HomePage homePage = pageNavigator().gotoHomePage(tester);

        //Then my AE should be My Test AE
        assertThat(homePage.getAeName(), equalTo(aeDetails.getAeName()));

        //And my Vts should be Test Site
        assertThat(homePage.getSiteName(), equalTo(site.getSiteNameAndNumberInHomePageFormat()));
    }


    @Test(groups = {"BVT","Regression"},
            description = "VM-4422 -Tester View of  Test logs", dataProvider = "createSiteAndTester")
    public void viewTestLogs( Site site, User tester, AeDetails aeDetails) throws IOException {

        //Given I perform an MOT test for my selected Tester
        ServiceLocator.getMotTestService().createMotTest(tester, site.getId(),
                TestDataHelper.getNewVehicle(), TestOutcome.PASSED, 14000, DateTime.now());

        //When I navigate to the Tester Test Logs page
        TesterTestLogPage testerTestLogPage =
                pageNavigator().gotoTesterTestLogPage(tester);


        //Then Today's Test count should be equal to number of tests performed today for that Tester
        assertThat(testerTestLogPage.getTodayCount(), equalTo("1"));

        //Then Last Week's Test count should be equal to number of test performed last week for that Tester
        assertThat(testerTestLogPage.getLastWeekCount(), equalTo("0"));

        //Then Last Week's Test count should be equal to number of test performed last month for that Tester
        assertThat(testerTestLogPage.getLastMonthCount(), equalTo("0"));

        //Then Last Year's Test count should be equal to to number of test performed this year for that Tester
        assertThat(testerTestLogPage.getLastYearCount(), equalTo("1"));

        //Then Default Message for No Records found is displayed?
        assertThat(testerTestLogPage.getNoRecordsFoundValidaitonMessage(),is(true));

        //The Validation message for invalid dates displayed?
        assertThat(testerTestLogPage.getInvalidDateMessage(),is(true));

    }

    @Test (groups = {"BVT"})
    public void myRoleIsDisplayedAsTester() throws IOException {
        //Given I am on my homepage
        HomePage homePage = pageNavigator().gotoHomePage(TestDataHelper.createTester());

        //I expect to see my role displayed
        assertThat(homePage.getRole(), equalTo("Tester".toUpperCase()));
    }

    @DataProvider(name = "createSiteAndTester")
    public Object[][] createAedmTester() throws IOException {
        AeDetails aeDetails = TestDataHelper.createAe();
        Site site = TestDataHelper.createSite(aeDetails.getId(), "My_TestSite");
        User tester = TestDataHelper.createTester(site.getId());
        return new Object[][]{{site, tester, aeDetails}};
    }
}
