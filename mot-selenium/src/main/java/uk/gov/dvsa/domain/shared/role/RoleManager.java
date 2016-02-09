package uk.gov.dvsa.domain.shared.role;

import uk.gov.dvsa.domain.service.RoleService;

import java.io.IOException;

public class RoleManager {
    private static RoleService roleService = new RoleService();

    public static void addRole(String userId, Role role) throws IOException {
        if(!roleService.addRole(Integer.valueOf(userId), role.getRoleName()))
            throw new IllegalStateException("Role update failed");
    }
}
