package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.datasource.Business;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskUserSearchPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.CreateAuthorisedExaminerPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.notifications.pages.AedNominationPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.notifications.pages.NominationPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.notifications.pages.NotificationAcceptedPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.notifications.pages.NotificationsRoleRemovalPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.CreateSitePage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.FinancialReportsPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.PaymentSearchPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.*;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.NotificationPage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.PerformanceDashboardPage;
import org.hamcrest.MatcherAssert;
import org.hamcrest.Matchers;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.CacheLookup;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

import java.util.List;


public class UserDashboardPage extends BasePage {

    @FindBy(xpath = "//h1") @CacheLookup private WebElement pageHeading;

    @FindBy(id = "user-profile") private WebElement yourProfileLink;

    @FindBy(id = "motTestingLink") private WebElement startMotTesting;

    @FindBy(id = "action-resume-mot-test") private WebElement resumeMotTestButton;

    @FindBy(id = "action-view-all-special-notices") private WebElement viewAllForSpecialNoticesLink;

    @FindBy(className = "notification_link") private WebElement nominationNotification;

    @FindBy(partialLinkText = "Role Removal") private WebElement roleRemovalLink;

    @FindBy(id = "unread-special-notice-link") private WebElement viewOverdueUnreadNotices;

    @FindBy(id = "unread-notification-count") private WebElement unReadNotificationCount;

    @FindBy(id = "unread-special-notice-count") private WebElement unReadSpecialNotificationCount;

    @FindBy(id = "action-start-mot-retest") private WebElement retestPreviousVehicle;

    @FindBy(id = "action-start-mot-training-mode") private WebElement startMotTrainingMode;

    @FindBy(id = "action-start-certificate-reissue") private WebElement certificateReIssueLink;

    @FindBy(id = "action-start-mot-test") private WebElement startMotTestButton;

    @FindBy(id = "createAuthorisedExaminerLink") private WebElement createAELink;

    @FindBy(id = "action-edit-ae") private WebElement editAE;

    @FindBy(linkText = "AE information") private WebElement aeInformationLink;

    @FindBy(id = "transactions") private WebElement paymentsLink;

    @FindBy(id = "financialReportLink") private WebElement generalFinancialReportsLink;

    @FindBy(id = "action-search-site") private WebElement searchSiteLink;

    @FindBy(id = "action-search-user") private WebElement searchUserLink;

    @FindBy(id = "action-start-user-search") private WebElement helpdeskUserSearch;

    @FindBy(id = "action-search-mot") private WebElement searchMOTLink;

    @FindBy(id = "action-create-vts") private WebElement createVtsLink;

    @FindBy(id = "ae-vts-list_1") private WebElement aeVTSlist1;

    @FindBy(id = "notification-list") private WebElement notificationList;

    @FindBy(id = "action-view-performance-dashboard") private WebElement
            actionViewPerformanceDashboard;

    @FindBy(partialLinkText = "Record Contingency Test") private WebElement contingencyLink;

    @FindBy(partialLinkText = "Example AE Inc.") private WebElement aeDetailsLink;

    @FindBy(className = "site-link") private WebElement garage;

    @FindBy(partialLinkText = "Forgotten/change password") private WebElement forgotPasswordLink;

    @FindBy(id = "feedback-link") private WebElement feedbackLink;

    public UserDashboardPage(WebDriver driver) {
        super(driver);
        //TODO Provisional. Added in order to fix multithread failures
        if (driver.getCurrentUrl() != null && driver.getCurrentUrl().equals(baseUrl() + "/login")) {
            driver.get(baseUrl());
        }
        PageFactory.initElements(driver, this);
    }

    public static UserDashboardPage navigateHereFromLoginPage(WebDriver driver, Login login) {
        LoginPage loginPage = new LoginPage(driver);
        return loginPage.loginAsUser(login);
    }

    public void verifyOnDashBoard() {
        String headingTest = pageHeading.getText();
        MatcherAssert.assertThat(headingTest, Matchers.containsString("Your home"));
    }

    /**
     * @param aeIndex 1-based index of AEs listed on page
     */
    public int getAvailableSlotsInAe(int aeIndex) {
        WebElement slotCountSection = driver.findElement(
                By.xpath("(//p[@class='pivot-panel_slot-count'])[" + aeIndex + "]/strong"));
        return Integer.valueOf(slotCountSection.getText().trim()).intValue();

    }

    public DuplicateReplacementCertificateSearchPage reissueCertificate() {
        if (!existReissueCertificateLink()) {
            resumeMotTest().cancelMotTest(ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR);
        }
        certificateReIssueLink.click();
        return new DuplicateReplacementCertificateSearchPage(driver);
    }

    public LocationSelectPage reissueCertificateExpectingLocationSelectPage() {
        certificateReIssueLink.click();
        return new LocationSelectPage(driver);
    }

    public boolean existReissueCertificateLink() {
        return driver.findElements(By.id("action-start-certificate-reissue")).size() > 0;
    }

    /**
     * method aborts test in progress (if any),
     * chooses first vts (for many vts tester, if the vts hasn't been chosen)
     * and goes for 'Start MOT test'
     *
     * @return
     */
    public VehicleSearchPage startMotTest() {
        if (!existStartMotTestButton() && existResumeMotTestButton()) {
            resumeMotTest().cancelMotTest(ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR);
        }

        startMotTestButton.click();

        if (!isCurrentSiteDisplayedInHeader()) {
            new LocationSelectPage(driver).selectAndConfirmFirstVts();
        }

        return new VehicleSearchPage(driver);
    }

    /**
     * clicks 'start mot test' -> may end in vehicle search page, location select page or error
     * if there is already test in progress!
     */
    public void clickStartMotTest() {
        startMotTestButton.click();
    }

    public LocationSelectPage startMotTestAsManyVtsTesterWithoutVtsChosen() {
        startMotTestButton.click();
        return new LocationSelectPage(driver);
    }

    public boolean isStartMotTestDisplayed() {
        return isElementDisplayed(startMotTestButton);
    }

    public boolean existStartMotTestButton() {
        turnOffImplicitWaits();
        List<WebElement> motTestButton =
                driver.findElements(By.id("action-start-certificate-reissue"));
        turnOnImplicitWaits();
        return motTestButton.size() > 0;
        //return driver.findElements(By.id("action-start-mot-test")).size() > 0;
    }

    public MotTestPage resumeMotTest() {
        resumeMotTestButton.click();
        String pageTitle = getPageTitle();
        if (pageTitle != null && pageTitle.toUpperCase()
                .equals(MOTRetestPage.MOT_RETEST_PAGE_TITLE)) {
            return new MOTRetestPage(driver);
        } else {
            return new MotTestPage(driver);
        }
    }

    public MOTRetestPage resumeMotTestExpectingMOTRetestPage() {
        resumeMotTestButton.click();
        return new MOTRetestPage(driver);
    }

    public boolean existResumeMotTestButton() {
        return driver.findElements(By.id("action-resume-mot-test")).size() > 0;
    }

    public VehicleSearchRetestPage startMotRetest() {
        if (!existStartMotRetestLink()) {
            resumeMotTest().cancelMotTest(ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR);
        }
        retestPreviousVehicle.click();
        if (getPageTitle().equals(LocationSelectPage.PAGE_TITLE)) {
            new LocationSelectPage(driver).selectAndConfirmFirstVts();
        }
        return new VehicleSearchRetestPage(driver);
    }

    public LocationSelectPage startMotRetestAsManyVtsTesterWithoutVtsChosen() {
        retestPreviousVehicle.click();
        return new LocationSelectPage(driver);
    }

    public boolean existStartMotRetestLink() {
        return driver.findElements(By.id("action-start-mot-retest")).size() > 0;
    }

    public SpecialNoticesPage viewNotices() {
        viewAllForSpecialNoticesLink.click();
        return new SpecialNoticesPage(driver);
    }

    public TradeUserSpecialNotice viewOverdueTradeUserUnreadSpecialNotices() {
        viewOverdueUnreadNotices.click();
        return new TradeUserSpecialNotice(driver);
    }

    public boolean isCreateAELinkClickable() {
        return waitForElementToBeClickable(createAELink) != null;
    }

    public boolean isCertificateReIssueLinkClickable() {
        return waitForElementToBeClickable(certificateReIssueLink) != null;
    }

    public boolean isCertificateReissueLinkDisplayed() {

        return isElementDisplayed(certificateReIssueLink);
    }

    public boolean isStartMotTrainingModeLinkClickable() {
        return waitForElementToBeClickable(startMotTrainingMode) != null;
    }

    public boolean isEditAELinkClickable() {
        return waitForElementToBeClickable(editAE) != null;
    }

    public boolean isSearchAELinkClickable() {
        return waitForElementToBeClickable(aeInformationLink) != null;
    }

    public boolean isSearchSiteLinkClickable() {

        return waitForElementToBeClickable(searchSiteLink) != null;
    }

    public boolean isSearchUserLinkClickable() {

        return waitForElementToBeClickable(searchUserLink) != null;
    }

    public boolean isHelpdeskSearchUserLinkClickable() {

        return waitForElementToBeClickable(helpdeskUserSearch) != null;
    }

    public CreateSitePage clickOnNewSiteLink() {
        createVtsLink.click();
        return new CreateSitePage(driver);
    }

    public boolean isViewAllForSpecialNoticesLinkClickable() {
        return waitForElementToBeClickable(viewAllForSpecialNoticesLink) != null;
    }

    public boolean isUnreadNotifications() {
        return getNumberOfUnreadNotifications() > 0;
    }

    public int getNumberOfUnreadNotifications() {
        String unReadNotificationCountText = unReadNotificationCount.getText();
        return Integer.parseInt(unReadNotificationCountText
                .substring(0, unReadNotificationCountText.indexOf("unread")).trim());
    }

    public NotificationPage clickNotification(String notificationTitle) {
        turnOffImplicitWaits();
        List<WebElement> notifications = notificationList
                .findElements(By.xpath("//a[contains(text(),'" + notificationTitle + "')]"));
        turnOnImplicitWaits();
        notifications.get(0).click();
        return new NotificationPage(driver);
    }

    public CreateAuthorisedExaminerPage clickAeLink() {
        createAELink.click();
        return new CreateAuthorisedExaminerPage(driver);
    }

    public PerformanceDashboardPage clickOnTesterPerformanceDashboard() {
        actionViewPerformanceDashboard.click();
        return new PerformanceDashboardPage(driver);
    }

    //TODO refactor when AE search functionality is available
    public AuthorisedExaminerOverviewPage manageAuthorisedExaminer(Business organisation) {
        turnOffImplicitWaits();
        try {
            WebElement authExaminerDetails = driver.findElement(By.xpath(
                    "(//*[contains(@id,'ae-vts-list_')])[contains(.,'"
                            + organisation.busDetails.companyName + "')]"));
            authExaminerDetails.findElement(By.linkText(organisation.busDetails.companyName))
                    .click();
        } catch (NoSuchElementException e) {
            String authorisedExaminerUrl =
                    baseUrl() + "/authorised-examiner/" + organisation.getBusId();
            driver.get(authorisedExaminerUrl);
        }
        turnOnImplicitWaits();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public AuthorisedExaminerOverviewPage manageAuthorisedExaminer(int organisationId) {
        turnOffImplicitWaits();
        driver.findElement(By.xpath("//a[@href='/authorised-examiner/" + organisationId + "']"))
                .click();
        turnOnImplicitWaits();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public UserDashboardPage clickStartMotTestExpectingError() {
        startMotTestButton.click();
        return new UserDashboardPage(driver);
    }

    public VehicleSearchPage startDemoTest() {
        startMotTrainingMode.click();
        return new VehicleSearchPage(driver);
    }

    public SiteDetailsPage clickOnSiteLink(Site site) {
        turnOffImplicitWaits();
        try {
            WebElement siteLink = driver.findElement(
                    By.xpath("//a[contains(text(), '(" + site.getNumber() + ")')]"));
            siteLink.click();
        } catch (NoSuchElementException e) {
            driver.get(baseUrl() + "/vehicle-testing-station/" + site.getId());
        }
        turnOnImplicitWaits();
        return new SiteDetailsPage(driver);
    }

    public SiteDetailsPage clickOnSiteLink(String site) {
        turnOffImplicitWaits();
        driver.findElement(By.partialLinkText(site)).click();
        turnOnImplicitWaits();
        return new SiteDetailsPage(driver);
    }

    public HelpdeskUserSearchPage clickUserSearch() {
        helpdeskUserSearch.click();
        return new HelpdeskUserSearchPage(driver);
    }


    public int numberOfNotificationsByTitle(String notificationTitle) {
        turnOffImplicitWaits();
        List<WebElement> notifications = notificationList
                .findElements(By.xpath("//a[contains(text(),'" + notificationTitle + "')]"));
        turnOnImplicitWaits();
        return notifications.size();
    }

    public void clickContingencyLink() {
        contingencyLink.click();

    }

    public SearchForAePage clickListAllAEs() {
        aeInformationLink.click();
        return new SearchForAePage(driver);
    }

    public PaymentSearchPage clickPaymentsLink() {
        paymentsLink.click();
        return new PaymentSearchPage(driver);
    }

    public FinancialReportsPage clickGeneralFinancialReportsLink() {
        generalFinancialReportsLink.click();
        return new FinancialReportsPage(driver);
    }

    public AuthorisedExaminerOverviewPage clickFirstAeLink() {
        driver.findElement(By.xpath("(//*[contains(@id, 'ae-vts-list_1')])//h2/a")).click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public AuthorisedExaminerOverviewPage clickAeNameLink(AeDetails aeDetails) {
        driver.findElement(By.partialLinkText(aeDetails.getAeName())).click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public SiteDetailsPage openVtsDetails() {
        garage.click();
        return new SiteDetailsPage(driver);
    }

    public NominationPage clickViewNomination() {
        nominationNotification.click();
        return new NominationPage(driver);
    }

    public AedNominationPage clickViewAedNomination() {
        nominationNotification.click();
        return new AedNominationPage(driver);

    }

    public NotificationAcceptedPage clickNominationAcceptedLink() {
        nominationNotification.click();
        return new NotificationAcceptedPage(driver);
    }

    public NotificationsRoleRemovalPage clickRoleRemovalLink() {
        roleRemovalLink.click();
        return new NotificationsRoleRemovalPage(driver);
    }

    public String getFeedbackLink() {
        return feedbackLink.getAttribute("href");
    }

    public boolean isVtsAssociationDisplayed(String vts) {

        return isElementDisplayed(driver.findElement(By.xpath(".//a[contains(., '" + vts + "')]")));
    }
}
