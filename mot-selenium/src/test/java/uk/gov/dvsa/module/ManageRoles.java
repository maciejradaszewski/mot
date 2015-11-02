package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.ProfileOfPage;
import uk.gov.dvsa.ui.pages.dvsa.ManageRolesPage;
import uk.gov.dvsa.ui.pages.dvsa.RolesAndAssociationsPage;

import java.util.List;

public class ManageRoles {

    private PageNavigator pageNavigator;

    public ManageRoles(PageNavigator pageNavigator) {
        this.pageNavigator = pageNavigator;
    }

    public void addRole(String role) {
        ManageRolesPage manageRolesPage = getManageRolesPage();
        switch (role) {
            case "AO2":
                manageRolesPage.clickAddRoleAO2User();
                break;
            case "VE":
                manageRolesPage.clickAddRoleVELink();
                break;
            default:
                break;
        }
        manageRolesPage.clickAddRoleButton();
    }

    public void removeRole(String role) {
        ManageRolesPage manageRolesPage = getManageRolesPage();
        switch (role) {
            case "AO2":
                manageRolesPage.clickRemoveRoleAO2Link();
                break;
            case "VE":
                manageRolesPage.clickRemoveRoleVELink();
                break;
            default:
                break;
        }
        manageRolesPage.clickRemoveRoleButton();
    }

    public String confirmRemoveRoleAction() {
        return getManageRolesPage().getNotificationText();
    }

    public boolean isRolesTableContainsValidTesterData() {
        RolesAndAssociationsPage rolesAndAssociationsPage = new RolesAndAssociationsPage(pageNavigator.getDriver());
        List<String> roleValues = rolesAndAssociationsPage.getRoleValues();
        boolean vtsAddress = roleValues.get(0).contains("Flat Test_Site Lord House, Boston, BT2 4RR");
        boolean role = roleValues.get(1).equals("Tester");
        return vtsAddress && role;
    }

    public boolean isRolesAndAssociationsLinkDisplayedOnProfileOfPage() {
        return new ProfileOfPage(pageNavigator.getDriver()).isRolesAndAssociationsLinkDisplayed();
    }

    private ManageRolesPage getManageRolesPage() {
        return new ManageRolesPage(pageNavigator.getDriver());
    }
}
