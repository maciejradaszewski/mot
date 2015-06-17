package com.dvsa.mot.selenium.datasource.enums;

public enum Reason {
    AccidentOrIllness("Accident or illness of tester"),
    AbortedByVE("Aborted by VE"),
    VehicleRegisteredInError("Vehicle registered in error"),
    testEquipmentIssue("Test equipment issue"),
    VTSincident("VTS incident"),
    incorrectLocation("Incorrect location"),
    dangerousOrCauseDamage("Inspection may be dangerous or cause damage");

    private final String description;

    private Reason(String description) {
        this.description = description;
    }

    public String getReasonDescription() {
        return this.description;
    }
}
