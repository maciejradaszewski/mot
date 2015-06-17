package com.dvsa.mot.selenium.datasource;

public class PRSrejection {
    public static final PRSrejection HORN_CONTROL_INSECURE =
            new PRSrejection(ReasonForRejection.HORN_CONTROL_INSECURE,
                    FailureLocation.failureLocation_CASE1);
    public static final PRSrejection HORN_CONTROL_MISSING =
            new PRSrejection(ReasonForRejection.HORN_CONTROL_MISSING,
                    FailureLocation.failureLocation_CASE1);
    public static final PRSrejection
            VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS = new PRSrejection(
            ReasonForRejection.VEHICLES_1ST_USE_AFTER_2_JANUARY_TREAD_DEPTH_BELOW_REQUIREMENTS,
            FailureLocation.failureLocation_CANVAS_SHOWING);
    public static final PRSrejection WARNING_LAMP_MISSING =
            new PRSrejection(ReasonForRejection.WARNING_LAMP_MISSING,
                    FailureLocation.failureLocation_DEFAULT);

    public final ReasonForRejection reason;
    public final FailureLocation failureLocation;

    public PRSrejection(ReasonForRejection reason, FailureLocation failureLocation) {
        super();
        this.reason = reason;
        this.failureLocation = failureLocation;
    }
}
