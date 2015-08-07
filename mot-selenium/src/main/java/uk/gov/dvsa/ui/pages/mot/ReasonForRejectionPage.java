package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import uk.gov.dvsa.domain.model.mot.Fault;
import uk.gov.dvsa.framework.config.Configurator;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.mot.modal.ManualAdvisoryModalPage;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.mot.modal.PrsLocationModalPage;

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

    @FindBy(id = "mot-header-details-rfr-done")
    private WebElement doneButton;

    @FindBy(id = "manual-advisory") private WebElement manualAdvisory;

    @FindBy(id = "rfrCount") private WebElement rfrCount;


    private By prsButtons = By.cssSelector("[data-type='PRS']");
    private static final String PAGE_TITLE = "Add PRS, failures and advisories";

    //Locators not created with Page factory
    By modalTitle = By.className("modal-title");
    By submitModal = By.name("submit");

    public ReasonForRejectionPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
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
