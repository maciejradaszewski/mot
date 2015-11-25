package uk.gov.dvsa.module;

import org.joda.time.DateTime;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.VehicleSearchPage;
import uk.gov.dvsa.ui.pages.mot.*;
import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalToIgnoringCase;

public class ContingencyTest {

    private PageNavigator pageNavigator = null;
    private boolean declarationSuccessful = false;
    private final String contingencyCode = "12345A";

    private static final String DECLARATION_STATEMENT = "I confirm that this MOT transaction has been conducted in accordance with " +
            "the conditions of authorisation which includes compliance with the MOT testing guide, the requirements for " +
            "authorisation, the appropriate MOT Inspection Manual and any other instructions issued by DVSA.";

    public ContingencyTest(PageNavigator pageNavigator) { this.pageNavigator = pageNavigator; }

    public void conductContingencyTest(User tester, Vehicle vehicle) throws IOException, URISyntaxException {

        ContingencyTestEntryPage contingencyTestEntryPage = pageNavigator.gotoContingencyTestEntryPage(tester);
        VehicleSearchPage vehicleSearchPage = contingencyTestEntryPage.fillContingencyTestFormAndConfirm(contingencyCode, DateTime.now());

        StartTestConfirmationPage startTestConfirmationPage = vehicleSearchPage.searchVehicle(vehicle).selectVehicleForTest();

        TestResultsEntryPage testResultsEntryPage = startTestConfirmationPage.clickStartMotTestWhenConductingContingencyTest();
        testResultsEntryPage.completeTestDetailsWithPassValues();
        TestSummaryPage testSummaryPage = testResultsEntryPage.clickReviewTestButton();

        if (testSummaryPage.isDeclarationTextDisplayed()) {
            assertThat(testSummaryPage.getDeclarationText(), equalToIgnoringCase(DECLARATION_STATEMENT));
            declarationSuccessful = true;
        }
        TestCompletePage testCompletePage = testSummaryPage.finishTestAndPrint();
    }

    public boolean isDeclarationStatementDisplayed() {
        return declarationSuccessful;
    }
}
