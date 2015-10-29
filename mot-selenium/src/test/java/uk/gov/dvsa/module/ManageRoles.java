package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.dvsa.ManageRolesPage;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchResultsPage;

import java.util.Arrays;
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

    public boolean isSearchResultAccurate(User user) {
        UserSearchResultsPage userSearchResultsPage = new UserSearchResultsPage(pageNavigator.getDriver());
        List<String> userDetails = userSearchResultsPage.getUserDetails(user);
        boolean userName = userDetails.get(0).equals(user.getNamesAndSurname());
        boolean address = userDetails.get(1).contains(user.getAddressLine1());
        boolean postcode = userDetails.get(2).equals(user.getPostcode());
        return userName && address && postcode;
    }

    private ManageRolesPage getManageRolesPage() {
        return new ManageRolesPage(pageNavigator.getDriver());
    }
}
