package com.dvsa.mot.selenium.datasource.enums;

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
}
