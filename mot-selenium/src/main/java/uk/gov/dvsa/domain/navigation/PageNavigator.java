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
import uk.gov.dvsa.ui.pages.authorisedexaminer.*;
import uk.gov.dvsa.ui.pages.changedriverlicence.ChangeDrivingLicencePage;
import uk.gov.dvsa.ui.pages.cpms.GenerateReportPage;
import uk.gov.dvsa.ui.pages.dvsa.*;
import uk.gov.dvsa.ui.pages.helpdesk.HelpDeskUserProfilePage;
import uk.gov.dvsa.ui.pages.login.LoginPage;
import uk.gov.dvsa.ui.pages.mot.*;
import uk.gov.dvsa.ui.pages.mot.createvehiclerecord.CreateNewVehicleRecordIdentificationPage;
import uk.gov.dvsa.ui.pages.mot.duplicatereplacementcertificates.DuplicateReplacementCertificateTestHistoryPage;
import uk.gov.dvsa.ui.pages.mot.retest.ConfirmVehicleRetestPage;
import uk.gov.dvsa.ui.pages.mot.retest.ReTestResultsEntryPage;
import uk.gov.dvsa.ui.pages.profile.ProfilePage;
import uk.gov.dvsa.ui.pages.specialnotices.SpecialNoticeCreationPage;
import uk.gov.dvsa.ui.pages.specialnotices.SpecialNoticePage;
import uk.gov.dvsa.ui.pages.userregistration.CreateAnAccountPage;
import uk.gov.dvsa.ui.pages.vehicleinformation.VehicleInformationSearchPage;
import uk.gov.dvsa.ui.pages.vts.*;

import java.io.IOException;
import java.net.URISyntaxException;

public class PageNavigator {
    private MotAppDriver driver;

    public void setDriver(MotAppDriver driver) {
        this.driver = driver;
    }

    public MotAppDriver getDriver() {
        return driver;
    }

    public void navigateToPath(String path){
        driver.navigateToPath(path);
    }

    public <T extends Page> T navigateToPage(User user, String path, Class<T> clazz) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, path);
        return MotPageFactory.newPage(driver, clazz);
    }

    private Cookie getCookieForUser(User user) throws IOException {
        return CookieService.generateOpenAmLoginCookie(user);
    }

    public VehicleSearchPage gotoVehicleSearchPage(User user) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);
        return new VehicleSearchPage(driver);
    }

    public StartTestConfirmationPage goToStartTestConfimationPage(User user, Vehicle vehicle) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);

        VehicleSearchPage vehicleSearchPage = PageLocator.getVehicleSearchPage(driver).searchVehicle(vehicle);
        return vehicleSearchPage.selectVehicleForTest();
    }

    public TestResultsEntryPage gotoTestResultsEntryPage(User user, Vehicle vehicle) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);

        VehicleSearchPage vehicleSearchPage = PageLocator.getVehicleSearchPage(driver).searchVehicle(vehicle);
        StartTestConfirmationPage testConfirmationPage = vehicleSearchPage.selectVehicleForTest();
        TestOptionsPage testOptionsPage =  testConfirmationPage.clickStartMotTest();

        navigateToPath(testOptionsPage.getMotTestPath());

        return new TestResultsEntryPage(driver);
    }

    public ReTestResultsEntryPage gotoReTestResultsEntryPage(User user, Vehicle vehicle) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);

        ConfirmVehicleRetestPage vehicleRetestPage = searchForVehicleForRetest(vehicle);
        navigateToPath(vehicleRetestPage.startRetest().getMotTestPath());

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
        injectOpenAmCookieAndNavigateToPath(user, ContingencyTestEntryPage.PATH);



        return new ContingencyTestEntryPage(driver);
    }

    public ReTestResultsEntryPage gotoContigencyReTestResultsEntryPage(User user, String contingencyCode, Vehicle vehicle) throws URISyntaxException, IOException {
        injectOpenAmCookieAndNavigateToPath(user, ContingencyTestEntryPage.PATH);

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
        injectOpenAmCookieAndNavigateToPath(user, String.format(VtsChangeContactDetailsPage.PATH, siteId));

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
        navigateToPath(path);
    }

    public HomePage gotoHomePage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, HomePage.path);

        return new HomePage(driver);
    }

    public MotTestCertificatesPage gotoMotTestCertificatesPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, MotTestCertificatesPage.path);

        return new MotTestCertificatesPage(driver);
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

    public AuthorisedExaminerViewPage goToAreaOfficeAuthorisedExaminerPage(User user, String aeId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(AreaOfficerAuthorisedExaminerViewPage.PATH, aeId));
        return new AreaOfficerAuthorisedExaminerViewPage(driver);
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

    public UserSearchPage goToUserSearchPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, UserSearchPage.PATH);

        return new UserSearchPage(driver);
    }

    public AssociateASitePage goToAssociateASitePage(User user, String aeId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(AssociateASitePage.PATH, aeId));
        return new AssociateASitePage(driver);
    }

    public ChangeTestingFacilitiesPage goToChangeTestingFacilitiesPage(User aoUser, String siteId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(aoUser, String.format(ChangeTestingFacilitiesPage.PATH, siteId) );
        return new ChangeTestingFacilitiesPage(driver);
    }

    public CreateAnAccountPage goToCreateAnAccountPage() throws IOException {
        navigateToPath(CreateAnAccountPage.PATH);

        return new CreateAnAccountPage(driver);
    }

    public VehicleSearchPage goToTrainingTestVehicleSearchPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.TRAINING_TEST_PATH);
        return new VehicleSearchPage(driver);
    }

    public TestResultsEntryPage gotoTrainingTestResultsEntryPage(User user, Vehicle vehicle) throws IOException, URISyntaxException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.TRAINING_TEST_PATH);

        VehicleSearchPage vehicleSearchPage = new VehicleSearchPage(driver).searchVehicle(vehicle);
        StartTestConfirmationPage testConfirmationPage = vehicleSearchPage.selectVehicleForTest();
        TestOptionsPage testOptionsPage =  testConfirmationPage.clickStartMotTest();

        navigateToPath(testOptionsPage.getMotTestPath());

        return new TestResultsEntryPage(driver);
    }

    public VtsSearchForAVtsPage goToVtsSearchPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, VtsSearchForAVtsPage.path);

        return new VtsSearchForAVtsPage(driver);
    }

    public VehicleTestingStationPage goToVtsPage(User user, String vtsId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(VehicleTestingStationPage.path, vtsId));

        return new VehicleTestingStationPage(driver);
    }

    public LoginPage goToLoginPage() throws IOException {
        driver.loadBaseUrl();
        return new LoginPage(driver);
    }

    public EventsHistoryPage goToEventsHistoryPage(User user, int aeId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(EventsHistoryPage.AE_PATH, aeId));
        return new EventsHistoryPage(driver);
    }
    public TestCompletePage gotoTestCompletePage(User user, String motTestNumber) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(TestSummaryPage.PATH, motTestNumber));
        TestSummaryPage summaryPage = new TestSummaryPage(driver);
        summaryPage.finishTestAndPrint();

        return new TestCompletePage(driver);
    }

    public ChangePasswordFromProfilePage goToPasswordChangeFromProfilePage(User user) throws IOException{
        injectOpenAmCookieAndNavigateToPath(user, String.format(ChangePasswordFromProfilePage.PATH));
        return new ChangePasswordFromProfilePage(driver);
    }

    public ManageRolesPage goToManageRolesPageViaUserSearch(User loggedUser, User searchedUser) throws IOException {
        return goToUserSearchedProfilePageViaUserSearch(loggedUser, searchedUser).clickManageRolesLink();
    }

    public UserSearchProfilePage goToUserSearchedProfilePageViaUserSearch(User loggedUser, User searchedUser) throws IOException {
        return goToUserSearchPage(loggedUser).searchForUserByUsername(searchedUser.getUsername())
                .clickSearchButton(UserSearchResultsPage.class)
                .chooseUser(0);
    }

    public SiteSearchPage goToSiteSearchPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(SiteSearchPage.PATH));
        return new SiteSearchPage(driver);
    }

    public SiteTestLogPage gotoSiteTestLogPage(User user, String siteId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(SiteTestLogPage.PATH, siteId));

        return new SiteTestLogPage(driver);
    }

    public ChangeDrivingLicencePage goToChangeDrivingLicencePage(User user, String userId) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(ChangeDrivingLicencePage.PATH, userId));
        return new ChangeDrivingLicencePage(driver);
    }

    public StartTestConfirmationPage goToStartTestConfirmationPage(User user, Vehicle vehicle) throws IOException, URISyntaxException {
        return gotoVehicleSearchPage(user).searchVehicle(vehicle).selectVehicleForTest();
    }

    public ReasonToCancelTestPage gotoReasonToCancelTestPage(User user) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format(ReasonToCancelTestPage.PATH));
        return new ReasonToCancelTestPage(driver);
    }

    public CreateNewVehicleRecordIdentificationPage gotoCreateNewVehicleRecordIdentificationPage(User user) throws IOException {

        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);

        VehicleSearchPage vehicleSearchPage = PageLocator.getVehicleSearchPage(driver);
        vehicleSearchPage.searchVehicle();
        CreateNewVehicleRecordIdentificationPage createNewVehicleRecordIdentificationPage = vehicleSearchPage.createNewVehicle();
        return new CreateNewVehicleRecordIdentificationPage(driver);

    }

    public DuplicateReplacementCertificateTestHistoryPage gotoDuplicateReplacementCertificateTestHistoryPage(User user, Vehicle vehicle) throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, String.format("/replacement-certificate-vehicle-search?registration=%s&vin=%s",
                vehicle.getRegistrationNumber(), vehicle.getVin()));

        new VehicleSearchPage(driver).searchVehicle(vehicle).selectVehicle();

        return new DuplicateReplacementCertificateTestHistoryPage(driver);
    }

    public RefuseToTestPage gotoRefuseToTestPage(User user, Vehicle vehicle)  throws IOException {
        injectOpenAmCookieAndNavigateToPath(user, VehicleSearchPage.path);

        VehicleSearchPage vehicleSearchPage = PageLocator.getVehicleSearchPage(driver).searchVehicle(vehicle);;
        StartTestConfirmationPage testConfirmationPage = vehicleSearchPage.selectVehicleForTest();
        RefuseToTestPage refuseToTestPage = testConfirmationPage.refuseToTestVehicle();

        return new RefuseToTestPage(driver);
    }

}
