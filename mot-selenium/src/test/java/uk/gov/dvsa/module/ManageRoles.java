package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.dvsa.ManageRolesPage;

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

    private ManageRolesPage getManageRolesPage() {
        return new ManageRolesPage(pageNavigator.getDriver());
    }
}
