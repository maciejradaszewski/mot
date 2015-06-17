package com.dvsa.mot.selenium.datasource.enums;

public enum MotSearchBy {
    Vts("vts"),
    VtsDate("vtsDate"),
    Tester("tester"),
    Vrm("vrm"),
    Vin("vin");

    private final String motTestTypeId;

    private MotSearchBy(String motTestTypeId) {
        this.motTestTypeId = motTestTypeId;
    }

    public String getMotTestTypeId() {
        return this.motTestTypeId;
    }
}
