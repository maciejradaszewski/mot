package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.equipment.pages.EquipmentPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.CreateSitePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.DuplicateReplacementCertificateSearchPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.SpecialNoticesPage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.NotificationPage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.VehicleTestClassPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

/**
 * @deprecated use UserDashboardPage
 */
@Deprecated public class UserProfilePage extends BasePage {

    //private static final String PAGE_TITLE = "YOUR PERSONAL PROFILE";
    //private static final String PAGE_TITLE = "ENFORCEMENT HOMEPAGE";

    @FindBy(id = "action-start-mot-test")

    private WebElement startMotTesting;

    @FindBy(id = "vehicleTestHistoryLink") private WebElement documentationLink;

    @FindBy(id = "specialNoticesAllLink") private WebElement specialNoticesLink;

    @FindBy(id = "action-start-tester-application") public WebElement applyToBeMotTester;

    @FindBy(id = "aedmApplicationLink") public WebElement applyToBeAedm;

    @FindBy(id = "aeApplicationLink") public WebElement applyToBeAnAe;

    @FindBy(id = "vtsApplicationLink") public WebElement vtsApplicationLink;

    @FindBy(id = "display-name") private WebElement displayNameFiled;

    @FindBy(id = "email") private WebElement emailField;

    @FindBy(id = "logout") private WebElement signOutButton;

    @FindBy(id = "vehicleTestHistoryLink") private WebElement testHistory;

    @FindBy(id = "applicationsLink") private WebElement testerApplicationsLink;

    @FindBy(id = "user-profile") private WebElement userProfileLink;

    @FindBy(id = "unread-notification-count") private WebElement unreadNotificationCount;

    @FindBy(id = "masterEquipmentLink") private WebElement equipmentLink;

    @FindBy(id = "action-create-vts") private WebElement createVts;

    public UserProfilePage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public boolean isAedmApplicationLinkPresentAndClick() {
        if (isAedmApplicationLinkPresent()) {
            applyToBeAedm.click();
            return true;
        } else
            return false;
    }

    public boolean isSignOutButtonDisplayed() {
        return !signOutButton.isDisplayed();
    }

    public LoginPage clickSignOutButtonToGoToLoginPage() {
        signOutButton.click();
        return new LoginPage(driver);
    }

    public void clickOnAedmApplicationLink() {
        applyToBeAedm.click();
    }

    public boolean isAedmApplicationLinkPresent() {
        return isElementPresent(By.id("aedmApplicationLink"));
    }

    public VehicleTestClassPage clickOnApplyToBeAnMotTesterLink() {
        applyToBeMotTester.click();

        return new VehicleTestClassPage(driver);
    }

    public boolean verifyUserNameExists() {
        return !displayNameFiled.getText().isEmpty();
    }

    public boolean verifyUserEmail(Person person) {
        if (emailField.getText().equalsIgnoreCase(person.email)) {
            return true;
        } else
            return false;
    }

    public DuplicateReplacementCertificateSearchPage clickDocumentationLink() {
        testHistory.click();
        return new DuplicateReplacementCertificateSearchPage(driver);
    }

    public SpecialNoticesPage clickSpecialNoticesAllLink() {
        specialNoticesLink.click();
        return new SpecialNoticesPage(driver);
    }

    public UserPersonalProfilePage clickOnUserProfileLink() {
        userProfileLink.click();
        return new UserPersonalProfilePage(driver);
    }

    public NotificationPage clickOnNotification(String notification) {
        driver.findElement(By.xpath("//a[contains(text()," + "'" + notification + "'" + ")]"))
                .click();
        return new NotificationPage(driver);
    }

    public boolean isUnreadNotifications() {
        return getNumberOfUnreadNotifications() > 0;
    }

    public int getNumberOfUnreadNotifications() {
        String notification = unreadNotificationCount.getText();
        int unReadNotifications =
                Integer.parseInt(notification.substring(0, notification.indexOf("unread")));
        return unReadNotifications;
    }

    public NotificationPage clickOnTrainingOutComeNotification(String notification) {
        driver.findElement(By.xpath("//a[contains(text()," + "'" + notification + "'" + ")]"))
                .click();
        return new NotificationPage(driver);
    }

    public EquipmentPage clickOnMasterEquipmentListLink() {
        equipmentLink.click();
        return new EquipmentPage(driver);
    }


    public NotificationPage clickOnNominationLink(String nominationTextLink) {
        driver.findElement(By.xpath("//a[contains(text()," + "'" + nominationTextLink + "'" + ")]"))
                .click();
        return new NotificationPage(driver);
    }

    public CreateSitePage clickOnNewSiteLink() {
        createVts.click();
        return new CreateSitePage(driver);
    }

    public boolean isCreateNewSiteDisplayed() {
        return isElementDisplayed(createVts);
    }
}
