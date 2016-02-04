package com.dvsa.mot.selenium.datasource.enums;

public enum CompanyType {
    Company("RC", "Company"),
    Partnership("P", "Partnership"),
    SoleTrader("ST","Sole Trader"),
    PublicBody("PA","Public Body");

    private String value;
    private String name;

    private CompanyType(String value, String name) {
        this.name = name;
        this.value = value;
    }

    public String getName() {
        return name;
    }

    public String getValue() {
        return value;
    }
}
