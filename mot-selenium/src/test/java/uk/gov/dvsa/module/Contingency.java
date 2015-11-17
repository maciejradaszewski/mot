package uk.gov.dvsa.module;

import org.joda.time.DateTime;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.VehicleSearchPage;
import uk.gov.dvsa.ui.pages.mot.*;

import java.io.IOException;
import java.net.URISyntaxException;

public class Contingency {

    PageNavigator pageNavigator = null;
    private ContingencyTestEntryPage contingencyPage;
    private boolean successful = false;

    public Contingency(PageNavigator pageNavigator)
    {
        this.pageNavigator = pageNavigator;
    }

    public ContingencyTestEntryPage testPage(User user) throws IOException, URISyntaxException {
        return contingencyPage = pageNavigator.gotoContingencyTestEntryPage(user);
    }

    public void enterTest(String code, DateTime datetime, Vehicle vehicle) {
        VehicleSearchPage vehicleSearchPage = contingencyPage.fillContingencyTestFormAndConfirm(code, datetime);

        StartTestConfirmationPage startTestConfirmationPage =
                vehicleSearchPage.searchVehicle(vehicle).selectVehicleForTest();

        TestResultsEntryPage testResultsEntryPage =
                startTestConfirmationPage.clickStartMotTestWhenConductingContingencyTest();

        testResultsEntryPage.completeTestDetailsWithPassValues();

        TestSummaryPage testSummaryPage = testResultsEntryPage.clickReviewTestButton();

        TestCompletePage testCompletePage = testSummaryPage.finishTestAndPrint();

        successful = testCompletePage.verifyBackToHomeLinkDisplayed();
    }

    public boolean isTestSaveSuccessful() {
        return successful;
    }

    public void startTest() {
        contingencyPage.clickConfirmContingencyTestDetailsButton();
    }

    public String getValidationMessage(String elementPrefix){
        return contingencyPage.getValidationText(elementPrefix);
    }

    public int getValidationListSize() {
        return contingencyPage.getValidationList().size();
    }
}
