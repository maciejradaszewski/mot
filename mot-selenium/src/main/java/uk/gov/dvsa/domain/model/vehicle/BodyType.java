package uk.gov.dvsa.domain.model.vehicle;

public enum BodyType {
    Hatchback("h"),
    Motorcycle("18"),
    Limousine("12"),
    Pickup("26"),
    Coupe("05"),
    FlatLorry("38");

    private final String code;

    private BodyType(String code) {
        this.code = code;
    }

    public String getCode() {
        return code;
    }

    public static BodyType getRandomBodyType(){
        return values()[(int) (Math.random() * values().length)];
    }
}
