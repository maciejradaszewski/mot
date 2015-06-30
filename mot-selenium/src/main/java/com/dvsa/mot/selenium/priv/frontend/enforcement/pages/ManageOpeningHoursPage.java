package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.OpeningHours;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.enums.Days;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;


public class ManageOpeningHoursPage extends BasePage {

    public static final String PAGE_TITLE = "VTS DETAILS\n" + "MANAGE OPENING HOURS";

    @FindBy(id = "update-opening-hours") private WebElement updateOpeningHours;

    public ManageOpeningHoursPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public static ManageOpeningHoursPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Site site) {
        return SiteDetailsPage.navigateHereFromLoginPage(driver, login, site)
                .clickChangeOpeningHours();
    }

    public SiteDetailsPage clickUpdateOpeningHours() {
        updateOpeningHours.click();
        return new SiteDetailsPage(driver);
    }

    public ManageOpeningHoursPage clickUpdateOpeningHoursExpectingError() {

        updateOpeningHours.click();
        return new ManageOpeningHoursPage(driver);
    }

    public ManageOpeningHoursPage updateOpeningHours(OpeningHours openingHours, Days days) {
        boolean isClosed = openingHours.getIsClosed();
        if (isClosed) {

        } else {
            WebElement isClosedCheckbox =
                    driver.findElement(By.id(days.getDayName().toLowerCase() + "isClosed"));
            WebElement openTimeUpdate =
                    driver.findElement(By.id(days.getDayName().toLowerCase() + "-time-text-open"));
            WebElement closeTimeUpdate =
                    driver.findElement(By.id(days.getDayName().toLowerCase() + "-time-text-close"));
            WebElement openTimeFormatAmOrPm = driver.findElement(
                    By.id(days.getDayName().toLowerCase() + "-" + openingHours.getOpeningPeriod()
                            + "-radio-open"));
            WebElement closeTimeFormatAmOrPm = driver.findElement(
                    By.id(days.getDayName().toLowerCase() + "-" + openingHours.getClosingPeriod()
                            + "-radio-close"));

            if (isClosed && !isClosedCheckbox.isSelected()) {
                isClosedCheckbox.click();
                openTimeUpdate.clear();
                closeTimeUpdate.clear();
            } else {
                if (!isClosed && isClosedCheckbox.isSelected()) {
                    isClosedCheckbox.click();
                }
                openTimeUpdate.clear();
                openTimeUpdate.sendKeys(openingHours.getOpeningTime());
                openTimeFormatAmOrPm.click();
                closeTimeUpdate.clear();
                closeTimeUpdate.sendKeys(openingHours.getClosingTime());
                closeTimeFormatAmOrPm.click();
            }
        }
        return this;
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }

    public SiteDetailsPage cancelAndReturnToVTS(String site) {

        WebElement cancelAndGoBackToVTS = driver.findElement(
                By.xpath("//a[contains(.,'Cancel and return to " + site + " details')]"));
        cancelAndGoBackToVTS.click();
        return new SiteDetailsPage(driver);
    }
}
