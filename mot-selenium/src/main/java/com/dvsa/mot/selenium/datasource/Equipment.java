package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.framework.Utilities;

import java.text.SimpleDateFormat;
import java.util.Date;

public class Equipment {
    public static final Equipment IId_1 =
            new Equipment("1", "BLINK-182", Utilities.getDate(2014, 7, 11), "EGA, 1996 Spec",
                    "50-01Y", "ALLEN", "I, II, III, IV, V, VII");
    public static final Equipment IId_2 =
            new Equipment("2", "HAMMER-ZEIT-11", Utilities.getDate(2014, 7, 11),
                    "EGA, Pre 1996 Spec", "42-902-20", "ALLEN", "I, II, III, IV, V, VII");

    public final String equipmentId;
    public final String serialNumber;
    public final Date dateAdded;
    public final String equipmentType;
    public final String model;
    public final String make;
    public final String classes;

    public Equipment(String equipmentId, String serialNumber, Date dateAdded, String equipmentType,
            String model, String make, String classes) {
        super();
        this.equipmentId = equipmentId;
        this.serialNumber = serialNumber;
        this.dateAdded = dateAdded;
        this.equipmentType = equipmentType;
        this.model = model;
        this.make = make;
        this.classes = classes;
    }

    public String getEquipmentId() {
        return equipmentId;
    }

    public String getSerialNumber() {
        return serialNumber;
    }

    public String getDateAdded() {
        SimpleDateFormat df = new SimpleDateFormat("dd MMM yyyy");
        return df.format(this.dateAdded);
    }

    public String getEquipmentType() {
        return equipmentType;
    }

    public String getModel() {
        return model;
    }

    public String getMake() {
        return make;
    }

    public String getClasses() {
        return classes;
    }
}
