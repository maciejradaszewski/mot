package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class EnforcementHomePage extends BasePage {

    @FindBy(partialLinkText = "MOT tests") private WebElement motTest;

    @FindBy(id = "action-resume-mot-test") private WebElement resumeReInspection;

    @FindBy(partialLinkText = "site information") private WebElement detailedSiteInfo;

    @FindBy(id = "user-profile") private WebElement userProfile;

    @FindBy(id = "role-VEHICLE-EXAMINER") private WebElement displayRole;

    @FindBy(partialLinkText = "AE information") private WebElement listAllAEs;

    @FindBy(partialLinkText = "vehicle information") private WebElement vehicleInformation;

    @FindBy(partialLinkText = "Record Contingency Test") private WebElement contingencyTest;

    @FindBy(id = "action-start-user-search") private WebElement userSearch;

    public EnforcementHomePage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PageTitles.ENFORCEMENT_HOME_PAGE.getPageTitle());
    }

    public static EnforcementHomePage navigateHereFromLoginPage(WebDriver driver, Login login) {
        LoginPage loginPage = new LoginPage(driver);
        return loginPage.loginAsEnforcementUser(login);
    }

    public SearchVehicleInformationPage clickVehicleInformation() {
        vehicleInformation.click();
        return new SearchVehicleInformationPage(driver);
    }

    public EnforcementVTSSearchPage clickMOTLink() {
        motTest.click();
        return new EnforcementVTSSearchPage(driver);
    }

    public VtsNumberEntryPage goToVtsNumberEntryPage() {
        motTest.click();
        return new VtsNumberEntryPage(driver);
    }

    public void clickResumeReInspection() {
        resumeReInspection.click();
    }

    public SiteInformationSearchPage clickSiteInformationLink() {
        detailedSiteInfo.click();
        return new SiteInformationSearchPage(driver);
    }

    public SearchVehicleInformationPage clickVehicleInformationLink() {
        vehicleInformation.click();
        return new SearchVehicleInformationPage(driver);
    }

    public VtsNumberEntryPage clickDetailedSiteInfo() {
        detailedSiteInfo.click();
        return new VtsNumberEntryPage(driver);
    }

    public SearchForAePage clickListAllAEs() {
        listAllAEs.click();
        return new SearchForAePage(driver);
    }

    public ContingencyTestPage clickListContingencyTests() {
        contingencyTest.click();
        return new ContingencyTestPage(driver);
    }

    public String getDisplayRole() {
        return displayRole.getText();
    }

}
