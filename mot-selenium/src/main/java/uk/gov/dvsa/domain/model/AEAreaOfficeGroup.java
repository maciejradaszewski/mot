package uk.gov.dvsa.domain.model;

public enum AEAreaOfficeGroup {
    AREAOFFICE1("1", "01"),
    AREAOFFICE2("2", "02"),
    AREAOFFICE3("3", "03"),
    AREAOFFICE4("4", "04"),
    AREAOFFICE5("5", "05"),
    AREAOFFICE6("6", "06"),
    AREAOFFICE7("7", "07"),
    AREAOFFICE8("8", "08"),
    AREAOFFICE9("9", "09"),
    AREAOFFICE10("10", "10"),
    AREAOFFICE11("11", "11"),
    AREAOFFICE12("12", "12"),
    AREAOFFICE13("13", "13"),
    AREAOFFICE14("14", "14"),
    AREAOFFICE15("15", "15"),
    AREAOFFICE16("16", "16");

    private final String value;
    private final String name;

    AEAreaOfficeGroup(String value, String name) {
        this.value = value;
        this.name = name;
    }

    public String getValue() {
            return value;
    }

    public String getName() {
        return name;
    }
}
