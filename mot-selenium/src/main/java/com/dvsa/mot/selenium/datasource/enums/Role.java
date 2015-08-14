package com.dvsa.mot.selenium.datasource.enums;

public enum Role {
    AED("AED", "Authorised examiner delegate", "Authorised examiner delegate", "AED","2"),
    AEDM("AEDM", "AEDM", "Authorised Examiner Designated Manager", "AEDM","1"),
    SITE_MANAGER("Site Manager", "Site Manager", "Site Manager", "SITE-MANAGER",""),
    SITE_ADMIN("Site Admin", "Site Admin", "Site Admin", "SITE-ADMIN",""),
    TESTER("Tester", "Tester", "Tester", "TESTER","");

    private final String name;
    private final String shortName;
    private final String fullName;
    private final String assignRoleName;
    private final String roleId;

    public String getName() {
        return name;
    }

    public String getShortName() {
        return shortName;
    }

    public String getFullName() {
        return fullName;
    }

    public String getAssignRoleName() {
        return assignRoleName;
    }

    public String getRoleId() {
        return roleId;
    }

    private Role(String name, String shortName, String fullName, String assignRoleName , String roleId) {
        this.name = name;
        this.shortName = shortName;
        this.fullName = fullName;
        this.assignRoleName = assignRoleName;
        this.roleId = roleId;
    }
}
