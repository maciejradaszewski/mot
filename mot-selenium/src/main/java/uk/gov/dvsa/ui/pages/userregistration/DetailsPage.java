package uk.gov.dvsa.ui.pages.userregistration;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormDataHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class DetailsPage extends Page {

    private static final String PAGE_TITLE = "Your details";

    @FindBy(id = "firstName") private WebElement firstName;

    @FindBy(id = "middleName") private WebElement middleName;

    @FindBy(id = "lastName") private WebElement lastName;

    @FindBy(id = "phone") private WebElement telephoneNumber;

    @FindBy(id = "continue") private WebElement continueToNextPage;

    public DetailsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public AddressPage clickContinue() {
        continueToNextPage.click();
        return new AddressPage(driver);
    }

    public DetailsPage enterYourDetails(String name, String surname, String telephone)
    {
        FormDataHelper.enterText(firstName, name);
        FormDataHelper.enterText(lastName, surname);
        FormDataHelper.enterText(telephoneNumber, telephone);

        return this;
    }
}
