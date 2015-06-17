package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

public class ReInspectionCompletePage extends BasePage {

    @FindBy(id = "compareTestResults") private WebElement compareTestsButton;

    @FindBy(id = "reinspection-outcome") private WebElement reInspectionOutcomeBox;

    @FindBy(id = "reinspection-outcome_0") private WebElement agreeFullyWithTestResult;

    @FindBy(id = "reinspection-outcome_1") private WebElement resultCorrectButAdvisoryWarranted;

    @FindBy(id = "reinspection-outcome_2") private WebElement resultIncorrect;

    @FindBy(id = "reinspection-outcome_3") private WebElement otherEnterDetailsInSectionC;

    @FindBy(id = "concludingRemarks") private WebElement ConcludingRemarksTextBox;

    @FindBy(id = "proceed_button") private WebElement ProceedButton;

    @FindBy(className = "col-sm-4") private WebElement OverallOutcome;

    // @FindBy (xpath = "/html/body/div[2]/div[3]/div/dl/dd")
    // @FindBy(xpath="html/body/div[2]/form/div[1]/div[1]/dl[1]/dt")
    @FindBy(id = "hdr-col1-dt0") private WebElement DateOfReInspection;

    // @FindBy (xpath = "/html/body/div[2]/div[3]/div/dl[2]/dd")
    @FindBy(id = "hdr-col1-dt1") private WebElement DateOfTestersTest;

    //@FindBy (xpath = "/html/body/div[2]/div[3]/div/dl[3]/dd")
    @FindBy(id = "hdr-col2-dt2") private WebElement VT2023TestNumber;

    //@FindBy (xpath = "/html/body/div[2]/div[3]/div[2]/dl/dd")
    @FindBy(id = "hdr-col2-dt0") private WebElement VTSDetails;

    //@FindBy (xpath = "/html/body/div[2]/div[3]/div[2]/dl[2]/dd")
    @FindBy(id = "hdr-col2-dt1") private WebElement ActivityConducted;

    //@FindBy (xpath = "/html/body/div[2]/div[3]/div[3]/dl/dd")
    @FindBy(id = "hdr-col3-dt0") private WebElement AuthorisedExaminer;

    //@FindBy (xpath = "/html/body/div[2]/div[3]/div[3]/dl[2]/dd")
    @FindBy(id = "hdr-col3-dt1") private WebElement ReInspectionTestNumber;

    // @FindBy (xpath = "/html/body/div[2]/div[4]/div/dl/dd")
    @FindBy(id = "veh-col1-dt0") private WebElement VRM;

    //@FindBy (xpath = "/html/body/div[2]/div[4]/div/dl[2]/dd")
    @FindBy(id = "veh-col1-dt1") private WebElement TesterID;

    @FindBy(id = "veh-col1-dt2") private WebElement onePersonTest;

    //@FindBy (xpath = "/html/body/div[2]/div[4]/div[2]/dl/dd")
    @FindBy(id = "veh-col2-dt0") private WebElement VIN;

    //@FindBy (xpath = "/html/body/div[2]/div[4]/div[2]/dl[2]/dd")
    @FindBy(id = "veh-col2-dt1") private WebElement TypeOfTesterInspection;

    @FindBy(id = "veh-col2-dt2") private WebElement TypeOfTesterReInspection;

    // @FindBy (xpath = "/html/body/div[2]/div[4]/div[3]/dl/dd")
    @FindBy(id = "veh-col3-dt0") private WebElement MakeModel;

    //@FindBy (xpath = "/html/body/div[2]/div[4]/div[3]/dl[2]/dd")
    // @FindBy (xpath = "/html/body/div[2]/div[4]/div[3]/dl/dd")
    @FindBy(id = "veh-col3-dt1") private WebElement TimeElapsed;

    // @FindBy (xpath = "/html/body/div[2]/div[4]/div[3]/dl[3]/dd")
    // @FindBy (xpath = "/html/body/div[2]/div[4]/div[3]/dl/dd")
    @FindBy(id = "veh-col3-dt2") private WebElement MilageDiff;

    // @FindBy(xpath="//table[@classname='table table-unstyled']/tbody/tr/td[1]")
    //@FindBy(xpath="html/body/div[2]/form/div[3]/div/table/tbody/tr[1]/td[1]")
    @FindBy(xpath = "id('reinspection-form')/div[3]/div/table/tbody/tr[1]/td[1]") private WebElement
            SectionA;

    //@FindBy (xpath = "html/body/div[2]/form/div[3]/div/table/tbody/tr[3]/td[1]")
    @FindBy(xpath = "id('reinspection-form')/div[3]/div/table/tbody/tr[3]/td[1]") private WebElement
            SectionB;

    //@FindBy (xpath = "/html/body/div[2]/div[6]/div/dl")
    //@FindBy(xpath="html/body/div[2]/form/div[4]/div[1]/dl/dt")
    @FindBy(xpath = "id('reinspection-form')/div[4]/div[1]/dl/dt") private WebElement SectionC;

    @FindBy(id = "plus1") private WebElement AddDarButton1;

    @FindBy(id = "plus2") private WebElement AddDarButton2;

    @FindBy(id = "plus3") private WebElement AddDarButton3;

    @FindBy(id = "plus4") private WebElement AddDarButton4;

    @FindBy(id = "plus5") private WebElement AddDarButton5;

    @FindBy(id = "inputDar1") private WebElement AddDarOneTextbox;

    @FindBy(id = "inputDar2") private WebElement AddDarTwoTextbox;

    @FindBy(id = "inputDar3") private WebElement AddDarThreeTextbox;

    @FindBy(id = "inputDar4") private WebElement AddDarFourTextbox;

    @FindBy(id = "inputDar5") private WebElement AddDarFiveTextbox;

    @FindBy(id = "inputPos1") private WebElement AddDarPosOneTextbox;

    @FindBy(id = "inputPos2") private WebElement AddDarPosTwoTextbox;

    @FindBy(id = "inputPos3") private WebElement AddDarPosThreeTextbox;

    @FindBy(id = "inputPos4") private WebElement AddDarPosFourTextbox;

    @FindBy(id = "inputPos5") private WebElement AddDarPosFiveTextbox;

    //private final WebDriver driver;

    public ReInspectionCompletePage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public void clickCompareTestResults() {
        compareTestsButton.click();
    }

    public void checkTitle() {

    }

    public boolean ReinspectionOutcomeAgree(String text) {

        String text1 = agreeFullyWithTestResult.getText();

        return text1.equals(text);
    }

    public boolean checkConcludingRemarksText(String text) {
        return ConcludingRemarksTextBox.getText() == text;
    }

    public void setReinspectionOutcome(String text) {
        Select dropDown = new Select(reInspectionOutcomeBox);
        dropDown.selectByVisibleText(text);
    }

    public void addConcludingRemarks(String text) {
        ConcludingRemarksTextBox.sendKeys(text);
    }

    public boolean checkOverallOutcome(String text) {
        return OverallOutcome.getText() == text;
    }

    public boolean checkReportPageIsDisplay() {
        return DateOfReInspection.isDisplayed() && DateOfTestersTest.isDisplayed()
                && VT2023TestNumber.isDisplayed() && VTSDetails.isDisplayed() && ActivityConducted
                .isDisplayed() && AuthorisedExaminer.isDisplayed() && onePersonTest.isDisplayed()
                && ReInspectionTestNumber.isDisplayed() && VRM.isDisplayed() && TesterID
                .isDisplayed() && VIN.isDisplayed() && TypeOfTesterInspection.isDisplayed()
                && MakeModel.isDisplayed() && TimeElapsed.isDisplayed() && MilageDiff.isDisplayed()
                && reInspectionOutcomeBox.isDisplayed() && SectionA.isDisplayed() && SectionB
                .isDisplayed() && SectionC.isDisplayed() && ConcludingRemarksTextBox.isDisplayed();
    }

    public void addDar(String number) {
        driver.findElement(By.id("plus" + number)).click();
    }

    public void RemoveDar(String number) {
        driver.findElement(By.id("minus" + number)).click();
    }

    public void AddFirstDarName(String text) {
        AddDarOneTextbox.sendKeys(text);
    }

    public void AddFirstDarPosition(String text) {
        AddDarPosOneTextbox.sendKeys(text);
    }

    public void AddSecondDarName(String text) {
        AddDarTwoTextbox.sendKeys(text);
    }

    public void AddSecondtDarPosition(String text) {
        AddDarPosTwoTextbox.sendKeys(text);
    }

    public void AddThirdDarName(String text) {
        AddDarThreeTextbox.sendKeys(text);
    }

    public void AddThirdDarPosition(String text) {
        AddDarPosThreeTextbox.sendKeys(text);
    }

    public void AddFourthDarName(String text) {
        AddDarFourTextbox.sendKeys(text);
    }

    public void AddFourthDarPosition(String text) {
        AddDarPosFourTextbox.sendKeys(text);
    }

    public void AddFifthDarName(String text) {
        AddDarFiveTextbox.sendKeys(text);
    }

    public void AddFifthDarPosition(String text) {
        AddDarPosFiveTextbox.sendKeys(text);
    }

    public void clickProceedButon() {
        ProceedButton.click();
    }
}
