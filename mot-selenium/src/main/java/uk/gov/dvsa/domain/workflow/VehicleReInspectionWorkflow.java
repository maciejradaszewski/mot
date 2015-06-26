package uk.gov.dvsa.domain.workflow;

import org.openqa.selenium.Cookie;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.Vehicle;
import uk.gov.dvsa.domain.service.CookieService;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.*;

import java.io.IOException;

public class VehicleReInspectionWorkflow extends BaseWorkflow {

    private MotAppDriver driver;

    public void setDriver(MotAppDriver driver) {
        this.driver = driver;
    }

    public TestSummaryPage searchFotMotTest(User user, String searchCategory, String searchValue, String motTestId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, MotTestSearchPage.path);
        PageLocator.getMotTestSearchPage(driver)
                .selectSearchCategory(searchCategory)
                .fillSearchValue(searchValue)
                .clickSearchButton();
        PageLocator.getMotTestHistoryPage(driver)
                .selectMotTestFromTableById(motTestId);

        return new TestSummaryPage(driver);
    }

    public EventsHistoryPage gotoEventsHistoryPage(User user, String siteId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(VehicleTestingStationPage.path, siteId));
        PageLocator.getVehicleTestingStationPage(driver)
                .clickOnViewHistoryLink();
        return new EventsHistoryPage(driver);
    }

    public VehicleTestingStationPage gotoVehicleTestingStationPage(User user, String siteId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(VehicleTestingStationPage.path, siteId));
        return new VehicleTestingStationPage(driver);
    }

    public TestCompletePage reinspectVehicle(String testType, int odometerValue, String siteId) {
        PageLocator.getTestSummaryPage(driver)
                .selectTestType(testType)
                .clickStartReinspectionButton();
        PageLocator.getTestResultsEntryPage(driver)
                .fillOdometerReadingAndSubmit(odometerValue)
                .clickReviewTest();
        PageLocator.getTestSummaryPage(driver)
                .fillSiteIdInput(siteId)
                .clickFinishButton();

        return new TestCompletePage(driver);
    }

    public AssessmentDetailsConfirmationPage compareResults(String score, String category) {
        PageLocator.getTestCompletePage(driver)
                .clickCompareResultsButton();
        PageLocator.getTestCompareResultsPage(driver)
                .selectServiceScore(score).selectServiceCategory(category).fillServiceJustificationInputWithDefaultValue()
                .selectParkingScore(score).selectParkingCategory(category).fillParkingJustificationInputWithDefaultValue()
                .fillFinalJustificationInputWithDefaultValue()
                .clickRecordAssessmentButton();

        return new AssessmentDetailsConfirmationPage(driver);
    }

    public TestOptionsPage startMotTestAsATester(User user, Vehicle vehicle) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);

        VehicleSearchPage vehicleSearchPage = PageLocator.getVehicleSearchPage(driver).searchVehicle(vehicle);
        StartTestConfirmationPage testConfirmationPage = vehicleSearchPage.selectVehicleFromTable();
        testConfirmationPage.clickStartMotTest();

        return new TestOptionsPage(driver);
    }

    public TestShortSummaryPage abortActiveTestOnVtsPage(String regNum) {
        PageLocator.getVehicleTestingStationPage(driver)
                .clickOnActiveTest(regNum)
                .clickAbortMotTestButton()
                .selectAbortedByVeReason()
                .clickAbortMotTestButton();

        return new TestShortSummaryPage(driver);
    }

    private void injectOpenAmCookieAndNavigateToPath(User user, String path) throws IOException {
        driver.manage().addCookie(getCookieForUser(user));
        driver.navigateToPath(path);
    }

    private Cookie getCookieForUser(User user) throws IOException {
        return CookieService.generateOpenAmLoginCookie(user);
    }
}