package com.dvsa.mot.selenium.datasource.enums;

public enum BodyType {

    Hatchback("Hatchback", "h"),
    Motorcycle("Motorcycle", "18"),
    Limousine("Limousine", "12"),
    Pickup("Pickup", "26"),
    Coupe("Coupe", "05"),
    FlatLorry("FlatLorry", "38");

    private final String bodyName;
    private final String code;

    private BodyType(String bodyName, String code) {
        this.bodyName = bodyName;
        this.code = code;
    }

    public String getCode() {
        return code;
    }

    public String getName() {
        return this.bodyName;
    }
}
