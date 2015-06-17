package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.Utilities;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class EnforcementReInspectionTestCompletePage extends BasePage {


    //Page Elements
    @FindBy(id = "compareTestResults") public WebElement compareTestResults;

    @FindBy(id = "reprint-certificate") private WebElement reprintCertificate;

    public EnforcementReInspectionTestCompletePage(WebDriver driver) {
        super(driver);
        checkTitle(PageTitles.MOT_REINSPECTION_TEST_COMPLETE_PAGE.getPageTitle());
    }

    public boolean isReprintCertificateButtonPresent() {

        return reprintCertificate.isDisplayed();
    }

    public void clickCompareTestsButton() {
        compareTestResults.click();
    }

    public boolean verifyCompareTestsButton() {
        return compareTestResults.isDisplayed();
    }

    public String getPrintCertificateUrl() {
        return reprintCertificate.getAttribute("href");
    }

    public String generateNewVT32FileName()
    {
        return "VT32"+ Utilities.getSystemDateAndTime();
    }
}
