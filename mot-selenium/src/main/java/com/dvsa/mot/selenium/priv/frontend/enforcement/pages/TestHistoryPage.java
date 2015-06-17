package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.testng.Assert;

public class TestHistoryPage extends BasePage {
    public static final String PAGE_TITLE = "Vehicle MOT test history";

    @FindBy(partialLinkText = "go back") private WebElement goBack;

    @FindBy(partialLinkText = "View")
    //@FindBy(xpath = "id('listMOTs')//tbody//tr[1]//td[3]")
    private WebElement summaryLink;

    @FindBy(xpath = "//table[@id='listMOTs']//a[text()='In progress']") private WebElement
            clickOnInProgressLink;

    @FindBy(xpath = "//table[@id='listMOTs']//a[text()='View']") private WebElement
            clickOnViewFailLink;

    public TestHistoryPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public TestHistoryPage verifyPageTitle() {
        Assert.assertEquals(title.getText(), PAGE_TITLE, "Assert the page title is correct");
        return new TestHistoryPage(driver);
    }

    public RetestSummaryPage clickSummaryLink() {
        summaryLink.click();
        return new RetestSummaryPage(driver);
    }

    public RetestSummaryPage viewMOTTest(String motTestNumber) {

        WebElement testLink = driver.findElement(By.id("mot-" + motTestNumber));
        testLink.click();
        return new RetestSummaryPage(driver);
    }

    public MotTestSummaryPage clickOnInProgressTest() {
        clickOnInProgressLink.click();
        return new MotTestSummaryPage(driver);
    }

    public MotTestSummaryPage clickOnViewFailResultTest() {
        clickOnViewFailLink.click();
        return new MotTestSummaryPage(driver);
    }
}
