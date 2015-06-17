package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.util.List;

public class DuplicateReplacementCertificatePage extends BasePage {

    private static String REPLACEMENT_CERT_PAGE =
            PageTitles.DUPLICATE_OR_REPLACEMENT_CERTIFICATE_PAGE.getPageTitle();
    private static String PASS_TEST_TEXT = Text.TEXT_STATUS_PASS;
    private static String FAIL_TEST_TEXT = Text.TEXT_STATUS_FAIL;

    @FindBy(xpath = "((//*[@class='row'])[contains(.,'Pass')]//button[text()='Edit'])[1]")
    private WebElement editOnPass;

    @FindBy(xpath = "((//*[@class='row'])[contains(.,'Fail')]//button[text()='Edit'])[1]")
    private WebElement editOnFail;


    @FindBy(id = "return_to_replacement_search") private WebElement returnButton;

    @FindBy(className = "validation-message") private WebElement validationMessage;

    public DuplicateReplacementCertificatePage(WebDriver driver) {
        super(driver);
        checkTitle(REPLACEMENT_CERT_PAGE);
    }

    public static DuplicateReplacementCertificatePage navigateHereFromLoginPage(WebDriver driver,
            Login login, Vehicle vehicle) {
        return DuplicateReplacementCertificateSearchPage.navigateHereFromLandingPage(driver, login)
                .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg);
    }

    public ReplacementCertificateUpdatePage clickEditButtonPass() {
        editOnPass.click();
        return new ReplacementCertificateUpdatePage(driver);
    }

    public String getTestStatus(int position) {
        return driver.findElement(By.xpath(
                "((//*[@id='certificate-history'])[" + position + "])//*[contains(@id,'status-')]"))
                .getText();
    }

    public String getTestNumber(int position) {
        return driver.findElement(By.xpath(
                "((//*[@id='certificate-history'])[" + position + "])//*[contains(@id,'number-')]"))
                .getText();
    }

    public ReplacementCertificateUpdatePage clickEditButtonFail() {
        editOnFail.click();
        return new ReplacementCertificateUpdatePage(driver);
    }

    //TODO Use this method while mot test history is not ordered by finish time (desc). Use for 'teser' users. For 'dvsa admin users' could use clickEditButtonFail and clickEditButtonPassl
    public ReplacementCertificateUpdatePage clickFirstEditButton() {
        WebElement editButton =
                driver.findElement(By.xpath("((//*[@class='row'])//button[text()='Edit'])[1]"));
        editButton.click();
        return new ReplacementCertificateUpdatePage(driver);
    }

    public MOTTestResultPage clickViewByMOTNumber(String motNumber) {
        driver.findElement(By.id("view-" + motNumber)).click();
        return new MOTTestResultPage(driver);
    }

    public ReplacementCertificateUpdatePage clickEditByMOTNumber(String motNumber) {
        driver.findElement(By.id("edit-" + motNumber)).click();
        return new ReplacementCertificateUpdatePage(driver);
    }

    public boolean isReplacementCertificateEditButtonDisplayed(String motNumber) {
        List<WebElement> editButtons = findElementWithoutImplicitWaits(By.id("edit-" + motNumber));
        return editButtons.size() > 0;
    }

    public boolean isReplacementCertificateViewDisplayed() {
        List<WebElement> elements =
                findElementWithoutImplicitWaits(By.xpath("//a[text()='" + "View" + "']"));
        return elements.size() > 0;
    }

    public boolean isReplacementCertificateViewDisplayed(String motTestNumber) {
        List<WebElement> e = findElementWithoutImplicitWaits(By.id("view-" + motTestNumber));
        return e.size() > 0;
    }

    public DuplicateReplacementCertificateSearchPage returnToReplacementSearch() {
        returnButton.click();
        return new DuplicateReplacementCertificateSearchPage(driver);
    }

    private WebElement getFirstTestIssuedAtOtherVTSByStatus(String status) {
        return driver.findElement(By.xpath(
                "(//*[@id='historyOnOtherSite']//*[@class='row'])[contains(.,'" + status
                        + "')][1]"));
    }

    private WebElement getDuplicateLinkFromTestIssuedAtOtherVTS(WebElement testElement) {
        WebElement button = testElement.findElement(By.xpath(".//button[contains(@id,'view')]"));
        return button;
    }

    private WebElement getInputFormForTestIssuedAtOtherVTS(WebElement testElement) {
        String formId =
                getDuplicateLinkFromTestIssuedAtOtherVTS(testElement).getAttribute("data-target")
                        .replace("#", "");
        return driver.findElement(By.id(formId));
    }

    private DuplicateReplacementCertificatePage clickDuplicateOnFirstTestIssuedAtOtherVTSByState(
            String state) {
        WebElement test = getFirstTestIssuedAtOtherVTSByStatus(state);
        WebElement testInputs = getInputFormForTestIssuedAtOtherVTS(test);
        if (!testInputs.isDisplayed()) {
            getDuplicateLinkFromTestIssuedAtOtherVTS(test).click();
            waitForElementToBeVisible(testInputs, 5);
        }
        return this;
    }


    private void enterMotTestAndV5ConFirstTestIssuedAtOtherVTSAndSubmit(String state,
            String motTestNumber, String v5c) {

        clickDuplicateOnFirstTestIssuedAtOtherVTSByState(state);
        WebElement formElement =
                getInputFormForTestIssuedAtOtherVTS(getFirstTestIssuedAtOtherVTSByStatus(state));
        WebElement numberInput = new WebDriverWait(driver, 10)
                .until(ExpectedConditions.visibilityOf(findWebElement(By.name("number"))));
        WebElement v5cInput = formElement.findElement(By.name("v5c"));
        numberInput.clear();
        v5cInput.clear();

        if (motTestNumber != null) {
            numberInput.sendKeys(motTestNumber);
        }

        if (v5c != null) {
            v5cInput.sendKeys(v5c);
        }
        formElement.findElement(By.xpath("//*[contains(@id,'confirm')]")).click();
    }

    public MOTTestResultPage enterFieldsOnFirstPassTestIssuedAtOtherVTSAndSubmit(
            String motTestNumber, String v5c) {
        enterMotTestAndV5ConFirstTestIssuedAtOtherVTSAndSubmit(PASS_TEST_TEXT, motTestNumber, v5c);
        return new MOTTestResultPage(driver);
    }

    public MOTTestResultPage enterFieldsOnFirstFailTestIssuedAtOtherVTSAndSubmit(
            String motTestNumber, String v5c) {
        enterMotTestAndV5ConFirstTestIssuedAtOtherVTSAndSubmit(FAIL_TEST_TEXT, motTestNumber, v5c);
        return new MOTTestResultPage(driver);
    }

    public DuplicateReplacementCertificatePage enterFieldsOnFirstPassTestIssuedAtOtherVTSAndSubmitExpectingError(
            String motTestNumber, String v5c) {
        enterMotTestAndV5ConFirstTestIssuedAtOtherVTSAndSubmit(PASS_TEST_TEXT, motTestNumber, v5c);
        return new DuplicateReplacementCertificatePage(driver);
    }

    public DuplicateReplacementCertificatePage enterFieldsOnFirstFailTestIssuedAtOtherVTSAndSubmitExpectingError(
            String motTestNumber, String v5c) {
        enterMotTestAndV5ConFirstTestIssuedAtOtherVTSAndSubmit(FAIL_TEST_TEXT, motTestNumber, v5c);
        return new DuplicateReplacementCertificatePage(driver);
    }

    public String getValidationMessage() {
        return validationMessage.getText().trim();
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
