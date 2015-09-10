package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.mot.MotTestCertificatesPage;
import uk.gov.dvsa.ui.pages.mot.TestCompletePage;
import uk.gov.dvsa.ui.pages.mot.TestResultsEntryPage;
import uk.gov.dvsa.ui.pages.mot.TestSummaryPage;

import java.io.IOException;
import java.net.URISyntaxException;

public class NormalTest {

    PageNavigator pageNavigator = null;
    public NormalTest(PageNavigator pageNavigator)
    {
        this.pageNavigator = pageNavigator;
    }

    public void conductTestPass(User tester, Vehicle vehicle) throws IOException, URISyntaxException {
        TestResultsEntryPage testResultsEntryPage = pageNavigator.gotoTestResultsEntryPage(tester,vehicle);
        testResultsEntryPage.completeTestDetailsWithPassValues();
        TestSummaryPage testSummaryPage = testResultsEntryPage.clickReviewTestButton();
        testSummaryPage.finishTestAndPrint();
    }

    public boolean isMotCertificateLinkDisplayed(){
        return new TestCompletePage(pageNavigator.getDriver()).isMotCertificateLinkPresent();
    }

    public boolean isPrintButtonDisplayed() {
        return new TestCompletePage(pageNavigator.getDriver()).isPrintDocumentButtonDisplayed();
    }

    public void certificatePage() {
        MotTestCertificatesPage certificatesPage=
                new TestCompletePage(pageNavigator.getDriver()).clickCertificateLink();
    }
}
