package com.dvsa.mot.selenium.datasource;

public class ReasonForRefusal {
    public static final ReasonForRefusal UNABLE_IDENTIFY_DATE_FIRST_USE =
            new ReasonForRefusal(1, "Unable to identify date of first use");
    public static final ReasonForRefusal VEHICLE_TOO_DIRTY_TO_EXAMINE =
            new ReasonForRefusal(2, "Vehicle is too dirty to examine");
    public static final ReasonForRefusal VEHICLE_IS_NOT_FIT_TO_BE_DRIVEN =
            new ReasonForRefusal(3, "The vehicle is not fit to be driven");
    public static final ReasonForRefusal INSECURITY_OF_LOAD =
            new ReasonForRefusal(4, "Insecurity of load or other items");
    public static final ReasonForRefusal VEHICLE_CONFIG_SIZE_UNSUITABLE =
            new ReasonForRefusal(5, "Vehicle configuration/size unsuitable");
    public static final ReasonForRefusal VEHICLE_EMITS_SUBSTANCIAL_SMOKE =
            new ReasonForRefusal(6, "Vehicle emits substantial smoke");
    public static final ReasonForRefusal UNABLE_TO_OPEN_DEVICE =
            new ReasonForRefusal(7, "Unable to open device (door, boot, etc.)");
    public static final ReasonForRefusal INSPECTION_MAY_BE_DANGEROUS =
            new ReasonForRefusal(8, "Inspection may be dangerous or cause damage");
    public static final ReasonForRefusal REQUESTED_TEST_FEE_NOT_PAID =
            new ReasonForRefusal(9, "Requested test fee not paid in advance");
    public static final ReasonForRefusal SUSPECT_MAINTENANCE_HISTORY_OF_DIESEL_ENGINE =
            new ReasonForRefusal(10, "Suspect maintenance history of diesel engine");
    public static final ReasonForRefusal MOTORCYCLE_FRAME_STAMPED_NOT_FOR_ROAD =
            new ReasonForRefusal(11, "Motorcycle frame stamped not for road use");
    public static final ReasonForRefusal VTS_NOT_AUTHORISED_TO_TEST_VEHICLE_CLASS =
            new ReasonForRefusal(26, "VTS not authorised to test vehicle class");

    public final int id;
    public final String name;

    public ReasonForRefusal(int id, String name) {
        super();
        this.id = id;
        this.name = name;
    }

    public int getId() {
        return id;
    }

    public String getName() {
        return name;
    }

}
