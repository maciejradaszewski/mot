package com.dvsa.mot.selenium.datasource.enums;

public enum FuelTypes {
    Petrol("Petrol", "PE"),
    Diesel("Diesel", "DI"),
    Electric("Electric", "EL"),
    Steam("Steam", "ST"),
    CNG("CNG", "CN"),
    LNG("LNG", "LN"),
    LPG("LPG", "LP"),
    Fuelcell("Fuelcell", "FC"),
    Other("Other", "OT");

    private final String fuelName;
    private final String fuelId;

    private FuelTypes(String fuelName, String fuelId) {
        this.fuelName = fuelName;
        this.fuelId = fuelId;
    }

    public String getFuelName() {
        return this.fuelName;
    }

    public String getFuelId() {
        return this.fuelId;
    }
}
