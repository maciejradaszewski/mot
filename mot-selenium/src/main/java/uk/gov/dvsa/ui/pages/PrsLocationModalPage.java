package uk.gov.dvsa.ui.pages;

import com.dvsa.mot.selenium.framework.Configurator;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.model.Advisory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class PrsLocationModalPage extends Page {
    private WebElement modalTitle;
    private WebElement lateral;
    private WebElement longitudinal;
    private WebElement vertical;
    private WebElement description;
    private WebElement addButton;
    private WebElement cancelButton;

    @FindBy(className = "validation-summary") private WebElement errorMessages;

    @FindBy(id = "info-message") private WebElement infoMessage;

    private static final String PAGE_TITLE = "PRS location";
    private String rfrId = "";

    public PrsLocationModalPage(MotAppDriver driver, String rfrId) {
        super(driver);
        this.rfrId = rfrId;
        initElements();
        selfVerify();
    }

    private void initElements(){
        modalTitle = driver.findElement(By.id(prepareLocatorWithRfrId("modal-rfr-title-%s")));
        lateral = driver.findElement(By.id(prepareLocatorWithRfrId("lateral-dd-%s")));
        longitudinal = driver.findElement(By.id(prepareLocatorWithRfrId("longitudinal-dd-%s")));
        vertical = driver.findElement(By.id(prepareLocatorWithRfrId("vertical-dd-%s")));
        addButton = driver.findElement(By.id(prepareLocatorWithRfrId("rfr-submit-%s")));
        cancelButton = driver.findElement(By.id(prepareLocatorWithRfrId("rfr-cancel-%s")));
        description = driver.findElement(By.id(prepareLocatorWithRfrId("description-%s")));
    }

    private String prepareLocatorWithRfrId(String id) {
        return String.format(id, rfrId);
    }

    public ReasonForRejectionPage addPrs() {
        FormCompletionHelper.selectFromDropDownByValue(lateral, String.valueOf(Advisory.Lateral.nearside));
        FormCompletionHelper.selectFromDropDownByValue(longitudinal, String.valueOf(Advisory.Longitudinal.front));
        FormCompletionHelper.selectFromDropDownByValue(vertical, String.valueOf(Advisory.Vertical.outer));
        FormCompletionHelper.enterText(description, "Horn Destroyed");

        addButton.click();
        PageInteractionHelper.waitForAjaxToComplete();
        return new ReasonForRejectionPage(driver);
    }

    @Override
    protected boolean selfVerify() {
        PageInteractionHelper.waitForElementToBeVisible(modalTitle, Configurator.defaultWebElementTimeout);
        return PageInteractionHelper.verifyTitle(modalTitle.getText(), PAGE_TITLE);
    }
}
