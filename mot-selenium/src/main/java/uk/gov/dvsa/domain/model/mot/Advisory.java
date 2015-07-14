package uk.gov.dvsa.domain.model.mot;

public class Advisory {

    public enum Lateral {notApply, nearside, center, offside}

    public enum Longitudinal {notApply, front, rear}

    public enum Vertical {notApply, upper, lower, inner, outer}

    public static final String DESCRIPTION = "Brake pads worn";

    @Override
    public String toString() {
        return "Advisory{}";
    }
}
