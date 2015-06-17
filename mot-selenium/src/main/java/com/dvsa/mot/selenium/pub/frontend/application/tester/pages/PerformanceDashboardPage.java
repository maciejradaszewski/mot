package com.dvsa.mot.selenium.pub.frontend.application.tester.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class PerformanceDashboardPage extends BasePage {

    @FindBy(id = "today-total-vehicles-tested") private WebElement todayTotalVehiclesTested;

    @FindBy(id = "today-number-passed") private WebElement todayNumberPassed;

    @FindBy(id = "today-number-failed") private WebElement todayNumberFailed;

    @FindBy(id = "today-number-retests") private WebElement todayNumberRetests;

    @FindBy(id = "current-month-average-time") private WebElement currentMonthAverageTime;

    @FindBy(id = "current-month-fail-rate") private WebElement currentMonthFailRate;

    @FindBy(id = "back-to-home-link") private WebElement backToHomeLink;

    public PerformanceDashboardPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public static PerformanceDashboardPage navigateHereFromLoginPage(WebDriver driver,
            Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login)
                .clickOnTesterPerformanceDashboard();
    }

    public void goBackToHome() {
        backToHomeLink.click();
    }

    public int getNumberOfTestsPassed() {
        return Integer.parseInt(todayNumberPassed.getText());
    }

    public int getNumberOfTestsFailed() {
        return Integer.parseInt(todayNumberFailed.getText());
    }

    public String getCurrentMonthFailRate() {
        return currentMonthFailRate.getText();
    }

    public boolean isDemoTestResultRecorded() {
        return todayTotalVehiclesTested.getText().equalsIgnoreCase("0");
    }
}

