package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.VehicleSearchPage;
import uk.gov.dvsa.ui.pages.mot.MotTestHistoryPage;
import uk.gov.dvsa.ui.pages.mot.MotTestSearchPage;
import uk.gov.dvsa.ui.pages.mot.EnforcementTestSummaryPage;
import uk.gov.dvsa.ui.pages.mot.certificates.*;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalToIgnoringCase;

public class Certificate {

    private PageNavigator pageNavigator;
    DuplicateReplacementCertificateTestHistoryPage certificateTestHistoryPage;
    MotTestCertificatePage certificatePage;
    ReplacementCertificateUpdatePage updatePage;
    ReplacementCertificateReviewPage reviewPage;
    CertificateEditUpdatePage editSuccessfulPage;
    private boolean isPrintButtonDisplayed;
    private boolean declarationSuccessful = false;

    private static final String DECLARATION_STATEMENT = "I confirm that this MOT transaction has been conducted in accordance with " +
            "the conditions of authorisation which includes compliance with the MOT testing guide, the requirements for " +
            "authorisation, the appropriate MOT Inspection Manual and any other instructions issued by DVSA.";

    public Certificate(PageNavigator pageNavigator) { this.pageNavigator = pageNavigator; }

    public boolean isDeclarationStatementDisplayed() {
        return declarationSuccessful;
    }

    public void createReplacementCertificate(User tester, Vehicle vehicle) throws IOException, URISyntaxException {
        DuplicateReplacementCertificateTestHistoryPage duplicateReplacementCertificateTestHistoryPage = pageNavigator.gotoDuplicateReplacementCertificateTestHistoryPage(tester, vehicle);
        ReplacementCertificateUpdatePage replacementCertificateUpdatePage = duplicateReplacementCertificateTestHistoryPage.clickFirstEditButton();
        replacementCertificateUpdatePage.submitNoOdometerOption();

        ReplacementCertificateReviewPage replacementCertificateReviewPage =
                replacementCertificateUpdatePage.reviewChangesButton(ReplacementCertificateReviewPage.class);

        if (replacementCertificateReviewPage.isDeclarationTextDisplayed()) {
            assertThat(replacementCertificateReviewPage.getDeclarationText(), equalToIgnoringCase(DECLARATION_STATEMENT));
            declarationSuccessful = true;
        }
    }

    public void printReplacementPage(User user, Vehicle vehicle, String motTestNumber) throws IOException, URISyntaxException {
        pageNavigator.navigateToPage(user, VehicleSearchPage.REPLACEMENT_PATH, VehicleSearchPage.class).searchVehicle(vehicle).selectVehicle();
        certificateTestHistoryPage = new DuplicateReplacementCertificateTestHistoryPage(pageNavigator.getDriver());

        certificatePage = certificateTestHistoryPage.viewTest(motTestNumber, MotTestCertificatePage.class);

        isPrintButtonDisplayed = certificatePage.isReprintButtonDisplayed();
    }

    private void updateCertificatePage(User user, Vehicle vehicle, String motTestNumber) throws IOException, URISyntaxException {
        pageNavigator.navigateToPage(user, VehicleSearchPage.REPLACEMENT_PATH, VehicleSearchPage.class)
                .searchVehicle(vehicle).selectVehicle();
        certificateTestHistoryPage = new DuplicateReplacementCertificateTestHistoryPage(pageNavigator.getDriver());

        updatePage = certificateTestHistoryPage.editTest(motTestNumber, ReplacementCertificateUpdatePage.class);
    }

    public boolean isReprintButtonDisplayed() {
        return isPrintButtonDisplayed;
    }

    public void updateOdometer() {
        updatePage.submitNoOdometerOption();
        updatePage.reviewChangesButton(ReplacementCertificateReviewPage.class);
    }

    public Certificate updateCertificate(User user, Vehicle vehicle, String motTestNumber) throws IOException, URISyntaxException {
        updateCertificatePage(user, vehicle, motTestNumber);
        return this;
    }

    public Certificate setOdometerToNull(){
        updateOdometer();
        reviewPage = new ReplacementCertificateReviewPage(pageNavigator.getDriver());
        editSuccessfulPage = reviewPage.confirmAndPrint(CertificateEditUpdatePage.class);

        isPrintButtonDisplayed = editSuccessfulPage.isPrintButtonDisplayed();

        return this;
    }

    public boolean isEditButtonDisplayed() {
        return updatePage.isEditOdometerButtonDisplayed();
    }

    public void viewSummaryAsVehicleExaminer(User user, String siteNumber, String testNumber)
            throws IOException, URISyntaxException {
        MotTestSearchPage testSearchPage = pageNavigator
                .navigateToPage(user, MotTestSearchPage.PATH, MotTestSearchPage.class);

        MotTestHistoryPage testHistoryPage = testSearchPage.fillSearchValue(siteNumber)
                .clickSearchButton(MotTestHistoryPage.class);

        EnforcementTestSummaryPage summaryPage =
                testHistoryPage.selectMotTestFromTableById(testNumber, EnforcementTestSummaryPage.class);
        isPrintButtonDisplayed = summaryPage.printCertificateButtonExists(testNumber);
    }
}
