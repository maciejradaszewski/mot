package com.dvsa.mot.selenium.priv.frontend.helpdesk;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.joda.time.LocalDate;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class HelpdeskUserSearchPage extends BasePage {
    private String PAGE_TITLE = "USER SEARCH";

    @FindBy(id = "username") private WebElement username;

    @FindBy(id = "firstName") private WebElement firstName;

    @FindBy(id = "lastName") private WebElement lastName;

    @FindBy(id = "date1-day") private WebElement dayOfBirth;

    @FindBy(id = "date1-month") private WebElement monthOfBirth;

    @FindBy(id = "date1-year") private WebElement yearOfBirth;

    @FindBy(id = "town") private WebElement town;

    @FindBy(id = "postcode") private WebElement postcode;

    @FindBy(xpath = "//button[contains(.,'Search')]") private WebElement search;

    @FindBy(id = "return_to_user_home") private WebElement backToUserHome;

    @FindBy(id = "validation-summary-message") private WebElement infoMessage;

    @FindBy(id = "validation-message--failure") private WebElement validationSummary;

    public HelpdeskUserSearchPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public static HelpdeskUserSearchPage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickUserSearch();
    }

    public HelpdeskUserSearchPage enterUsername(String username) {
        this.username.sendKeys(username);
        return this;
    }

    public String getUsername() {
        return username.getAttribute("value");
    }

    public HelpdeskUserSearchPage enterFirstName(String firstName) {
        this.firstName.sendKeys(firstName);
        return this;
    }

    public String getFirstName() {
        return firstName.getAttribute("value");
    }

    public HelpdeskUserSearchPage enterLastName(String lastName) {
        this.lastName.sendKeys(lastName);
        return this;
    }

    public HelpdeskUserSearchPage enterDayOfBirth(String dayOfBirth) {
        this.dayOfBirth.sendKeys(dayOfBirth);
        return this;
    }

    public String getDayOfBirth() {
        return dayOfBirth.getAttribute("value");
    }

    public HelpdeskUserSearchPage enterMonthOfBirth(String monthOfBirth) {
        this.monthOfBirth.sendKeys(monthOfBirth);
        return this;
    }

    public String getMonthOfBirth() {
        return monthOfBirth.getAttribute("value");
    }

    public HelpdeskUserSearchPage enterYearOfBirth(String yearOfBirth) {
        this.yearOfBirth.sendKeys(yearOfBirth);
        return this;
    }

    public String getYearOfBirth() {
        return yearOfBirth.getAttribute("value");
    }

    public HelpdeskUserSearchPage enterDateOfBirth(LocalDate dateOfBirth) {
        enterDayOfBirth(dateOfBirth.toString("dd"));
        enterMonthOfBirth(dateOfBirth.toString("MM"));
        enterYearOfBirth(dateOfBirth.toString("yyyy"));
        return this;
    }

    public HelpdeskUserSearchPage enterPostcode(String postcode) {
        this.postcode.sendKeys(postcode);
        return this;
    }

    public HelpdeskUserSearchPage enterTown(String town) {
        this.town.sendKeys(town);
        return this;
    }

    public String getPostcode() {
        return postcode.getAttribute("value");
    }

    private void clickSearch() {
        search.click();
    }

    public HelpdeskUserResultsPage search() {
        clickSearch();
        return new HelpdeskUserResultsPage(driver);
    }

    public HelpdeskUserSearchPage searchExpectigError() {
        clickSearch();
        return new HelpdeskUserSearchPage(driver);
    }

    public String getInfoMessage() {
        return infoMessage.getText();
    }

    public String getValidationSummary() {
        return validationSummary.getText();
    }

    public boolean isSearchButtonDisplayed(){

        return isElementDisplayed(search);
    }



}
