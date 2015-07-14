package uk.gov.dvsa.domain.model.vehicle;

public enum VehicleDetails {
    BMW("BMW", "18811", "ALPINA", "01459"),
    Ford_Mondeo("FORD", "18837", "MONDEO", "01698"),
    Ford_STREETKA("FORD", "18837", "STREETKA", "016A2"),
    Fuso("FUSO", "1883A", "CANTER", "016B0"),
    HarleyDavidson("HARLEY DAVIDSON", "18840", "FLHC", "016D8"),
    Hyundai("HYUNDAI", "1884D", "I40", "0189F"),
    Indian("INDIAN", "1884E", "DAKOTA", "018B1"),
    Kawasaki_100("KAWASAKI", "1885F", "ZRX1100", "0197F"),
    Kawasaki_200R("KAWASAKI", "1885F", "ZRX1200R", "01980"),
    MercedesBenz_300D("MERCEDES", "1887E", "300 D", "01AFA"),
    MercedesBenz_190("MERCEDES", "1887E", "190", "01ADC"),
    Piaggio("PIAGGIO", "188A0", "NRG", "01D37"),
    Porsche("PORSCHE", "188A2", "BOXSTER", "01D5E"),
    Renault("RENAULT", "188A9", "CLIO", "01D93"),
    Subaru("SUBARU", "188C0", "IMPREZA", "01E9A"),
    Suzuki_Baleno("SUZUKI", "188C2", "BALENO", "01EB8"),
    Suzuki_Cappucino("SUZUKI", "188C2", "CAPPUCCINO", "01EBA"),
    Vauxhall("VAUXHALL", "188D2", "ASTRA", "01FF3"),
    Volkswagen("VOLKSWAGEN", "188D5", "PASSAT", "02037"),
    Other("Other","OTHER", "Other","OTHER");

    private final String make;
    private final String Id;

    private final String makeId;
    private final String modelId;

    private VehicleDetails(String vehicleMake, String vehicleID, String makeId, String modelId) {
        this.make = vehicleMake;
        this.Id = vehicleID;
        this.makeId = makeId;
        this.modelId = modelId;
    }

    public String getMake() {
        return this.make;
    }

    public String getId() {
        return this.Id;
    }

    public String getMakeId() {
        return makeId;
    }

    public String getModelId() {
        return modelId;
    }

    public static VehicleDetails getRandomData() {
        return values()[(int) (Math.random() * values().length)];
    }
}
