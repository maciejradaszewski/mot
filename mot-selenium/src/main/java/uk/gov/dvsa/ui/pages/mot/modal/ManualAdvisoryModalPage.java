package uk.gov.dvsa.ui.pages.mot.modal;

import com.dvsa.mot.selenium.framework.Configurator;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.model.mot.Advisory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.mot.ReasonForRejectionPage;

public class ManualAdvisoryModalPage extends Page {

    @FindBy(id = "modal-rfr-title-0") protected WebElement modalTitle;

    @FindBy(id = "lateral-dd-0") protected WebElement lateral;

    @FindBy(id = "longitudinal-dd-0") protected WebElement longitudinal;

    @FindBy(id = "vertical-dd-0") protected WebElement vertical;

    @FindBy(id = "description-0") protected WebElement description;

    @FindBy(css = "input[id=dangerous]") protected WebElement dangerousFailure;

    @FindBy(id = "rfr-submit-0") protected WebElement addButton;

    @FindBy(id = "rfr-cancel-0") protected WebElement cancelButton;

    @FindBy(className = "validation-summary") protected WebElement errorMessages;

    @FindBy(id = "info-message") protected WebElement infoMessage;

    private static final String PAGE_TITLE = "Manual Advisory";

    public ManualAdvisoryModalPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        PageInteractionHelper.waitForElementToBeVisible(modalTitle, Configurator.defaultWebElementTimeout);
        return PageInteractionHelper.verifyTitle(modalTitle.getText(), PAGE_TITLE);
    }

    public ReasonForRejectionPage addManualAdvisory() {
        FormCompletionHelper.selectFromDropDownByValue(lateral, String.valueOf(Advisory.Lateral.nearside));
        FormCompletionHelper.selectFromDropDownByValue(longitudinal, String.valueOf(Advisory.Longitudinal.rear));
        FormCompletionHelper.selectFromDropDownByValue(vertical, String.valueOf(Advisory.Vertical.lower));

        FormCompletionHelper.enterText(description, Advisory.DESCRIPTION);
        FormCompletionHelper.selectInputBox(dangerousFailure);

        addButton.click();
        PageInteractionHelper.waitForAjaxToComplete();
        return new ReasonForRejectionPage(driver);
    }
}
