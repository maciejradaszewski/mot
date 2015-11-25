package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.mot.duplicatereplacementcertificates.*;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalToIgnoringCase;

public class DuplicateReplacementCertificate {

    private PageNavigator pageNavigator = null;
    private boolean declarationSuccessful = false;

    private static final String DECLARATION_STATEMENT = "I confirm that this MOT transaction has been conducted in accordance with " +
            "the conditions of authorisation which includes compliance with the MOT testing guide, the requirements for " +
            "authorisation, the appropriate MOT Inspection Manual and any other instructions issued by DVSA.";

    public DuplicateReplacementCertificate(PageNavigator pageNavigator) { this.pageNavigator = pageNavigator; }

    public boolean isDeclarationStatementDisplayed() {
        return declarationSuccessful;
    }

    public void createReplacementCertificate(User tester, Vehicle vehicle) throws IOException, URISyntaxException {
        DuplicateReplacementCertificateTestHistoryPage duplicateReplacementCertificateTestHistoryPage = pageNavigator.gotoDuplicateReplacementCertificateTestHistoryPage(tester, vehicle);
        ReplacementCertificateUpdatePage replacementCertificateUpdatePage = duplicateReplacementCertificateTestHistoryPage.clickFirstEditButton();
        replacementCertificateUpdatePage.submitNoOdometerOption();

        ReplacementCertificateReviewPage replacementCertificateReviewPage = replacementCertificateUpdatePage.reviewChangesButton();

        if (replacementCertificateReviewPage.isDeclarationTextDisplayed()) {
            assertThat(replacementCertificateReviewPage.getDeclarationText(), equalToIgnoringCase(DECLARATION_STATEMENT));
            declarationSuccessful = true;
        }
    }
}
