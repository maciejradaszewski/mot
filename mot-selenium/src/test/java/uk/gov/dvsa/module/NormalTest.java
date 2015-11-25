package uk.gov.dvsa.module;

import uk.gov.dvsa.data.SiteData;
import uk.gov.dvsa.data.UserData;
import uk.gov.dvsa.data.VehicleData;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.CancelTestReason;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.VehicleSearchPage;
import uk.gov.dvsa.ui.pages.mot.*;
import uk.gov.dvsa.domain.model.mot.ReasonForVehicleRefusal;
import uk.gov.dvsa.ui.pages.mot.createvehiclerecord.*;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalToIgnoringCase;

public class NormalTest {

    private PageNavigator pageNavigator = null;
    private TestResultsEntryPage testResultsEntryPage;
    private boolean declarationSuccessful = false;

    private static final String DECLARATION_STATEMENT = "I confirm that this MOT transaction has been conducted in accordance with " +
            "the conditions of authorisation which includes compliance with the MOT testing guide, the requirements for " +
            "authorisation, the appropriate MOT Inspection Manual and any other instructions issued by DVSA.";

    public NormalTest(PageNavigator pageNavigator) {
        this.pageNavigator = pageNavigator;
    }

    public void conductTestPass(User tester, Vehicle vehicle) throws IOException, URISyntaxException {
        TestResultsEntryPage testResultsEntryPage = pageNavigator.gotoTestResultsEntryPage(tester, vehicle);
        testResultsEntryPage.completeTestDetailsWithPassValues();
        TestSummaryPage testSummaryPage = testResultsEntryPage.clickReviewTestButton();

        if (testSummaryPage.isDeclarationTextDisplayed()) {
            assertThat(testSummaryPage.getDeclarationText(), equalToIgnoringCase(DECLARATION_STATEMENT));
            declarationSuccessful = true;
        }
        testSummaryPage.finishTestAndPrint();
    }

    public boolean isMotCertificateLinkDisplayed() {
        return new TestCompletePage(pageNavigator.getDriver()).isMotCertificateLinkPresent();
    }

    public boolean isPrintButtonDisplayed() {
        return new TestCompletePage(pageNavigator.getDriver()).isPrintDocumentButtonDisplayed();
    }

    public void certificatePage() {
        MotTestCertificatesPage certificatesPage =
                new TestCompletePage(pageNavigator.getDriver()).clickCertificateLink();
    }

    public boolean isDeclarationStatementDisplayed() {
        return declarationSuccessful;
    }

    public void cancelTestWithReason(CancelTestReason reason) {
        ReasonToCancelTestPage cancelTestPage = testResultsEntryPage.clickCancelTest();
        cancelTestPage.enterReason(reason);

        if (cancelTestPage.isDeclarationTextDisplayed()) {
            assertThat(cancelTestPage.getDeclarationText(), equalToIgnoringCase(DECLARATION_STATEMENT));
            declarationSuccessful = true;
        }
    }

    public void startTest() throws IOException, URISyntaxException {
        User tester = new UserData().createTester(new SiteData().createSite().getId());

        testResultsEntryPage = pageNavigator.gotoTestResultsEntryPage(
                tester, new VehicleData().getNewVehicle(tester));
    }

    public void changeVehicleDetails(User tester, Vehicle vehicle) throws IOException, URISyntaxException {
        VehicleSearchPage vehicleSearchPage = pageNavigator.gotoVehicleSearchPage(tester);
        StartTestConfirmationPage startTestConfirmationPage = vehicleSearchPage.searchVehicle(vehicle).selectVehicleForTest();
        VehicleDetailsChangedPage vehicleDetailsChangedPage = startTestConfirmationPage.changeVehicleDetailAndSubmit(vehicle);

        if (vehicleDetailsChangedPage.isDeclarationTextDisplayed()) {
            assertThat(vehicleDetailsChangedPage.getDeclarationText(), equalToIgnoringCase(DECLARATION_STATEMENT));
            declarationSuccessful = true;
        }
    }

    public void conductTrainingTest(User tester, Vehicle vehicle) throws IOException, URISyntaxException {
        TestResultsEntryPage testResultsEntryPage = pageNavigator.gotoTrainingTestResultsEntryPage(tester, vehicle);
        testResultsEntryPage.completeTestDetailsWithPassValues();
        TestSummaryPage testSummaryPage = testResultsEntryPage.clickReviewTestButton();

        declarationSuccessful = testSummaryPage.isDeclarationElementPresentInDom();
    }

    public void refuseToTestVehicle(User tester, Vehicle vehicle, ReasonForVehicleRefusal reason) throws IOException, URISyntaxException {
        RefuseToTestPage refuseToTestPage = pageNavigator.gotoRefuseToTestPage(tester, vehicle);
        refuseToTestPage.selectReason(reason);

        declarationSuccessful = refuseToTestPage.isDeclarationElementPresentInDom();
    }

    public void createNewVehicleRecord(User tester, Vehicle vehicle) throws IOException, URISyntaxException {

        CreateNewVehicleRecordIdentificationPage createNewVehicleRecordIdentificationPage = pageNavigator.gotoCreateNewVehicleRecordIdentificationPage(tester);
        createNewVehicleRecordIdentificationPage.enterDetails(vehicle);

        CreateNewVehicleRecordSpecificationPage createNewVehicleRecordSpecificationPage = createNewVehicleRecordIdentificationPage.submit();
        createNewVehicleRecordSpecificationPage.enterVehicleDetails(vehicle);

        CreateNewVehicleRecordConfirmPage createNewVehicleRecordConfirmPage = createNewVehicleRecordSpecificationPage.submit();

        if (createNewVehicleRecordConfirmPage.isDeclarationTextDisplayed()) {
            assertThat(createNewVehicleRecordConfirmPage.getDeclarationText(), equalToIgnoringCase(DECLARATION_STATEMENT));
            declarationSuccessful = true;
        }
    }
}
