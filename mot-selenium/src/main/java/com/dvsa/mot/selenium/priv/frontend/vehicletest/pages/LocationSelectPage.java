package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.CacheLookup;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

import java.util.List;

public class LocationSelectPage extends BasePage {

    public static final String PAGE_TITLE = "PLEASE SELECT YOUR CURRENT VTS";

    @FindBy(name = "vtsId") @CacheLookup private List<WebElement> vtsRadios;

    @FindBy(name = "submit") @CacheLookup private WebElement confirm;

    @FindBy(className = "col-lg-8") @CacheLookup private WebElement noSlotsInfo;

    @FindBy(xpath = "id('change-vts-list')") private WebElement vtsList;

    public LocationSelectPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public static LocationSelectPage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login)
                .startMotRetestAsManyVtsTesterWithoutVtsChosen();
    }

    public List<WebElement> getVtsRadio() {
        return vtsRadios;
    }

    public WebElement getRadioButton(int n) {
        List<WebElement> vtsRadioGroup = getVtsRadio();
        return vtsRadioGroup.get(n);
    }

    public LocationSelectPage selectVtsRadio(int n) {
        getRadioButton(n).click();
        return this;
    }

    public String getRadioBtnLabelText(String vtsId) {
        By vtsRadioLabel = By.id("vts-radio-option-" + vtsId);
        return driver.findElement(vtsRadioLabel).getText();
    }

    public LocationSelectPage selectVTS(Site site) {
        WebElement specficVTS =
                vtsList.findElement(By.xpath("//*[contains(text(),'" + site.getName() + "')]"));
        specficVTS.click();
        return this;
    }

    public VehicleSearchPage selectAndConfirmVTS(Site site) {
        selectVTS(site);
        return confirmSelectedAndGoBacToVehicleSearch();
    }

    public DuplicateReplacementCertificateSearchPage selectAndConfirmVTSExpectingDuplicateReplacementCertificateSearchPage(
            Site site) {
        selectVTS(site);
        confirm.click();
        return new DuplicateReplacementCertificateSearchPage(driver);
    }

    public UserDashboardPage confirmSelected() {
        confirm.submit();
        return new UserDashboardPage(driver);
    }

    public void selectAndConfirmFirstVts() {
        selectVtsRadio(0);
        confirm.submit();
    }

    public VehicleSearchPage confirmSelectedAndGoBacToVehicleSearch() {
        confirm.submit();
        return new VehicleSearchPage(driver);
    }

    public LocationSelectPage confirmSelectedExpectingError() {
        confirm.submit();
        return new LocationSelectPage(driver);
    }

    public LocationSelectPage selectVtsByName(String name) {
        List<WebElement> vtsLabels = vtsList.findElements(By.xpath("div/label/strong"));
        for (WebElement vtsLabel : vtsLabels) {
            String vtsName = vtsLabel.getText();

            if (vtsName.equalsIgnoreCase(name)) {
                vtsLabel.click();
                return this;
            }
        }

        throw new NoSuchElementException("VTS " + name + " not found");
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}



