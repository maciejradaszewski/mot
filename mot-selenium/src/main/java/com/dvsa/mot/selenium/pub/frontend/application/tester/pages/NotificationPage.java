package com.dvsa.mot.selenium.pub.frontend.application.tester.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class NotificationPage extends BasePage {

    @FindBy(id = "link-back-to-home") private WebElement backToHomePage;

    @FindBy(xpath = "//article/p") private WebElement notificationMessage;

    @FindBy(css = ".content-header__title") private WebElement notificationReceivedByAEDMText;

    @FindBy(id = "action-site-nomination-accepted") private WebElement acceptButton;

    @FindBy(id = "action-organisation-nomination-accepted") private WebElement
            acceptNominationButtonForAED;

    @FindBy(id = "action-site-nomination-rejected") private WebElement rejectButton;

    @FindBy(id = "action-organisation-nomination-rejected") private WebElement
            rejectedNominationButtonForAED;

    @FindBy(id = "link-back-to-home") private WebElement backToUserHomePage;

    @FindBy(id = "notification-decision") private WebElement nominationDecision;

    @FindBy(id = "notification-content") private WebElement notificationContentText;

    public NotificationPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public static NotificationPage navigateHereFromLogin(WebDriver driver, Login login,
            String notificationTitle) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login)
                .clickNotification(notificationTitle);
    }

    public UserDashboardPage backToHomePage() {
        backToHomePage.click();
        return new UserDashboardPage(driver);
    }

    public String getNominationDecisionMessage() {
        return nominationDecision.getText().trim();
    }

    public String getNotificationMessageForAEDM() {
        String notification = notificationMessage.getText();
        return notification.substring(0, 71);

    }

    public String getNotificationTypeByNomination() {
        return notificationReceivedByAEDMText.getText();
    }

    public NotificationPage clickAcceptNomination() {
        driver.findElement(By.xpath("//button[text()='" + Text.TEXT_ACCEPT.text + "']")).click();
        return new NotificationPage(driver);
    }

    public NotificationPage clickAcceptNominationForAED() {
        acceptNominationButtonForAED.click();
        return new NotificationPage(driver);
    }

    public NotificationPage clickRejectNominationForAED() {
        rejectedNominationButtonForAED.click();
        return new NotificationPage(driver);
    }

    public boolean isTheCorrectVehicleBeenAborted(String vehicleVIN) {
        return (notificationContentText.getText().contains(vehicleVIN));
    }

    public static NotificationPage loginAsAssociatedUserToCheckNominationLetter(LoginPage loginPage,
            Person userBeingAssociated, String nominationLetter) {

        UserDashboardPage userDashboardPage = loginPage.loginAsUser(userBeingAssociated.login);
        return userDashboardPage.clickNotification(nominationLetter).clickAcceptNomination();
    }
}
