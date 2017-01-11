package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.model.mot.Defect;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.helper.PageInteractionHelper;

import java.util.concurrent.TimeUnit;

public abstract class AbstractReasonsForRejectionPage extends Page {

    @FindBy(id = "validation-message--success") protected WebElement validationMessage;
    @FindBy(xpath = ".//*[@id='rfrList']//*[@class='defect__title']") protected WebElement reasonForRejectionTitle;
    @FindBy(xpath = "//*[@id='rfrList']//a[contains(., 'Remove')]") protected WebElement removeDefectLink;
    @FindBy(xpath = "//*[@id='rfrList']//a[contains(., 'Edit')]") protected WebElement editDefectLink;
    @FindBy(id="rfrList") private WebElement rfrList;

    public AbstractReasonsForRejectionPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    public RemoveDefectPage navigateToRemoveDefectPage(Defect defect) {
        removeDefectLink.click();
        return new RemoveDefectPage(driver, defect.getAddOrRemovalType());
    }

    public EditDefectPage navigateToEditDefectPage(Defect defect) {
        editDefectLink.click();
        return new EditDefectPage(driver, defect.getAddOrRemovalType());
    }

    public boolean isDefectInReasonsForRejection(Defect defect) {
        return reasonForRejectionTitle.isDisplayed() && reasonForRejectionTitle.getText().contains(defect.getDefectName());
    }

    public boolean isDefectRemovedSuccessMessageDisplayed(Defect defect) {
        return validationMessage.getText().equals(
                String.format("This %s has been removed:\n%s", defect.getAddOrRemovalType(), defect.getAddOrRemoveName()));
    }

    public boolean isDefectAddedSuccessMessageDisplayed(Defect defect) {
        return validationMessage.getText().equals(
                String.format("This %s has been added:\n%s", defect.getAddOrRemovalType(), defect.getAddOrRemoveName()));
    }

    public boolean isDefectEditSuccessMessageDisplayed(Defect defect) {
        return validationMessage.getText().equals(
                String.format("This %s has been edited:\n%s", defect.getAddOrRemovalType(), defect.getAddOrRemoveName()));
    }

    public boolean isDefectDangerous(Defect defect) {
        return isDangerousShownForDefect(defect) && defect.getIsDangerous();
    }

    private boolean isDangerousShownForDefect(Defect defect) {
        return PageInteractionHelper.isElementDisplayed(By.xpath(String.format(
                "//*[@id='rfrList']//strong[contains(text(), '%s')]/../span[contains(@class, 'defect__is-dangerous')]",
                defect.getAddOrRemoveName())));
    }
}
