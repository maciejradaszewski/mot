package com.dvsa.mot.selenium.datasource;

public class AdvisoryRejection {
    public static final AdvisoryRejection ELECTRICAL_WIRING_DAMAGED_CAUSE_SHORT =
            new AdvisoryRejection(ReasonForRejection.ELECTRICAL_WIRING_DAMAGED_CAUSE_SHORT,
                    FailureLocation.failureLocation_CASE2);
    public static final AdvisoryRejection ELECTRICAL_WIRING_DAMAGE_WIRED_EXPOSED =
            new AdvisoryRejection(ReasonForRejection.ELECTRICAL_WIRING_DAMAGE_WIRED_EXPOSED,
                    FailureLocation.failureLocation_CASE2);
    public static final AdvisoryRejection BRAKE_LININGS_LESS_THAN_1_5_THICK =
            new AdvisoryRejection(ReasonForRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK,
                    FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection BALLJOINT_EXCESSIVELY_DETERIORATED = new AdvisoryRejection(ReasonForRejection.BALLJOINT_EXCESSIVELY_DETERIORATED, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection ELECTRICAL_WIRING_DETERIORATED_CAUSE_SHORT = new AdvisoryRejection(ReasonForRejection.ELECTRICAL_WIRING_DETERIORATED_CAUSE_SHORT, FailureLocation.failureLocation_CASE2);
    public static final AdvisoryRejection DOOR_PILLAR_DETERIORATED = new AdvisoryRejection(ReasonForRejection.DOOR_PILLAR_DETERIORATED, FailureLocation.failureLocation_CASE2);
    public static final AdvisoryRejection VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS = new AdvisoryRejection(ReasonForRejection.VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection BREAK_CONTROL_SHORT = new AdvisoryRejection(ReasonForRejection.BREAK_CONTROL_SHORT, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection ROAD_WHEELS_WORN_HUB_SPIGOT = new AdvisoryRejection(ReasonForRejection.ROAD_WHEELS_WORN_HUB_SPIGOT, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection SERIOUSLY_DAMAGED = new AdvisoryRejection(ReasonForRejection.SERIOUSLY_DAMAGED, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection HOSES_MODIFIED = new AdvisoryRejection(ReasonForRejection.HOSES_MODIFIED, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection EXCESSIVELY_WEAKENED_CORROSION = new AdvisoryRejection(ReasonForRejection.EXCESSIVELY_WEAKENED_CORROSION, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection BRACKET_MODIFIED_WEAKENED = new AdvisoryRejection(ReasonForRejection.BRACKET_MODIFIED_WEAKENED, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection TREAD_DEPTH_BELOW_1_0 = new AdvisoryRejection(ReasonForRejection.TREAD_DEPTH_BELOW_1_0, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection WARNING_LAMP_INDICATES_FAULT = new AdvisoryRejection(ReasonForRejection.WARNING_LAMP_INDICATES_FAULT, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection AFFECTED_OPERATION_ANOTHER_LAMP = new AdvisoryRejection(ReasonForRejection.AFFECTED_OPERATION_ANOTHER_LAMP, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection SERIOUSLY_MISALIGNED = new AdvisoryRejection(ReasonForRejection.SERIOUSLY_MISALIGNED, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection WIRES_BROKEN_SIGNIFICANTLY_WEAKENED = new AdvisoryRejection(ReasonForRejection.WIRES_BROKEN_SIGNIFICANTLY_WEAKENED, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection DIAL_GLASS_BROKEN = new AdvisoryRejection(ReasonForRejection.DIAL_GLASS_BROKEN, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection BRACKET_EXCESSIVELY_WORN = new AdvisoryRejection(ReasonForRejection.BRACKET_EXCESSIVELY_WORN, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection TOP_BEARING_EXCESSIVELY_WORN = new AdvisoryRejection(ReasonForRejection.TOP_BEARING_EXCESSIVELY_WORN, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection SLIPPER_EXCESSIVELY_WORN = new AdvisoryRejection(ReasonForRejection.SLIPPER_EXCESSIVELY_WORN, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection EXCESSIVE_PLAY_RECEIVER_SOCKET = new AdvisoryRejection(ReasonForRejection.EXCESSIVE_PLAY_RECEIVER_SOCKET, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection DAMAGED_LEAD_CANNOT_BE_SECURELY_CONNECTED = new AdvisoryRejection(ReasonForRejection.DAMAGED_LEAD_CANNOT_BE_SECURELY_CONNECTED, FailureLocation.failureLocation_ALMOST_GONE);
    public static final AdvisoryRejection DETERIORATED_CANNOT_FUNCTION = new AdvisoryRejection(ReasonForRejection.DETERIORATED_CANNOT_FUNCTION, FailureLocation.failureLocation_ALMOST_GONE);

    public final ReasonForRejection reason;
    public final FailureLocation failureLocation;

    public AdvisoryRejection(ReasonForRejection reason, FailureLocation failureLocation) {
        super();
        this.reason = reason;
        this.failureLocation = failureLocation;
    }

}
