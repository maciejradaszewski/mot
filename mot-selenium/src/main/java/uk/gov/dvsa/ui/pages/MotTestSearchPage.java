package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class MotTestSearchPage extends Page {

    public static final String path = "/mot-test-search";
    private static final String PAGE_TITLE = "Search for MOT tests by...";

    @FindBy(id = "type") private WebElement searchTypePrompt;

    @FindBy(id = "vts-search") private WebElement searchInput;

    @FindBy(id = "item-selector-btn-search") private WebElement searchButton;

    public MotTestSearchPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public MotTestSearchPage selectSearchCategory(String searchCategory) {
        FormCompletionHelper.selectFromDropDownByValue(searchTypePrompt, searchCategory);

        return this;
    }

    public MotTestSearchPage fillSearchValue(String searchValue) {
        searchInput.sendKeys(searchValue);

        return this;
    }

    public MotTestSearchPage clickSearchButton() {
        searchButton.click();

        return this;
    }
}
