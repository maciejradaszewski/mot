package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;
import org.testng.Assert;

public class RetestSummaryPage extends BasePage {

    @FindBy(partialLinkText = "go back") private WebElement goBack;

    @FindBy(id = "motTestType") private WebElement motTestType;

    @FindBy(id = "start_inspection_button") private WebElement startInspectionButton;

    public RetestSummaryPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PageTitles.MOT_TEST_SUMMARY_PAGE.getPageTitle());
    }

    public MotTestPage selectTestType(String testType) {

        Select select = new Select(motTestType);
        select.selectByVisibleText(testType);
        startInspectionButton.click();
        return new MotTestPage(driver, PageTitles.MOT_REINSPECTION_TEST_ENTRY_PAGE.getPageTitle());
    }

    public boolean verifyMotTestTypeDropDownBox() {
        return isElementDisplayed(motTestType);
    }

    public boolean verifyStartInspectionButton() {
        return isElementDisplayed(startInspectionButton);
    }

    public RetestSummaryPage verifyReInspectionForVE() {
        Assert.assertTrue(verifyMotTestTypeDropDownBox());
        Assert.assertTrue(verifyStartInspectionButton());
        return new RetestSummaryPage(driver);
    }

    public RetestSummaryPage verifyReInspectionForAreaAdmin() {
        Assert.assertFalse(verifyMotTestTypeDropDownBox());
        Assert.assertFalse(verifyStartInspectionButton());
        return new RetestSummaryPage(driver);
    }
}
