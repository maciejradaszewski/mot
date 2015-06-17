package com.dvsa.mot.selenium.datasource;

public class FailureRejection {
    public static final FailureRejection HORN_CONTROL_INSECURE =
            new FailureRejection(ReasonForRejection.HORN_CONTROL_INSECURE,
                    FailureLocation.failureLocation_CASE2);
    public static final FailureRejection HORN_CONTROL_MISSING =
            new FailureRejection(ReasonForRejection.HORN_CONTROL_MISSING,
                    FailureLocation.failureLocation_CASE2);
    public static final FailureRejection BALLJOINT_EXCESSIVELY_DETERIORATED =
            new FailureRejection(ReasonForRejection.BALLJOINT_EXCESSIVELY_DETERIORATED,
                    FailureLocation.failureLocation_WORN);
    public static final FailureRejection BALLJOINT_EXCESSIVELY_DETERIORATED_WONR =
            new FailureRejection(ReasonForRejection.BALLJOINT_EXCESSIVELY_DETERIORATED,
                    FailureLocation.failureLocation_WONR);
    public static final FailureRejection BRAKE_LININGS_LESS_THAN_1_5_THICK =
            new FailureRejection(ReasonForRejection.BRAKE_LININGS_LESS_THAN_1_5_THICK,
                    FailureLocation.failureLocation_ALMOST_GONE);
    public static final FailureRejection
            VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS = new FailureRejection(
            ReasonForRejection.VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS,
            FailureLocation.failureLocation_CANVAS_SHOWING);
    public static final FailureRejection BRAKE_PERFORMANCE_NOT_TESTED =
            new FailureRejection(ReasonForRejection.BRAKE_PERFORMANCE_NOT_TESTED,
                    FailureLocation.failureLocation_DEFAULT);
    public static final FailureRejection MOUNTING_BROKEN_PIPE =
            new FailureRejection(ReasonForRejection.MOUNTING_EXCESSIVELY_DETERIORATED_FLEXIBLE_PIPE,
                    FailureLocation.failureLocation_DEFAULT);

    public final ReasonForRejection reason;
    public final FailureLocation failureLocation;

    public FailureRejection(ReasonForRejection reason, FailureLocation failureLocation) {
        super();
        this.reason = reason;
        this.failureLocation = failureLocation;
    }
}
