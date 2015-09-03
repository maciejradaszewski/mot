package uk.gov.dvsa.domain.navigation;

import org.joda.time.DateTime;
import org.openqa.selenium.Cookie;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.service.CookieService;
import uk.gov.dvsa.framework.config.Configurator;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.*;
import uk.gov.dvsa.ui.pages.accountclaim.AccountClaimPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AeSlotsUsagePage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AedmAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AuthorisedExaminerTestLogPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.FinanceAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.cpms.GenerateReportPage;
import uk.gov.dvsa.ui.pages.helpdesk.HelpDeskUserProfilePage;
import uk.gov.dvsa.ui.pages.mot.*;
import uk.gov.dvsa.ui.pages.mot.retest.ConfirmVehicleRetestPage;
import uk.gov.dvsa.ui.pages.mot.retest.ReTestResultsEntryPage;
import uk.gov.dvsa.ui.pages.specialnotices.SpecialNoticeCreationPage;
import uk.gov.dvsa.ui.pages.specialnotices.SpecialNoticePage;
import uk.gov.dvsa.ui.pages.vehicleinformation.VehicleInformationSearchPage;
import uk.gov.dvsa.ui.pages.vts.ChangeTestingFacilitiesPage;
import uk.gov.dvsa.ui.pages.vts.VtsChangeContactDetailsPage;

import java.io.IOException;
import java.net.URISyntaxException;

public class PageNavigator {
    private MotAppDriver driver;
    private String motTestPath;

    public void setDriver(MotAppDriver driver) {
        this.driver = driver;
    }

    private Cookie getCookieForUser(User user) throws IOException {
        return CookieService.generateOpenAmLoginCookie(user);
    }

    public VehicleSearchPage gotoVehicleSearchPage(User user) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);
        return new VehicleSearchPage(driver);
    }

    public TestResultsEntryPage gotoTestResultsEntryPage(User user, Vehicle vehicle) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);

        VehicleSearchPage vehicleSearchPage = PageLocator.getVehicleSearchPage(driver).searchVehicle(vehicle);
        StartTestConfirmationPage testConfirmationPage = vehicleSearchPage.selectVehicleForTest();
        TestOptionsPage testOptionsPage =  testConfirmationPage.clickStartMotTest();

        driver.navigateToPath(testOptionsPage.getMotTestPath());

        return new TestResultsEntryPage(driver);
    }

    public ReTestResultsEntryPage gotoReTestResultsEntryPage(User user, Vehicle vehicle) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);

        ConfirmVehicleRetestPage vehicleRetestPage = searchForVehicleForRetest(vehicle);
        driver.navigateToPath(vehicleRetestPage.startRetest().getMotTestPath());

        return new ReTestResultsEntryPage(driver);
    }

    private ConfirmVehicleRetestPage searchForVehicleForRetest(Vehicle vehicle) throws URISyntaxException {
        VehicleSearchPage vehicleSearchPage = PageLocator.getVehicleSearchPage(driver).searchVehicle(vehicle);
        vehicleSearchPage.selectVehicleForRetest();

        return new ConfirmVehicleRetestPage(driver);

    }

    public ConfirmVehicleRetestPage gotoVehicleRetestPage(User user, Vehicle vehicle) throws IOException, URISyntaxException {
        gotoVehicleSearchPage(user).searchVehicle(vehicle).selectVehicleForRetest();
        return new ConfirmVehicleRetestPage(driver);
    }

    public ContingencyTestEntryPage gotoContingencyTestEntryPage(User user) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, ContingencyTestEntryPage.path);

        return new ContingencyTestEntryPage(driver);
    }

    public ReTestResultsEntryPage gotoContigencyReTestResultsEntryPage(User user, String contingencyCode, Vehicle vehicle) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, ContingencyTestEntryPage.path);

        ContingencyTestEntryPage testEntryPage = PageLocator.getContingencyTestEntryPage(driver);
        testEntryPage.fillContingencyTestFormAndConfirm(contingencyCode, DateTime.now());
        ConfirmVehicleRetestPage vehicleRetestPage = searchForVehicleForRetest(vehicle);
        vehicleRetestPage.startContigencyRetest();

        return new ReTestResultsEntryPage(driver);
    }

    public CreateAePage gotoCreateAePage(User user) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, CreateAePage.path);

        return new CreateAePage(driver);
    }

    public ChangeDetailsPage gotoChangeDetailsPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, ChangeDetailsPage.path);

        return new ChangeDetailsPage(driver);
    }

    public VtsChangeContactDetailsPage gotoVtsChangeContactDetailsPage(User user, String siteId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(VtsChangeContactDetailsPage.PATH,siteId));

        return new VtsChangeContactDetailsPage(driver);
    }

    public void signOutAndGoToLoginPage() {
        driver.manage().deleteAllCookies();
        driver.setBaseUrl(Configurator.baseUrl());
        driver.loadBaseUrl();
    }

    public PerformanceDashBoardPage gotoPerformanceDashboardPage(User tester) throws IOException {
        injectOpenAmCookieAndNavigateToPath(tester, PerformanceDashBoardPage.path);

        return new PerformanceDashBoardPage(driver);
    }

    private void injectOpenAmCookieAndNavigateToPath(User user, String path) throws IOException {
        driver.setUser(user);
        driver.manage().addCookie(getCookieForUser(user));
        driver.navigateToPath(path);
    }

    public HomePage gotoHomePage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, HomePage.path);

        return new HomePage(driver);
    }

    public ProfilePage gotoProfilePage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, ProfilePage.path);

        return new ProfilePage(driver);
    }

    public AuthorisedExaminerViewPage goToAuthorisedExaminerPage(User user, String path, String aeId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(path, aeId));

        return new AedmAuthorisedExaminerViewPage(driver);
    }

    public AuthorisedExaminerViewPage goToAuthorisedExaminerPage(User user, String aeId)
            throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(AuthorisedExaminerViewPage.PATH, aeId));

        return new AedmAuthorisedExaminerViewPage(driver);
    }
    
    public FinanceAuthorisedExaminerViewPage goToFinanceAuthorisedExaminerViewPage(User user, String path, String aeId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(path, aeId));
        return new FinanceAuthorisedExaminerViewPage(driver);
    }

    public AuthorisedExaminerTestLogPage gotoAETestLogPage(User user, String aeId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(AuthorisedExaminerTestLogPage.PATH, aeId));

        return new AuthorisedExaminerTestLogPage(driver);
    }

    public TesterTestLogPage gotoTesterTestLogPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(TesterTestLogPage.PATH));

        return new TesterTestLogPage(driver);
    }

    public AeSlotsUsagePage gotoAeSlotsUsagePage(User user, String aeId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(AeSlotsUsagePage.PATH, aeId));

        return new AeSlotsUsagePage(driver);
    }

    public AccountClaimPage gotoAccountClaimPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, AccountClaimPage.PATH);

        return new AccountClaimPage(driver);
    }

    public SpecialNoticeCreationPage goToSpecialNoticeCreationPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, SpecialNoticeCreationPage.PATH);

        return new SpecialNoticeCreationPage(driver);
    }

    public SpecialNoticePage goToSpecialNoticesPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, SpecialNoticePage.PATH);

        return new SpecialNoticePage(driver);
    }
    
    public GenerateReportPage goToGenerateReportPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, GenerateReportPage.PATH);
        return new GenerateReportPage(driver);
        
    }

    public VehicleInformationSearchPage goToVehicleInformationSearchPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleInformationSearchPage.PATH);

        return new VehicleInformationSearchPage(driver);
    }

    public HelpDeskUserProfilePage goToUserHelpDeskProfilePage(User user, String profileId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(HelpDeskUserProfilePage.PATH, profileId));

        return new HelpDeskUserProfilePage(driver);
    }

    public DuplicateReplacementCertificatePage gotoDuplicateCertificatePage(User user, Vehicle vehicle) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format("/replacement-certificate-vehicle-search?registration=%s&vin=%s",
                vehicle.getRegistrationNumber(), vehicle.getVin()));

        new VehicleSearchPage(driver).searchVehicle(vehicle).selectVehicle();

        return new DuplicateReplacementCertificatePage(driver);
    }

    public ChangeTestingFacilitiesPage goToChangeTestingFacilitiesPage(User aoUser, String siteId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(aoUser, String.format(ChangeTestingFacilitiesPage.PATH, siteId) );
        return new ChangeTestingFacilitiesPage(driver);
    }

}
