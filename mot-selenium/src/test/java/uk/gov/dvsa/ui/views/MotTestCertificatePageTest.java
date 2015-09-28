package uk.gov.dvsa.ui.views;

import org.joda.time.DateTime;
import org.testng.SkipException;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.service.FeaturesService;
import uk.gov.dvsa.helper.ExecutorServiceInitializer;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.mot.MotTestCertificatesPage;

import java.io.IOException;
import java.util.concurrent.Callable;

import static org.hamcrest.core.Is.is;
import static org.hamcrest.MatcherAssert.assertThat;

public class MotTestCertificatePageTest extends BaseTest {
    FeaturesService service = new FeaturesService();
    private Site site;
    private AeDetails aeDetails;

    @Test(groups = {"Regression"}, description = "VM-11876")
    public void PaginationButtonCheck() throws Exception {
        isJasperAsyncEnabled();
        int iterations = 21;
        int threadPool = 5;
        int terminationTimeout = 100;

        aeDetails = aeData.createAeWithDefaultValues();
        site = siteData.createNewSite(aeDetails.getId(), "TestSite");

        Callable<Void> task = new Callable<Void>() {
            @Override
            public Void call() throws Exception {
                User tester =  userData.createTester(site.getId());
                Vehicle vehicle = vehicleData.getNewVehicle(tester);
                motApi.createTest(tester, site.getId(), vehicle, TestOutcome.FAILED, 12345, DateTime.now());
                return null;
            }
        };

        //Generate 21 random tests (parallel)
        ExecutorServiceInitializer executorServiceInitializer = new ExecutorServiceInitializer();
        executorServiceInitializer.runInParallel(iterations, threadPool, terminationTimeout, task);

        //Go to Mot Test Certificates Page
        User tester = userData.createTester(site.getId());
        MotTestCertificatesPage motTestCertificatesPage = pageNavigator.gotoMotTestCertificatesPage(tester);

        //check if pagination button is there after 21 tests - supposed to be 
        assertThat("There supposed to be pagination button",
                motTestCertificatesPage.isPaginationButtonVisible(), is(true));
    }

    private void isJasperAsyncEnabled() throws IOException {
        if (!service.getToggleValue("jasper.async")) {
            throw new SkipException("Jasper Async not Enabled");
        }
    }
}
