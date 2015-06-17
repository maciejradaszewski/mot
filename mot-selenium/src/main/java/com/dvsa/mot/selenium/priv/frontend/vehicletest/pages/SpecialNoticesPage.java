package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.util.List;


public class SpecialNoticesPage extends BasePage {
    public static final String PAGE_TITLE = "SPECIAL NOTICES";

    @FindBy(id = "returnDashboard") private WebElement returnButton;

    @FindBy(id = "info-message") private WebElement specialNoticeInfoMessage;

    @FindBy(id = "create_special_notice") private WebElement createSpecialNotice;

    @FindBy(xpath = "//*[contains(@id,'view-overdue-')]") private List<WebElement>
            overdueSpecialNotices;

    @FindBy(id = "special-notice-unread") private List<WebElement> unreadSpecialNotices;

    @FindBy(id = "special-notice") private List<WebElement> currentSpecialNotices;

    @FindBy(id = "removed_special_notice") private WebElement removedSpecialNoticesLink;

    @FindBy(id = "special-notice-edit") private WebElement specialNoticeEdit;

    @FindBy(id = "no-unread-special-notice-message") private WebElement noUnreadSpecialNotices;

    public SpecialNoticesPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public static SpecialNoticesPage navigateHereFromLoginPageAsTesterUser(WebDriver driver,
            Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).viewNotices();
    }

    public static SpecialNoticesPage navigateHereFromLoginPageAsDVSAUser(WebDriver driver,
            Login login) {
        UserDashboardPage.navigateHereFromLoginPage(driver, login).viewNotices();
        return new SpecialNoticesPage(driver);
    }

    public UserDashboardPage returnToDashboard() {
        returnButton.click();
        return new UserDashboardPage(driver);
    }

    public String getStatus(int specialNotice) {

        return findWebElement(By.id("status-" + specialNotice)).getText();
    }

    public CreateSpecialNoticePage createSpecialNotice() {
        createSpecialNotice.click();
        return new CreateSpecialNoticePage(driver);
    }

    public boolean isCreateSpecialNoticeLinkNotPresent() {

        return isElementPresent(By.id("create_special_notice"));

    }

    public boolean isRemoveLinkNotPresent() {

        return isElementPresent(By.id("removed_special_notice"));
    }

    public SpecialNoticesPage printCurrentSpecialNotice(int printSpecialNotice) {

        new WebDriverWait(driver, 1).until(ExpectedConditions
                .visibilityOf(findWebElement(By.className("view-special-notices")))).click();
        new WebDriverWait(driver, 1).until(ExpectedConditions
                .visibilityOf(findWebElement(By.id("print-" + printSpecialNotice)))).click();

        return this;
    }

    public SpecialNoticesPage removeSpecialNotice(int specialNotice) {

        new WebDriverWait(driver, 1).until(ExpectedConditions
                .visibilityOf(findWebElement(By.id("view-current-" + specialNotice)))).click();
        new WebDriverWait(driver, 1).until(ExpectedConditions
                .visibilityOf(findWebElement(By.id("special-notice-remove-" + specialNotice))))
                .click();

        driver.switchTo().alert().accept();
        return this;
    }

    public boolean removeButtonNotPresent(int specialNotice) {
        WebElement removeButton = findWebElement(By.id("special-notice-remove-" + specialNotice));
        if (removeButton == null) {
            return true;
        }

        return false;
    }

    public boolean acknowledgeButtonNotPresent(int specialNotice) {
        WebElement acknowledgeButton =
                findWebElement(By.id("special-notice-submit-" + specialNotice));
        if (acknowledgeButton == null) {
            return true;
        }
        return false;
    }

    public SpecialNoticesPage acknowledgeOverdueSpecialNotice(int specialNotice) {


        new WebDriverWait(driver, 1).until(ExpectedConditions
                .visibilityOf(findWebElement(By.id("view-current-" + specialNotice)))).click();
        new WebDriverWait(driver, 1).until(ExpectedConditions
                .visibilityOf(findWebElement(By.id("special-notice-submit-" + specialNotice))))
                .click();

        return this;
    }

    public CreateSpecialNoticePage editSpecialNotice(int specialNoticeContentId) {

        new WebDriverWait(driver, 1).until(ExpectedConditions
                .visibilityOf(findWebElement(By.id("view-current-" + specialNoticeContentId))))
                .click();
        new WebDriverWait(driver, 1).until(ExpectedConditions.visibilityOf(
                findWebElement(By.id("special-notice-edit-" + specialNoticeContentId)))).click();

        return new CreateSpecialNoticePage(driver);
    }

    public String getTitle(int specialNotice) {
        return findWebElement(By.id("title-" + specialNotice)).getText();

    }

    public String getUnreadSpecialNoticesMessage() {
        return noUnreadSpecialNotices.getText();

    }

    public String verifySpecialNoticeInfoMessage() {
        return specialNoticeInfoMessage.getText();
    }


    public boolean isPrintButtonPresentInCurrentNotices(int specialNoticePrintId) {
        for (WebElement currentSpecialNotice : currentSpecialNotices) {
            if (currentSpecialNotice.findElements(By.id("print-" + specialNoticePrintId)).size()
                    == 0)
                return false;
        }
        return true;
    }

}
