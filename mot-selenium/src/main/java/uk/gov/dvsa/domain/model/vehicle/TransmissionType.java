package uk.gov.dvsa.domain.model.vehicle;

public enum TransmissionType {
    Automatic("Automatic", "1", "a"),
    Manual("Manual", "2", "m");

    private final String name;
    private final String id;
    private final String code;

    private TransmissionType(String name, String id, String code) {
        this.name = name;
        this.id = id;
        this.code = code;
    }

    public String getName() {
        return name;
    }

    public String getId() {
        return id;
    }

    public String getCode() {
        return code;
    }

    public static TransmissionType getTransmissionType(){
        return values()[(int) (Math.random() * values().length)];
    }
}
