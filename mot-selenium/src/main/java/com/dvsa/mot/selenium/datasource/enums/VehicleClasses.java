package com.dvsa.mot.selenium.datasource.enums;

public enum VehicleClasses {
    one("1"), two("2"), three("3"), four("4"), five("5"), seven("7");

    private final String vehicleClass;

    private VehicleClasses(String id) {
        this.vehicleClass = id;
    }

    public String getId() {
        return this.vehicleClass;
    }
};
