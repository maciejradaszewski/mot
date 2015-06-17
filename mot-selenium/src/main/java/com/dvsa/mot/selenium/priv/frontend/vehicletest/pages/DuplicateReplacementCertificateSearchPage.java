package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.enums.VehicleSearch;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;
import org.testng.Assert;

public class DuplicateReplacementCertificateSearchPage extends BasePage {

    private String REPLACEMENT_CERT__PAGE_TITLE = "DUPLICATE OR REPLACEMENT CERTIFICATE";

    @FindBy(id = "reg-input") private WebElement registrationField;

    @FindBy(id = "chk") private WebElement checkboxForVIN;

    @FindBy(id = "vin-input") private WebElement vinField;

    @FindBy(id = "vehicle-search") private WebElement clickSearchButton;

    @FindBy(id = "cancel_vehicle_search") private WebElement cancelSearchButton;

    @FindBy(id = "vin-type-select") private WebElement selectVinType;

    @FindBy(xpath = "id('results-table')//td[1]//a") private WebElement firstResultCTA;

    public DuplicateReplacementCertificateSearchPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(REPLACEMENT_CERT__PAGE_TITLE);
    }

    public DuplicateReplacementCertificateSearchPage(WebDriver driver, String title) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(title);
    }

    public static DuplicateReplacementCertificateSearchPage navigateHereFromLandingPage(
            WebDriver driver, Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).reissueCertificate();
    }

    public DuplicateReplacementCertificateSearchPage enterRegistration(String registrationNumber) {
        this.registrationField.sendKeys(registrationNumber);
        return this;
    }

    public DuplicateReplacementCertificateSearchPage enterVIN(String vinNumber) {
        this.vinField.sendKeys(vinNumber);
        return this;
    }

    public DuplicateReplacementCertificatePage submitSearchWithVinAndReg(String vin, String reg) {
        enterRegistration(reg);
        enterVIN(vin);
        clickSearchButton();
        clickVehicleCTA();
        return new DuplicateReplacementCertificatePage(driver);
    }

    public DuplicateReplacementCertificateSearchPage clickSearchButton() {
        clickSearchButton.click();
        return this;
    }
    public DuplicateReplacementCertificatePage clickVehicleCTA() {
        firstResultCTA.click();
        return new DuplicateReplacementCertificatePage(driver);
    }

}
