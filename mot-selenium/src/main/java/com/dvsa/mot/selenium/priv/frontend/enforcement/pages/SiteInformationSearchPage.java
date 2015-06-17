package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class SiteInformationSearchPage extends BasePage {

    @FindBy(id = "site_number") private WebElement siteNumber;

    @FindBy(id = "site_name") private WebElement siteName;

    @FindBy(id = "site_town") private WebElement siteTown;

    @FindBy(id = "site_postcode") private WebElement sitePostCode;

    @FindBy(id = "site_vehicle_class[]1") private WebElement siteClass1;

    @FindBy(id = "site_vehicle_class[]2") private WebElement siteClass2;

    @FindBy(id = "site_vehicle_class[]3") private WebElement siteClass3;

    @FindBy(id = "site_vehicle_class[]4") private WebElement siteClass4;

    @FindBy(id ="site_vehicle_class[]5") private WebElement siteClass5;

    @FindBy(id = "site_vehicle_class[]7") private WebElement siteClass7;

    @FindBy(id = "submitSiteSearch") private WebElement submitSiteSearch;

    @FindBy(className = "validation-message") private WebElement validationMessage;

    @FindBy(id = "validation-message--failure") private WebElement validationMessageFailure;

    public SiteInformationSearchPage(WebDriver driver) {
        super(driver);
        checkTitle(PageTitles.SITE_INFORMATION.getPageTitle());
    }

    public boolean isSiteSearchButtonEnabled(){
        return submitSiteSearch.isEnabled();
    }

    public static SiteInformationSearchPage navigateHereFromLoginPage(WebDriver driver,
            Login login) {
        return EnforcementHomePage.navigateHereFromLoginPage(driver, login)
                .clickSiteInformationLink();
    }

    public SiteInformationSearchPage enterSiteId(String siteId){
        siteNumber.sendKeys(siteId);
        return new SiteInformationSearchPage(driver);
    }

    public SiteInformationSearchPage enterSiteName(String name){
        siteName.sendKeys(name);
        return new SiteInformationSearchPage(driver);
    }

    public SiteInformationSearchPage enterSiteTown(String town){
        siteTown.sendKeys(town);
        return new SiteInformationSearchPage(driver);
    }

    public SiteInformationSearchPage enterSitePostCode(String postCode){
        sitePostCode.sendKeys(postCode);
        return new SiteInformationSearchPage(driver);
    }

    public SiteInformationSearchPage selectSiteClass1(){
        siteClass1.click();
        return new SiteInformationSearchPage(driver);
    }

    public SiteInformationSearchPage selectSiteClass2(){
        siteClass2.click();
        return new SiteInformationSearchPage(driver);
    }

    public SiteInformationSearchPage selectSiteClass3(){
        siteClass3.click();
        return new SiteInformationSearchPage(driver);
    }

    public SiteInformationSearchPage selectSiteClass4(){
        siteClass4.click();
        return new SiteInformationSearchPage(driver);
    }

    public SiteInformationSearchPage selectSiteClass5(){
        siteClass5.click();
        return new SiteInformationSearchPage(driver);
    }

    public SiteInformationSearchPage selectSiteClass7(){
        siteClass7.click();
        return new SiteInformationSearchPage(driver);
    }

    public SiteSearchResultsPage submitSearchExpectingResultsPage(){
        submitSiteSearch.click();
        return new SiteSearchResultsPage(driver);
    }

    public SiteDetailsPage submitSearchExpectingDetailsPage(){
        submitSiteSearch.click();
        return new SiteDetailsPage(driver);
    }

    public SiteInformationSearchPage submitSearchExpectingSiteSearchPage(){
        submitSiteSearch.click();
        return new SiteInformationSearchPage(driver);
    }

    public String getValidationMessage(){
        return validationMessage.getText();
    }

    public String getValidationMessageFailure() {
        return validationMessageFailure.getText();
    }

    public boolean isErrorMessageDisplayed() {

        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }

}
