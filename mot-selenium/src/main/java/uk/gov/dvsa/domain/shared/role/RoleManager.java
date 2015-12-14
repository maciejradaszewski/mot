package uk.gov.dvsa.domain.shared.role;

import uk.gov.dvsa.domain.service.RoleService;

public class RoleManager {
    private static RoleService roleService = new RoleService();

    public static void addRole(String userId, Role role){
        if(!roleService.addRole(Integer.valueOf(userId), role.getRoleName()))
            throw new IllegalStateException("Role update failed");
    }
}
