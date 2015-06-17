package com.dvsa.mot.selenium.datasource;

public class AdvisoryLocation {

    public static final AdvisoryLocation advisoryLocation_CASE1 =
            new AdvisoryLocation(Lateral.nearside, Longitudinal.rear, Vertical.lower, "Damages",
                    false);
    public static final AdvisoryLocation advisoryLocation_CASE2 =
            new AdvisoryLocation(Lateral.offside, Longitudinal.front, Vertical.inner,
                    "Brake pads worn", true);


    public enum Lateral {notApply, nearside, center, offside}


    public enum Longitudinal {notApply, front, rear}


    public enum Vertical {notApply, upper, lower, inner, outer}


    public final Lateral lateral;
    public final Longitudinal longitudinal;
    public final Vertical vertical;
    public final String description;
    public final boolean isDangerousFailure;

    public AdvisoryLocation(Lateral lateral, Longitudinal longitudinal, Vertical vertical,
            String description, boolean isDangerousFailure) {
        super();
        this.lateral = lateral;
        this.longitudinal = longitudinal;
        this.vertical = vertical;
        this.description = description;
        this.isDangerousFailure = isDangerousFailure;
    }
}

