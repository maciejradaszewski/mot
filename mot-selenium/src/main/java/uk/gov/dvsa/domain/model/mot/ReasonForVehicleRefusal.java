package uk.gov.dvsa.domain.model.mot;

public enum ReasonForVehicleRefusal {
    UNABLE_IDENTIFY_DATE_FIRST_USE("Unable to identify date of first use"),
    VEHICLE_TOO_DIRTY_TO_EXAMINE("Vehicle is too dirty to examine"),
    VEHICLE_IS_NOT_FIT_TO_BE_DRIVEN("The vehicle is not fit to be driven"),
    INSECURITY_OF_LOAD("Insecurity of load or other items"),
    VEHICLE_CONFIG_SIZE_UNSUITABLE("Vehicle configuration/size unsuitable"),
    VEHICLE_EMITS_SUBSTANTIAL_SMOKE("Vehicle emits substantial smoke"),
    UNABLE_TO_OPEN_DEVICE("Unable to open device (door, boot, etc.)"),
    INSPECTION_MAY_BE_DANGEROUS("Inspection may be dangerous or cause damage"),
    REQUESTED_TEST_FEE_NOT_PAID("Requested test fee not paid in advance"),
    SUSPECT_MAINTENANCE_HISTORY_OF_DIESEL_ENGINE("Suspect maintenance history of diesel engine"),
    MOTORCYCLE_FRAME_STAMPED_NOT_FOR_ROAD("Motorcycle frame stamped not for road use"),
    VTS_NOT_AUTHORISED_TO_TEST_VEHICLE_CLASS("VTS not authorised to test vehicle class");

    private final String description;

    private ReasonForVehicleRefusal(String description) {
        this.description = description;
    }

    public String getDescription() {
        return this.description;
    }

}