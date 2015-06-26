package uk.gov.dvsa.domain.model.vehicle;

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

    private final String name;
    private final String Id;

    private FuelTypes(String name, String Id) {
        this.name = name;
        this.Id = Id;
    }

    public String getName() {
        return this.name;
    }

    public String getId() {
        return this.Id;
    }

    public static FuelTypes getRandomFuelType() {
        return values()[(int) (Math.random() * values().length)];
    }
}

