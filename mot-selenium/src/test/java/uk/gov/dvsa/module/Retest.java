package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.mot.retest.ReTestCompletePage;
import uk.gov.dvsa.ui.pages.mot.retest.ReTestResultsEntryPage;
import uk.gov.dvsa.ui.pages.mot.retest.ReTestSummaryPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class Retest {

    PageNavigator pageNavigator = null;
    private boolean successful = false;

    public Retest(PageNavigator pageNavigator)
    {
        this.pageNavigator = pageNavigator;
    }

    public void conductRetestPass(Vehicle vehicle, User tester) throws IOException, URISyntaxException {
        ReTestResultsEntryPage resultsEntryPage = pageNavigator.gotoReTestResultsEntryPage(tester, vehicle);
        resultsEntryPage.completeTestDetailsWithPassValues();

        ReTestSummaryPage summaryPage = resultsEntryPage.clickReviewTestButton();

        ReTestCompletePage testCompletePage = summaryPage.finishTestAndPrint();

        successful = testCompletePage.verifyBackToHomeDisplayed();
    }

    public void conductRetestFail(Vehicle vehicle, User tester) throws IOException, URISyntaxException {
        ReTestResultsEntryPage resultsEntryPage = pageNavigator.gotoReTestResultsEntryPage(tester, vehicle);
        resultsEntryPage.completeTestDetailsWithFailValues();

        ReTestSummaryPage summaryPage = resultsEntryPage.addDefaultRfrPrsAndManualAdvisory();

        ReTestCompletePage testCompletePage = summaryPage.finishTestAndPrint();

        successful = testCompletePage.isRefusalMessageDisplayed();
    }

    public void conductContingencyRetest(User tester, String contingencyCode, Vehicle vehicle) throws IOException, URISyntaxException {

        ReTestResultsEntryPage resultsEntryPage = pageNavigator.gotoContigencyReTestResultsEntryPage(tester, contingencyCode, vehicle);

        resultsEntryPage.completeTestDetailsWithPassValues();

        ReTestSummaryPage summaryPage = resultsEntryPage.clickReviewTestButton();

        ReTestCompletePage testCompletePage = summaryPage.finishTestAndPrint();

        successful = testCompletePage.verifyBackToHomeDisplayed();
    }

    public void verifyRetestIsSuccessful() {
        assertThat(successful, is(true));
    }

    public void gotoHomepageAs(User user) throws IOException {
        HomePage homePage = pageNavigator.gotoHomePage(user);

        successful = homePage.isRetestPreviousVehicleLinkPresent();
    }

    public void verifyRetestLinkNotPresent(){
        assertThat(successful, is(false));
    }
}
