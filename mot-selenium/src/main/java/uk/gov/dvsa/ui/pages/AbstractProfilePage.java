package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public abstract class AbstractProfilePage extends Page {

    @FindBy(id = "add-edit-driving-licence-link") private WebElement addEditDrivingLicenceLink;
    @FindBy(id = "person-driving-licence") private WebElement personDrivingLicence;

    public AbstractProfilePage(MotAppDriver driver) {
        super(driver);
    }

    public boolean drivingLicenceIsDisplayed() {
        try {
            return personDrivingLicence.isDisplayed();
        } catch (NoSuchElementException exception) {
            return false;
        }
    }

    public boolean addEditDrivingLicenceLinkExists() {
        try {
            return addEditDrivingLicenceLink.isDisplayed();
        } catch (NoSuchElementException exception) {
            return false;
        }
    }
}
