package com.dvsa.mot.selenium.datasource;

public enum InspectionOutcome {
    Satisfactory("Satisfactory"), ShortComingsFound("Shortcomings found"), Abandoned("Abandoned");

    private String inspectionOutcome;

    private InspectionOutcome(String s) {
        this.inspectionOutcome = s;
    }

    public String getInspectionOutcome() {
        return inspectionOutcome;
    }
}
