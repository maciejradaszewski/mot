package com.dvsa.mot.selenium.pub.frontend.user.admin.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.Keys;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

import java.util.List;

public class UsersListPage extends BasePage {
    //TODO provisional. It should be removed when user admin page link is added to Dashboard page
    private static String PAGE_URL = baseUrl() + "/user-admin";

    @FindBy(name = "usersTable_length") private WebElement showRowsSelection;

    @FindBy(id = "usersTable_next") private WebElement navigateToNextPage;

    @FindBy(id = "usersTable_last") private WebElement navigateToLastPage;

    @FindBy(xpath = "//label/input") private WebElement searchUserInput;

    @FindBy(name = "usersTable_length") private WebElement displayNoOfRecordsPerPage;

    @FindBy(xpath = "//div/h3") private WebElement labelSystemUsers;

    @FindBy(id = "usersTable") private List<WebElement> systemUsersTable;

    @FindBy(id = "usersTable_info") private WebElement usersTableInfo;

    public UsersListPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public boolean isLabelSystemUsersDisplayed() {
        return isElementDisplayed(labelSystemUsers);
    }

    public boolean isUserIdPresent(String uid) {

        for (WebElement tableRow : systemUsersTable) {
            String matchUserId = tableRow.findElement(By.xpath("//td[1]")).getText();
            if (matchUserId.equalsIgnoreCase(uid)) {
                return true;
            }
        }
        return false;
    }

    public boolean isUserNamePresent(String userName) {

        for (WebElement tableRow : systemUsersTable) {
            String matchUserName = tableRow.findElement(By.xpath("//td[2]")).getText();
            if (matchUserName.equalsIgnoreCase(userName)) {
                return true;
            }
        }
        return false;
    }

    public boolean isNamePresent(String name) {
        turnOffImplicitWaits();
        List<WebElement> e = driver.findElements(
                By.xpath("//*[@id='usersTable']//td[contains(.,'" + name + "')]"));
        turnOnImplicitWaits();
        return e.size() > 0;
    }

    public boolean isRolePresent(String role) {

        for (WebElement tableRow : systemUsersTable) {
            String matchRole = tableRow.findElement(By.xpath("//td[4]")).getText();
            if (matchRole.equalsIgnoreCase(role)) {
                return true;
            }
        }
        return false;
    }

    public void select10UserPerPage() {
        new Select(displayNoOfRecordsPerPage).selectByVisibleText("10");
    }

    public void select25UserPerPage() {
        new Select(displayNoOfRecordsPerPage).selectByVisibleText("25");
    }

    public void select50UserPerPage() {
        new Select(displayNoOfRecordsPerPage).selectByVisibleText("50");
    }

    public void select100UserPerPage() {
        new Select(displayNoOfRecordsPerPage).selectByVisibleText("100");
    }

    public int getNumberOfUsersInList() {
        String recordsInfo = usersTableInfo.getText();
        String[] splitRecordsInfo = recordsInfo.split(" ");
        return Integer.parseInt(splitRecordsInfo[splitRecordsInfo.length - 2]);
    }

    public void searchCriteria(String uid) {
        searchUserInput.sendKeys(uid);
        searchUserInput.sendKeys(Keys.ENTER);
    }

    public static UsersListPage navigateHereFromLoginPage(WebDriver driver, Login login) {
        UserDashboardPage.navigateHereFromLoginPage(driver, login);
        driver.get(PAGE_URL);
        return new UsersListPage(driver);
    }

} 
