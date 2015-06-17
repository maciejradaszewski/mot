package com.dvsa.mot.selenium.datasource.enums;

public enum VehicleTransmissionType {

    Automatic("Automatic", "1", "a"),
    Manual("Manual", "2", "m");

    private final String transmissionName;
    private final String transmissionId;
    private final String transmissionCode;

    private VehicleTransmissionType(String transmissionName, String transmissionId, String transmissionCode) {
        this.transmissionName = transmissionName;
        this.transmissionId = transmissionId;
        this.transmissionCode = transmissionCode;
    }

    public String getTransmissionName() {
        return transmissionName;
    }

    public String getTransmissionId() {
        return transmissionId;
    }

    public String getTransmissionCode() {
        return transmissionCode;
    }
}
