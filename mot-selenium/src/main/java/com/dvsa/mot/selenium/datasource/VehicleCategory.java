package com.dvsa.mot.selenium.datasource;

public class VehicleCategory {
    //Used for categories and subcategories as well
    public static final VehicleCategory PARENT_RFR_HOME = new VehicleCategory(0, "RFR Home");
    public static final VehicleCategory CAT_BODY_STRUCTURE_AND_GENERAL_ITEMS =
            new VehicleCategory(5690, "Body, Structure and General Items");
    public static final VehicleCategory SUBCAT_DOORS = new VehicleCategory(5697, "Doors");
    public static final VehicleCategory SUBCAT_PASSENGERS_FRONT =
            new VehicleCategory(5700, "Passengers front");

    public final int categoryId;
    public final String categoryDescription;

    public VehicleCategory(int categoryId, String categoryDescription) {
        super();
        this.categoryId = categoryId;
        this.categoryDescription = categoryDescription;
    }
}
