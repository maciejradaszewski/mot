package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.AreaOfficerAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.Page;

public class AssociateASitePage extends Page {
    public static final String PATH = "/authorised-examiner/%s/site/link";
    private static final String PAGE_TITLE = "Associate a site";

    @FindBy(id = "siteNumber") private WebElement siteNumberInput;
    @FindBy(id = "submitAeLink") private WebElement associateThisSiteButton;

    public AssociateASitePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public AreaOfficerAuthorisedExaminerViewPage searchForSiteNumberAndAssociate(String siteNumber) {
        siteNumberInput.sendKeys(siteNumber);
        clickOnAssociateThisSiteButton();
        return new AreaOfficerAuthorisedExaminerViewPage(driver);
    }

    public AssociateASitePage clickOnAssociateThisSiteButton() {
        associateThisSiteButton.click();
        return this;
    }
}
