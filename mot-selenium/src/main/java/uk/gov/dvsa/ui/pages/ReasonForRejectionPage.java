package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.model.Fault;
import uk.gov.dvsa.framework.config.Configurator;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Random;

public class ReasonForRejectionPage extends Page {

    @FindBy(tagName = "a")
    private List<WebElement> links;

    @FindBy(className = "step")
    private WebElement stepInfo;

    @FindBy(id = "info-message")
    private WebElement infoMessage;

    @FindBy(linkText = "Manual")
    private WebElement manualButton;

    @FindBy(className = "modal-title")
    private WebElement rfrTitle;

    @FindBy(id = "reason-for-rejection-modal")
    private WebElement reasonsForRejection;

    @FindBy(id = "rfr-modal-close")
    private WebElement closeRFRList;

    private By searchForRfrField = By.id("rfr-search");

    @FindBy(id = "test-item-selector-btn-search")
    private WebElement searchButton;

    @FindBy(id = "rfr-remove")
    private WebElement removeRFR;

    @FindBy(id = "rfr-submit-1055")
    private WebElement submitModalFailureLocation;

    @FindBy(id = "rfr-submit-7080")
    private WebElement submitModalLampFailureLocation;

    @FindBy(id = "mot-header-details-rfr-done")
    private WebElement doneButton;

    @FindBy(id = "manual-advisory")
    private WebElement manualAdvisory;

    @FindBy(id = "rfr-modal-close")
    private WebElement closeRFRModalBox;

    @FindBy(id = "dangerous")
    private WebElement failureIsDangerousCheckbox;

    @FindBy(name = "submit")
    private WebElement submitFail;

    @FindBy(linkText = "Cancel")
    private WebElement cancelFail;

    @FindBy(id = "fail-rfr-1055")
    private WebElement failFlagToCustomer;

    @FindBy(id = "prs-rfr-1055")
    private WebElement prsFlagToCustomer;

    @FindBy(id = "advisory-rfr-7089")
    private WebElement advisoryFlagToCustomer;

    @FindBy(id = "fail-rfr-7088")
    private WebElement clickRFRMissing;

    @FindBy(id = "mot-header-details-rfr-done")
    private WebElement DoneAddingRfrs;

    @FindBy(id = "breadcrumb")
    private WebElement breadcrumb;

    @FindBy(id = "advisory-rfr-8291")
    private WebElement rfrsClickAdvisoryButton;

    @FindBy(id = "prs-rfr-8394")
    private WebElement rfrsClickPRSButton;

    @FindBy(id = "rfrCount")
    private WebElement rfrCount;

    private By prsButtons = By.cssSelector("[data-type='PRS']");
    private By prsDiv = By.cssSelector(".col-md-4");

    private Map<String, Integer> pageLinks = new HashMap<String, Integer>();

    private static final String PAGE_TITLE = "Reasons for rejection";

    //Locators not created with Page factory
    By modalTitle = By.className("modal-title");
    By submitModal = By.name("submit");

    public ReasonForRejectionPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        PageInteractionHelper.waitForElementToBeVisible(title, Configurator.defaultWebElementTimeout);
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    /**
     * Click Done when an RFR has been added successfully
     */

    public TestResultsEntryPage clickDone() {
        PageInteractionHelper.waitForElementToBeVisible(doneButton, Configurator.defaultFastWebElementTimeout);
        doneButton.click();
        return new TestResultsEntryPage(driver);
    }

    protected ReasonForRejectionPage addManualAdvisory() {
        manualAdvisory.click();

        ManualAdvisoryModalPage advisoryModalPage = new ManualAdvisoryModalPage(driver);
        advisoryModalPage.addManualAdvisory();

        return this;
    }

    protected ReasonForRejectionPage addPRS() {
        PageInteractionHelper.waitForPageToLoad();

        driver.findElement(searchForRfrField).sendKeys(Fault.HORN_CONTROL_MISSING.getDescription());
        searchButton.click();

        List<WebElement> prsButtonList = driver.findElements(prsButtons);
        int randomItemIndex = new Random().nextInt(prsButtonList.size());
        String locatorId = prsButtonList.get(randomItemIndex).getAttribute("data-rfrid");
        prsButtonList.get(randomItemIndex).click();

        PrsLocationModalPage modalPage = new PrsLocationModalPage(driver, locatorId);
        modalPage.addPrs();

        return this;
    }
}