package uk.gov.dvsa.ui.pages.dvsa;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

import java.util.ArrayList;
import java.util.List;

public class RolesAndAssociationsPage extends Page{

    private static final String PAGE_TITLE = "Roles and Associations";

    @FindBy(id = "user-trade-roles-table") private WebElement rolesTableElement;
    @FindBy(className = "history-go-back") private WebElement returnToUserProfileLink;

    public RolesAndAssociationsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public List<String> getRoleValues() {
        List<String> roleValues = new ArrayList<>();
        roleValues.add(rolesTableElement.findElement(By.className("key-value-list__key")).getText());
        roleValues.add(rolesTableElement.findElement(By.className("key-value-list__value")).getText());
        return roleValues;
    }

    public UserSearchProfilePage clickReturnToUserProfile() {
        returnToUserProfileLink.click();
        return new UserSearchProfilePage(driver);
    }
}
