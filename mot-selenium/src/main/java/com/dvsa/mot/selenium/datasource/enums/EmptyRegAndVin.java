package com.dvsa.mot.selenium.datasource.enums;

public enum EmptyRegAndVin {

    Please_select("Please select"),
    Missing("Missing"),
    NotFound("Not found"),
    NotRequired("Not required");


    private final String description;

    private EmptyRegAndVin(String description) {
        this.description = description;
    }
    public String getReasonDescription() {
        return this.description;
    }
}
