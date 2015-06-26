package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class VtsSearchForAUserPage extends Page {

    public static final String path = "/vehicle-testing-station/%s/search-for-person";
    private static final String PAGE_TITLE = "Search for a user";

    @FindBy(id = "search-button" ) private WebElement searchButton;

    @FindBy(id = "userSearchBox" ) private WebElement userSearchBoxInput;

    public VtsSearchForAUserPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public VtsSearchForAUserPage clickSearchButton() {
        searchButton.click();

        return this;
    }

    public VtsSearchForAUserPage fillUserSearchBoxInput(String userName) {
        userSearchBoxInput.sendKeys(userName);

        return this;
    }
}
