package uk.gov.dvsa.ui.pages.dvsamanageroles;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;


public class UserSearchPage extends Page{

    private static final String PAGE_TITLE = "User management";
    @FindBy(id = "username") private WebElement usernameField;
    @FindBy(xpath = "//button[contains(.,'Search')]") private WebElement searchButton;

    public UserSearchPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public UserSearchResultsPage enterUsernameIntoSearchFieldAndClickSearch(String username){
        FormCompletionHelper.enterText(usernameField, username);
        searchButton.click();
        return new UserSearchResultsPage(driver);
    }
}
