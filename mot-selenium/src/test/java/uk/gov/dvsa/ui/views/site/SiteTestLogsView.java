package uk.gov.dvsa.ui.views.site;

import org.joda.time.DateTime;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.DateRange;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.helper.enums.DateRangeFilter;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.exception.PageInstanceNotFoundException;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class SiteTestLogsView extends BaseTest {

    Site testSite;
    User tester;

    @DataProvider(name = "users")
    public Object[][] permittedUsers() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        testSite = siteData.createNewSite(aeDetails.getId(), "My_Site");
        tester = userData.createTester(testSite.getId());

        return new Object[][]{
                {userData.createAedm(aeDetails.getId(), "TestAe", false)},
                {userData.createSiteAdmin(testSite.getId(), false)},
                {userData.createAreaOfficeOne("permittedAE")},
                {userData.createVehicleExaminer("permittedVE", false)}
        };
    }

    @Test(groups = {"BVT"}, dataProvider = "users")
    public void permittedUserCanViewTestLogs(User user) throws IOException {

        //Given I performed 1 MOT  test Last week
        motApi.createTest(tester, testSite.getId(), vehicleData.getNewVehicle(user), TestOutcome.PASSED, 123456, DateTime.now().minusWeeks(1));

        //When I go to the Site Log page as <permitted user>
        motUI.testLog.siteLogPage(user, String.valueOf(testSite.getId()));

        //Then I am able to view the Test logs, with *last week's worth (Mon to Sun) as default View
        assertThat(motUI.testLog.isDisplayed(), is(true));
        assertThat("Expected Default View to be " + DateRangeFilter.LAST_WEEK , motUI.testLog.isSelected(DateRangeFilter.LAST_WEEK), is(true));

        //And I can go back to the VTS Page
        motUI.testLog.home();
    }

    @Test(groups = {"BVT", "Regression"}, expectedExceptions = PageInstanceNotFoundException.class)
    public void nonPermittedUserCannotViewTestLogs() throws IOException {

        //Given I am a SchemeUser
        User schemeUser = userData.createSchemeUser(false);

        //When I attempt to view the VTS Test Logs
        motUI.testLog.siteLogPage(schemeUser, String.valueOf(siteData.createSite().getId()));

        //Then I am not able to view the Test log
        assertThat(motUI.testLog.isDisplayed(), is(false));

    }
    
    @Test(groups = {"BVT", "Regression"})
    public void permittedUserCanViewCustomDateRange() throws IOException {

        //Given I performed 2 Mot tests 2 months today, on the 2nd and 10th
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        Site testSite = siteData.createNewSite(aeDetails.getId(), "My_Site");
        User tester = userData.createTester(testSite.getId());

        DateRange date_2nd = new DateRange(02, DateTime.now().getMonthOfYear() - 2, DateTime.now().getYear());
        DateRange date_30th = new DateRange(30,  DateTime.now().getMonthOfYear() - 2, DateTime.now().getYear());

        motApi.createTest(tester, testSite.getId(), vehicleData.getNewVehicle(tester), TestOutcome.PASSED, 123456,
                DateTime.now().withDate(date_2nd.getIntYear(), date_2nd.getIntMonth(), date_2nd.getIntDay()));

        motApi.createTest(tester, testSite.getId(), vehicleData.getNewVehicle(tester), TestOutcome.PASSED, 123456,
                DateTime.now().withDate(date_30th.getIntYear(), date_30th.getIntMonth(), date_30th.getIntDay()));


        // When I go to the VTS Test Log page as <permitted user>
        motUI.testLog.siteLogPage(userData.createAreaOfficeOne("AreaOfficer"), String.valueOf(testSite.getId()));

        //When I search with a date range
        motUI.testLog.selectDateRange(date_2nd, date_30th);

        //Then the data table should be displayed containing only 2 Mot test
        assertThat("The Correct number of Mot is returned", motUI.testLog.getNumberOfMotTestInTable(), is(2));
    }
}
