package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class VtsSearchForAVtsPage extends Page {
    public static final String path = "/vehicle-testing-station/search";
    private static final String PAGE_TITLE = "Search for site information by...";

    @FindBy(id = "site_number") private WebElement siteIdInputBox;
    @FindBy(id = "site_name") private WebElement siteNameInputBox;
    @FindBy(id = "site_postcode") private WebElement sitePostcodeInputBox;
    @FindBy(id = "submitSiteSearch") private WebElement searchButton;

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public VtsSearchForAVtsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    public VtsSearchResultsPage searchForVts(String siteId) {
        FormCompletionHelper.enterText(siteIdInputBox, siteId);
        searchButton.click();
        return new VtsSearchResultsPage(driver);
    }
}
