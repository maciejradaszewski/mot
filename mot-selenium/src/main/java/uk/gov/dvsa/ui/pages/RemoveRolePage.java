package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.navigation.MotPageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.dvsa.RolesAndAssociationsPage;

public class RemoveRolePage extends Page {
    private static final String PAGE_TITLE = "Remove role";
    @FindBy(id = "confirm") private WebElement confirmButton;
    @FindBy(id = "cancel-and-return-to-profile") private WebElement cancelAndReturnLink;

    public RemoveRolePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public <T extends Page>T confirmRemoveRole(Class<T> clazz) {
        confirmButton.click();
        return MotPageFactory.newPage(driver, clazz);
    }

    public RolesAndAssociationsPage cancelRoleRemoval() {
        cancelAndReturnLink.click();
        return new RolesAndAssociationsPage(driver);
    }
}