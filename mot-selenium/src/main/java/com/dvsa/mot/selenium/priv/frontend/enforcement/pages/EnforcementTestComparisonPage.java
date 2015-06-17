package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;


public class EnforcementTestComparisonPage extends BasePage {
    @FindBy(id = "record_assessment_button") private WebElement recordAssessmentButton;

    @FindBy(id = "info-popup") private WebElement scorePopup;

    @FindBy(id = "caseOutcome") private WebElement caseOutcome;

    @FindBy(xpath = "//textarea[@class=\"form-control flexible-vert justification NT 2-4-G-2\"]") private WebElement
            addText2JustificationBox1;

    @FindBy(xpath = "//textarea[@class=\"form-control flexible-vert justification NT 3-5-1g\"]") private WebElement
            addText2JustificationBox2;

    @FindBy(xpath = "//textarea[@class=\"form-control flexible-vert justification ER 3-5-1g\"]") private WebElement
            addText2VeJustificationBox1;

    @FindBy(xpath = "//textarea[@class=\"form-control flexible-vert justification NT 4-1-E-1\"]") private WebElement
            addText2JustificationBox4;

    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[2]/td[5]/textarea")
    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[2]/td[5]/div/textarea")
    @FindBy(xpath = "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[2]/td[5]/div/textarea")
    private WebElement NtJustificationBox;

    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[6]/td[5]/textarea")
    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[6]/td[5]/div/textarea")
    @FindBy(xpath = "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[6]/td[5]/div/textarea")
    private WebElement NtJustificationBox2;

    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[4]/td[5]/textarea")
    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[4]/td[5]/div/textarea")
    @FindBy(xpath = "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[4]/td[5]/div/textarea")
    private WebElement VeJustificationBox;

    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[6]/td[5]/textarea")
    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[6]/td[5]/div/textarea")
    @FindBy(xpath = "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[6]/td[5]/div/textarea")
    private WebElement VeJustificationBox1;

    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[8]/td[5]/textarea")
    //@FindBy(xpath = "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[8]/td[5]/div/textarea")
    @FindBy(xpath = "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[8]/td[5]/div/textarea")
    private WebElement VeJustificationBox2;

    //@FindBy(xpath="html/body/div[2]/div[4]/div/div/form/table/tbody/tr[8]/td[5]/textarea")
    @FindBy(xpath = "html/body/div[2]/div[4]/div/div/form/table/tbody/tr[8]/td[5]/div/textarea")
    private WebElement VeJustificationBox3;

    @FindBy(id = "finalJustification") private WebElement finalJustificationTextBox;


    //@FindBy(xpath = "html/body/div[2]/div[3]/div/div/form/table/tbody/tr[2]/td[1]/strong")
    @FindBy(xpath = "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[2]/td/strong")
    private WebElement finalResultFail;

    //@FindBy(xpath = "html/body/div[2]/div[3]/div/div/form/table/tbody/tr[8]/td[1]/strong")
    //@FindBy(xpath = "html/body/div[2]/div[3]/div/div/form/table/tbody/tr[4]/td[1]/strong")
    @FindBy(xpath = "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[8]/td/strong")
    private WebElement finalResultPRS;

    //@FindBy(xpath = "html/body/div[2]/div[3]/div/div/form/table/tbody/tr[6]/td[1]/strong")
    @FindBy(xpath = "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[4]/td/strong")
    private WebElement finalResultAdvisory;

    @FindBy(xpath = "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[2]/td[4]/select")
    private WebElement category;

    @FindBy(xpath = "/html/body/div[3]/div/div/div[3]/div/div/form/table/tbody/tr[2]/td[3]/select")
    private WebElement decision;


    @FindBy(id = "view") private WebElement btnViewCompareTestSection;


    @FindBy(id = "comp1") private WebElement txtboxVETestId;


    @FindBy(id = "comp2") private WebElement txtboxTesterTestId;


    @FindBy(id = "compare") private WebElement btnCompare;

    @FindBy(id = "swap") private WebElement btnSwap;

    //@FindBy(xpath="html/body/div[2]/div[6]/div/div/div/table/tbody/tr[1]/td[4]/a")
    @FindBy(xpath = "id('listMOTs')/tbody/tr[1]/td[4]/a") private WebElement veMOTTestId;

    //@FindBy(xpath="html/body/div[2]/div[6]/div/div/div/table/tbody/tr[2]/td[4]/a")
    @FindBy(xpath = "id('listMOTs')/tbody/tr[2]/td[4]/a") private WebElement testerMOTTestId;


    //@FindBy(xpath="html/body/div[2]/div[6]/div/div/div/table/tbody/tr[2]/td[4]/a")
    @FindBy(xpath = "id('listMOTs')/tbody/tr[2]/td[4]/a") private WebElement veNONMOTTestId;


    //@FindBy(xpath="html/body/div[2]/div[6]/div/div/div/table/tbody/tr[1]/td[4]/a")
    @FindBy(xpath = "id('listMOTs')/tbody/tr[1]/td[4]/a") private WebElement testerNONMOTTestId;

    @FindBy(xpath = "//th[text()='Test']") private WebElement sortTestcol;

    @FindBy(linkText = "MOT tests") private WebElement motTestCompareLink;

    @FindBy(id = "type") private WebElement searchType;

    @FindBy(id = "vts-search") private WebElement searchVTS;

    @FindBy(className = "form-control") private WebElement filterBox;

    public EnforcementTestComparisonPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public void clickRecordAssessmentButton() {
        recordAssessmentButton.click();
    }

    public void clickCompareMOTestLink() {

        motTestCompareLink.click();
    }

    public void newSelectDropdown(String text) {

        Select dropDown = new Select(searchType);
        dropDown.selectByVisibleText(text);

    }

    public EnforcementTestComparisonPage selectDropdown(String id, String text) {

        Select dropDown = new Select(driver.findElement(By.xpath(id)));
        dropDown.selectByVisibleText(text.toString());
        return this;

    }

    public void searchByVTS(String text, String motNUM, Vehicle vehicle) {
        searchVTS.sendKeys(text);
        searchVTS.submit();
        filterBox.sendKeys(vehicle.carReg);
        WebElement viewTest = driver.findElement(By.id("mot-" + motNUM));
        viewTest.click();
    }

    public boolean verifyCategory() {
        return category.isEnabled();
    }

    public String getCategoryText() {
        Select dropDown = new Select(category);
        return dropDown.getFirstSelectedOption().getText();
    }

    public boolean checkDropDownText(String id, String text) {
        boolean result = true;
        Select dropDown = new Select(driver.findElement(By.xpath(id)));
        dropDown.selectByVisibleText(text);

        return result;
    }

    public String getOutcomeText(){
        Select dropDown = new Select(driver.findElement(By.id("caseOutcome")));
        return dropDown.getFirstSelectedOption().getText();
    }

    public boolean selectScoreInformationIcon() {
        driver.findElement(By.id("info-popup")).click();
        new Actions(driver).moveToElement(driver.findElement(By.id("info-popup"))).click()
                .perform();

        return scorePopup.isDisplayed();
    }

    public EnforcementTestComparisonPage addTextToNtJustificationBox(String text) {
        NtJustificationBox.sendKeys(text);
        return this;
    }

    public void addTextToVeJustificationBox(String text) {
        VeJustificationBox.sendKeys(text);

    }

    /**
     * *************************************************************************
     * <p/>
     * *****	Story 1441- Non Mot Test comparison methods   ******
     * <p/>
     * **************************************************************************
     */

    public EnforcementTestComparisonPage clickViewCompareSection() {

        btnViewCompareTestSection.click();
        waitForElementToBeVisible(veNONMOTTestId, 5);
        return this;

    }

    public EnforcementTestComparisonPage enterMOTVETestId() {

        veMOTTestId.click();
        return this;

    }

    public EnforcementTestComparisonPage enterMOTTesterTestId() {

        testerMOTTestId.click();
        return this;

    }

    public void clickonCompare() {

        btnCompare.click();

    }

    public void addText2JustificationBox1(String justification) {

        addText2JustificationBox1.sendKeys(justification);
    }

    public void addText2JustificationBox2(String justification) {

        addText2JustificationBox2.sendKeys(justification);
    }

    public void addText2VeJustificationBox1(String justification) {

        addText2VeJustificationBox1.sendKeys(justification);
    }

    public void addText2JustificationBox4(String justification) {

        addText2JustificationBox4.sendKeys(justification);
    }
}

	


