package com.dvsa.mot.selenium.datasource;

public class FailureLocation {

    public static final FailureLocation failureLocation_DEFAULT =
            new FailureLocation(Lateral.notApply, Longitudinal.notApply, Vertical.notApply, "",
                    false);
    public static final FailureLocation failureLocation_CASE1 =
            new FailureLocation(Lateral.nearside, Longitudinal.rear, Vertical.lower, "Damages",
                    false);
    public static final FailureLocation failureLocation_CASE2 =
            new FailureLocation(Lateral.offside, Longitudinal.front, Vertical.inner,
                    "Brake pads worn", true);
    public static final FailureLocation failureLocation_WORN =
            new FailureLocation(Lateral.notApply, Longitudinal.notApply, Vertical.notApply, "worn",
                    false);
    public static final FailureLocation failureLocation_WONR =
            new FailureLocation(Lateral.notApply, Longitudinal.notApply, Vertical.notApply, "wonr",
                    false);
    public static final FailureLocation failureLocation_ALMOST_GONE =
            new FailureLocation(Lateral.offside, Longitudinal.front, Vertical.inner, "almost gone",
                    false);
    public static final FailureLocation failureLocation_CANVAS_SHOWING =
            new FailureLocation(Lateral.offside, Longitudinal.front, Vertical.inner,
                    "canvas showing", false);


    public enum Lateral {notApply, nearside, center, offside}


    public enum Longitudinal {notApply, front, rear}


    public enum Vertical {notApply, upper, lower, inner, outer}


    public final Lateral lateral;
    public final Longitudinal longitudinal;
    public final Vertical vertical;
    public final String description;
    public final boolean isDangerousFailure;

    public FailureLocation(Lateral lateral, Longitudinal longitudinal, Vertical vertical,
            String description, boolean isDangerousFailure) {
        super();
        this.lateral = lateral;
        this.longitudinal = longitudinal;
        this.vertical = vertical;
        this.description = description;
        this.isDangerousFailure = isDangerousFailure;
    }
}
