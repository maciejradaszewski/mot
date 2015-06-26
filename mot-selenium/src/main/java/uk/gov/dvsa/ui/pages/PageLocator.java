package uk.gov.dvsa.ui.pages;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public abstract class PageLocator {

    public static VehicleSearchPage getVehicleSearchPage(MotAppDriver driver) {
        return new VehicleSearchPage(driver);
    }

    public static VtsSearchForAUserPage getVtsSearchForAUserPage(MotAppDriver driver) {
        return new VtsSearchForAUserPage(driver);
    }

    public static BrakeTestConfigurationPage getBrakeTestConfigurationPage(MotAppDriver driver) {
        return new BrakeTestConfigurationPage(driver);
    }

    public static VtsChooseARolePage getVtsChooseARolePage(MotAppDriver driver) {
        return new VtsChooseARolePage(driver);
    }

    public static VtsSummaryAndConfirmationPage getVtsSummaryAndConfirmationPage(MotAppDriver driver) {
        return new VtsSummaryAndConfirmationPage(driver);
    }
    
    public static MotTestSearchPage getMotTestSearchPage(MotAppDriver driver) {
        return new MotTestSearchPage(driver);
    }

    public static MotTestHistoryPage getMotTestHistoryPage(MotAppDriver driver) {
        return new MotTestHistoryPage(driver);
    }

    public static TestSummaryPage getTestSummaryPage(MotAppDriver driver) {
        return new TestSummaryPage(driver);
    }

    public static TestResultsEntryPage getTestResultsEntryPage(MotAppDriver driver) {
        return new TestResultsEntryPage(driver);
    }

    public static TestCompareResultsPage getTestCompareResultsPage(MotAppDriver driver) {
        return new TestCompareResultsPage(driver);
    }

    public static TestCompletePage getTestCompletePage(MotAppDriver driver) {
        return new TestCompletePage(driver);
    }

    public static HomePage getHomePage(MotAppDriver driver) {
        return new HomePage(driver);
    }

    public static NotificationPage getNotificationPage(MotAppDriver driver) {
        return new NotificationPage(driver);
    }

    public static ContingencyTestEntryPage getContingencyTestEntryPage(MotAppDriver driver) {
        return new ContingencyTestEntryPage(driver);
    }

    public static VehicleTestingStationPage getVehicleTestingStationPage(MotAppDriver driver) {
        return new VehicleTestingStationPage(driver);
    }

    public static AuthorisedExaminerPage getAuthorisedExaminerPage(MotAppDriver driver) {
        return new AuthorisedExaminerPage(driver);
    }
}
