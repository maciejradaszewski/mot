package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

import java.util.List;

public class EnforcementMotTestSummaryPage extends BasePage {

    //Page Elements
    @FindBy(id = "testStationDetails") public WebElement testStationDetails;

    @FindBy(id = "testInformation") public WebElement testInformation;

    @FindBy(id = "vehicleSummary") public WebElement vehicleSummary;

    //@FindBy(id = "brakeResultsDetail")
    @FindBy(id = "brakeResults") public WebElement brakeResultsDetail;

    @FindBy(id = "siteNumber") public WebElement siteNumber;

    @FindBy(id = "siteAddress") public WebElement siteAddress;

    @FindBy(id = "motTestNumber") public WebElement motTestNumber;

    @FindBy(id = "issueDate") public WebElement issueDate;

    @FindBy(id = "motTestDuration") public WebElement motTestDuration;

    @FindBy(id = "registrationNumber") public WebElement registrationNumber;

    @FindBy(id = "vinChassisNumber") public WebElement vinChassisNumber;

    @FindBy(id = "testClass") public WebElement testClass;

    @FindBy(id = "aproxFirstUse") public WebElement aproxFirstUse;

    @FindBy(id = "make") public WebElement vehicleMake;

    @FindBy(id = "model") public WebElement vehicleModel;

    @FindBy(id = "colour") public WebElement vehicleColour;

    @FindBy(id = "odometerReading") public WebElement odometerReading;

    @FindBy(id = "fuelType") public WebElement fuelType;

    @FindBy(id = "prses") public WebElement prses;

    @FindBy(id = "advisoryText") public WebElement advisoryText;

    @FindBy(id = "confirmTestTypeText") public WebElement confirmTestTypeText;

    @FindBy(name = "motTestType") public WebElement motTestType;

    @FindBy(id = "start_inspection_button") public WebElement startInspectionButton;

    @FindBy(id = "confirm_test_result") public WebElement finishReInspection;

    @FindBy(id = "info-message") public WebElement testResultText;

    @FindBy(id = "partialReasons") public WebElement partialReasons;

    @FindBy(id = "partialItemsMissed") public WebElement partialItemsMissed;

    @FindBy(id = "complaintRef") public WebElement complaintRefTextBox;

    @FindBy(id = "logout") private WebElement logout;

    @FindBy(id = "fails") private WebElement fails;

    public EnforcementMotTestSummaryPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    private boolean testStationDetailsPresent() {
        return testStationDetails.isDisplayed();
    }

    public void logout() {
        logout.click();
    }

    private boolean testInformationlsPresent() {
        return testInformation.isDisplayed();
    }

    private boolean vehicleSummarylsPresent() {
        return brakeResultsDetail.isDisplayed();
    }

    private boolean brakeResultsDetaillsPresent() {
        return brakeResultsDetail.isDisplayed();
    }

    public boolean checkEnforcementSummaryPageDisplay() {
        return testStationDetailsPresent() && testInformationlsPresent()
                && vehicleSummarylsPresent() && brakeResultsDetaillsPresent();
    }

    public String checkTextOfTestType() {
        WebElement testType = motTestType.findElement(By.tagName("option"));
        return testType.getText().trim();
    }

    public boolean checkComplaintRefTextBoxDisplayed() {
        return complaintRefTextBox.isDisplayed();
    }

    public EnforcementMotTestSummaryPage setTypeOfTest(String text) {
        Select selectTargetedReInspection = new Select(driver.findElement(By.name("motTestType")));
        selectTargetedReInspection.selectByVisibleText(text);
        return new EnforcementMotTestSummaryPage(driver);
    }

    public String getTypeOfTest(){
        Select selectTargetedReInspection = new Select(driver.findElement(By.name("motTestType")));
        return selectTargetedReInspection.getFirstSelectedOption().getText();
    }

    public MotTestPage startInspection() {
        startInspectionButton.click();
        return new MotTestPage(driver, PageTitles.MOT_REINSPECTION_PAGE.getPageTitle());
    }

    public void startInspectionInvertedAppeal() {
        startInspectionButton.click();
        //return new MotTestPage(driver);
    }

    public void enterComplaintReferenceNumber(String text) {
        complaintRefTextBox.sendKeys(text);
    }

    public boolean failuresContain(String reasonDescription) {
        List<WebElement> elements = fails.findElements(By.tagName("li"));

        for (WebElement element : elements) {
            if (element.getText().contains(reasonDescription)) {
                return true;
            }
        }

        return false;
    }
}
